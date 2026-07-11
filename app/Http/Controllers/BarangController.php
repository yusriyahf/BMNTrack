<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('ruangan.gedung');

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $barang  = $query->latest()->paginate(10)->withQueryString();
        return view('barang.index', compact('barang'));
    }

    public function create(Ruangan $ruangan)
    {
        return view('barang.create', compact('ruangan'));
    }

    public function store(Request $request, Ruangan $ruangan)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'kategori'    => 'nullable|string|max:100',
            'jumlah'      => 'required|integer|min:1',
            'kondisi'     => 'required|in:Aman,Rusak',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'keterangan'  => 'nullable|string',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'jumlah.required'      => 'Jumlah wajib diisi.',
            'kondisi.required'     => 'Kondisi wajib dipilih.',
        ]);

        $data = $request->only(['nama_barang', 'kategori', 'jumlah', 'kondisi', 'keterangan']);
        $data['ruangan_id'] = $ruangan->id;

        if ($request->hasFile('foto_barang')) {
            $data['foto_barang'] = $request->file('foto_barang')->store('barang', 'public');
        } elseif ($request->filled('foto_barang_base64')) {
            // Handle base64 from camera capture
            $base64 = $request->foto_barang_base64;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                $base64 = substr($base64, strpos($base64, ',') + 1);
                $ext    = strtolower($type[1]);
                $decoded = base64_decode($base64);
                $filename = 'barang/' . uniqid() . '.' . $ext;
                Storage::disk('public')->put($filename, $decoded);
                $data['foto_barang'] = $filename;
            }
        }

        Barang::create($data);

        return redirect()->route('ruangan.show', $ruangan)->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'kategori'    => 'nullable|string|max:100',
            'jumlah'      => 'required|integer|min:1',
            'kondisi'     => 'required|in:Aman,Rusak',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'keterangan'  => 'nullable|string',
        ]);

        $data = $request->only(['nama_barang', 'kategori', 'jumlah', 'kondisi', 'keterangan']);

        if ($request->hasFile('foto_barang')) {
            if ($barang->foto_barang) {
                Storage::disk('public')->delete($barang->foto_barang);
            }
            $data['foto_barang'] = $request->file('foto_barang')->store('barang', 'public');
        } elseif ($request->filled('foto_barang_base64')) {
            $base64 = $request->foto_barang_base64;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                $base64 = substr($base64, strpos($base64, ',') + 1);
                $ext    = strtolower($type[1]);
                $decoded = base64_decode($base64);
                $filename = 'barang/' . uniqid() . '.' . $ext;
                Storage::disk('public')->put($filename, $decoded);
                if ($barang->foto_barang) {
                    Storage::disk('public')->delete($barang->foto_barang);
                }
                $data['foto_barang'] = $filename;
            }
        }

        $barang->update($data);

        return redirect()->route('ruangan.show', $barang->ruangan)->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $ruangan = $barang->ruangan;
        if ($barang->foto_barang) {
            Storage::disk('public')->delete($barang->foto_barang);
        }
        $barang->delete();
        return redirect()->route('ruangan.show', $ruangan)->with('success', 'Barang berhasil dihapus.');
    }
}
