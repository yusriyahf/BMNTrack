<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::with(['gedung', 'createdBy'])->withCount('barang');

        if ($request->filled('gedung_id')) {
            $query->where('gedung_id', $request->gedung_id);
        }
        if ($request->filled('search')) {
            $query->where('nama_ruangan', 'like', '%' . $request->search . '%');
        }

        $ruangan = $query->latest()->paginate(10)->withQueryString();
        $gedungs  = Gedung::orderBy('nama_gedung')->get();

        return view('ruangan.index', compact('ruangan', 'gedungs'));
    }

    public function create()
    {
        $gedungs = Gedung::orderBy('nama_gedung')->get();
        return view('ruangan.create', compact('gedungs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gedung_id'        => 'required|exists:gedung,id',
            'nama_ruangan'     => 'required|string|max:100',
            'luas_ruangan'     => 'nullable|string|max:100',
            'lantai'           => 'required|integer|min:1',
            'pic_ruangan'      => 'nullable|string|max:100',
            'foto_pic'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_ruangan'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tanggal_pendataan'=> 'nullable|date',
        ], [
            'gedung_id.required'    => 'Gedung wajib dipilih.',
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'lantai.required'       => 'Lantai wajib diisi.',
        ]);

        $data = $request->only([
            'gedung_id', 'nama_ruangan', 'luas_ruangan',
            'lantai', 'pic_ruangan', 'tanggal_pendataan',
        ]);
        $data['created_by'] = Auth::id();

        if ($request->hasFile('foto_ruangan')) {
            $data['foto_ruangan'] = $request->file('foto_ruangan')->store('ruangan', 'public');
        }
        if ($request->hasFile('foto_pic')) {
            $data['foto_pic'] = $request->file('foto_pic')->store('ruangan/pic', 'public');
        }

        Ruangan::create($data);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Ruangan $ruangan)
    {
        $ruangan->load(['gedung', 'createdBy', 'barang']);
        return view('ruangan.show', compact('ruangan'));
    }

    public function edit(Ruangan $ruangan)
    {
        $gedungs = Gedung::orderBy('nama_gedung')->get();
        return view('ruangan.edit', compact('ruangan', 'gedungs'));
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $request->validate([
            'gedung_id'        => 'required|exists:gedung,id',
            'nama_ruangan'     => 'required|string|max:100',
            'luas_ruangan'     => 'nullable|string|max:100',
            'lantai'           => 'required|integer|min:1',
            'pic_ruangan'      => 'nullable|string|max:100',
            'foto_pic'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_ruangan'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tanggal_pendataan'=> 'nullable|date',
        ]);

        $data = $request->only([
            'gedung_id', 'nama_ruangan', 'luas_ruangan',
            'lantai', 'pic_ruangan', 'tanggal_pendataan',
        ]);

        if ($request->hasFile('foto_ruangan')) {
            if ($ruangan->foto_ruangan) {
                Storage::disk('public')->delete($ruangan->foto_ruangan);
            }
            $data['foto_ruangan'] = $request->file('foto_ruangan')->store('ruangan', 'public');
        }
        if ($request->hasFile('foto_pic')) {
            if ($ruangan->foto_pic) {
                Storage::disk('public')->delete($ruangan->foto_pic);
            }
            $data['foto_pic'] = $request->file('foto_pic')->store('ruangan/pic', 'public');
        }

        $ruangan->update($data);

        return redirect()->route('ruangan.show', $ruangan)->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        if ($ruangan->foto_ruangan) {
            Storage::disk('public')->delete($ruangan->foto_ruangan);
        }
        if ($ruangan->foto_pic) {
            Storage::disk('public')->delete($ruangan->foto_pic);
        }
        $ruangan->delete();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
