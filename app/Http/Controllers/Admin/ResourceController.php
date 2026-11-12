<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Daerah;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Satu controller untuk SEMUA modul admin (daerah, kategori, kuliner, budaya, destinasi,
 * berita, pengalaman). Modul yang dimaksud dibaca dari nama route, mis. "admin.kuliner.index"
 * -> modul "kuliner". Definisi kolom/field/validasi ada di config/admin.php.
 * Setara AdminResourceController di Class Diagram dan mesin CRUD generik di admin.js lama.
 */
class ResourceController extends Controller
{
    /* ===================== Aksi CRUD ===================== */

    /** GET /admin/{modul} : tabel data + modal form */
    public function index(Request $request): View
    {
        [$kunci, $modul] = $this->modul($request);

        $q = $this->teks($request, 'q');

        $query = $modul['model']::query()
            ->with($modul['with'] ?? [])
            ->orderBy($modul['order'], ($modul['order_asc'] ?? true) ? 'asc' : 'desc');

        // Pencarian: semua kolom teks tabel (termasuk kolom relasi seperti daerah.nama_daerah)
        if ($q !== '') {
            $pola = '%'.addcslashes($q, '%_\\').'%';
            $query->where(function ($w) use ($modul, $pola) {
                foreach ($modul['columns'] as $col) {
                    if (in_array($col['type'] ?? 'teks', ['image', 'date'], true)) {
                        continue;
                    }
                    if (str_contains($col['key'], '.')) {
                        [$relasi, $kolom] = explode('.', $col['key'], 2);
                        $w->orWhereHas($relasi, fn ($r) => $r->where($kolom, 'like', $pola));
                    } else {
                        $w->orWhere($col['key'], 'like', $pola);
                    }
                }
            });
        }

        $baris = $query->paginate(15)->withQueryString();

        return view('admin.index', [
            'kunci' => $kunci,
            'modul' => $modul,
            'semuaModul' => config('admin.modul'),
            'baris' => $baris,
            'q' => $q,
            'opsiFk' => $this->opsiFk($modul),
        ]);
    }

    /** POST /admin/{modul} */
    public function store(Request $request): RedirectResponse
    {
        [$kunci, $modul] = $this->modul($request);

        $data = $request->validate($this->aturan($modul, $request), [], $this->namaAtribut($modul));

        $modul['model']::create($data);

        return redirect()->route("admin.$kunci.index")->with('sukses', 'Data berhasil ditambahkan.');
    }

    /** PUT /admin/{modul}/{id} */
    public function update(Request $request, string $id): RedirectResponse
    {
        [$kunci, $modul] = $this->modul($request);

        $record = $modul['model']::findOrFail($id);

        $data = $request->validate($this->aturan($modul, $request, $record->getKey()), [], $this->namaAtribut($modul));

        $record->update($data);

        return redirect()->route("admin.$kunci.index")->with('sukses', 'Data berhasil diperbarui.');
    }

    /** DELETE /admin/{modul}/{id} */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        [$kunci, $modul] = $this->modul($request);

        $record = $modul['model']::findOrFail($id);

        // Hapus juga berkas fotonya (khusus unggahan lokal, bukan URL eksternal)
        $kolomBerkas = $modul['hapus_berkas'] ?? null;
        if ($kolomBerkas && $record->{$kolomBerkas} && ! preg_match('#^https?://#i', $record->{$kolomBerkas})) {
            Storage::disk('public')->delete($record->{$kolomBerkas});
        }

        $record->delete();

        return redirect()->route("admin.$kunci.index")->with('sukses', 'Data berhasil dihapus.');
    }

    /* ===================== Pembantu ===================== */

    /** Ambil [kunci, konfigurasi] modul dari nama route (admin.<kunci>.<aksi>) */
    private function modul(Request $request): array
    {
        $kunci = explode('.', (string) $request->route()->getName())[1] ?? '';
        $modul = config("admin.modul.$kunci");

        abort_if($modul === null, 404);

        return [$kunci, $modul];
    }

    /** Bangun aturan validasi dari daftar field di config (pengganti validasi di admin.js) */
    private function aturan(array $modul, Request $request, mixed $idSendiri = null): array
    {
        $tabel = (new $modul['model'])->getTable();
        $aturan = [];

        foreach ($modul['fields'] as $f) {
            $r = [($f['required'] ?? false) ? 'required' : 'nullable'];

            switch ($f['type']) {
                case 'text':
                    $r[] = 'string';
                    $r[] = 'max:'.($f['max'] ?? 255);
                    break;
                case 'textarea':
                    $r[] = 'string';
                    $r[] = 'max:5000';
                    break;
                case 'number':
                    $r[] = 'integer';
                    $r[] = 'min:0';
                    break;
                case 'date':
                    $r[] = 'date';
                    break;
                case 'url':           // tautan biasa (Google Maps, situs rekomendasi, dst.)
                case 'image-url':
                    $r[] = 'url:http,https';
                    $r[] = 'max:500';
                    break;
                case 'select':
                    $r[] = Rule::in(array_column($f['options'], 'value'));
                    break;
                case 'select-fk':
                    // Kategori harus bertipe sesuai modul (kuliner hanya boleh kategori bertipe kuliner)
                    $exists = Rule::exists($f['fk'], 'id');
                    if (! empty($f['fkTipe'])) {
                        $exists = $exists->where('tipe', $f['fkTipe']);
                    }
                    $r[] = $exists;
                    break;
            }

            if ($f['name'] === 'slug') {
                $r[] = 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/';
                $unik = Rule::unique($tabel, 'slug')->ignore($idSendiri);   // saat edit, abaikan baris sendiri
                if ($tabel === 'kategori') {
                    $unik = $unik->where('tipe', $request->input('tipe'));  // slug kategori unik per tipe
                }
                $r[] = $unik;
            }

            if ($f['name'] === 'harga_max') {
                $r[] = function ($atribut, $nilai, $gagal) use ($request) {
                    $min = $request->input('harga_min');
                    if ($nilai !== null && $min !== null && $min !== '' && (int) $nilai < (int) $min) {
                        $gagal('Harga maksimum tidak boleh lebih kecil dari harga minimum.');
                    }
                };
            }

            $aturan[$f['name']] = $r;
        }

        return $aturan;
    }

    /** Nama field yang ramah dibaca di pesan galat */
    private function namaAtribut(array $modul): array
    {
        return collect($modul['fields'])->pluck('label', 'name')->all();
    }

    /** Opsi dropdown untuk field select-fk: ['nama_field' => [id => label, ...]] */
    private function opsiFk(array $modul): array
    {
        $opsi = [];

        foreach ($modul['fields'] as $f) {
            if ($f['type'] !== 'select-fk') {
                continue;
            }

            if ($f['fk'] === 'daerah') {
                $opsi[$f['name']] = Daerah::orderBy('nama_daerah')->pluck('nama_daerah', 'id')->all();
            } else {
                $opsi[$f['name']] = Kategori::tipe($f['fkTipe'])->orderBy('nama_kategori')->pluck('nama_kategori', 'id')->all();
            }
        }

        return $opsi;
    }
}
