<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    public function index()
    {
        $gedung = Gedung::withCount('ruangan')->latest()->paginate(10);
        return view('gedung.index', compact('gedung'));
    }

    public function create()
    {
        return view('gedung.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_gedung' => 'required|string|max:20|unique:gedung,kode_gedung',
            'nama_gedung' => 'required|string|max:100',
        ], [
            'kode_gedung.required' => 'Kode gedung wajib diisi.',
            'kode_gedung.unique'   => 'Kode gedung sudah digunakan.',
            'nama_gedung.required' => 'Nama gedung wajib diisi.',
        ]);

        Gedung::create($request->only('kode_gedung', 'nama_gedung'));

        return redirect()->route('gedung.index')->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function edit(Gedung $gedung)
    {
        return view('gedung.edit', compact('gedung'));
    }

    public function update(Request $request, Gedung $gedung)
    {
        $request->validate([
            'kode_gedung' => 'required|string|max:20|unique:gedung,kode_gedung,' . $gedung->id,
            'nama_gedung' => 'required|string|max:100',
        ]);

        $gedung->update($request->only('kode_gedung', 'nama_gedung'));

        return redirect()->route('gedung.index')->with('success', 'Gedung berhasil diperbarui.');
    }

    public function destroy(Gedung $gedung)
    {
        $gedung->delete();
        return redirect()->route('gedung.index')->with('success', 'Gedung berhasil dihapus.');
    }
}
