<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /** Tampilkan daftar semua user */
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    /** Simpan user baru */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => ['required', 'confirmed', Password::min(6)],
            'role'     => 'required|in:admin,petugas',
        ], [
            'nama.required'      => 'Nama wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.',
            'role.required'      => 'Role wajib dipilih.',
        ]);

        User::create([
            'nama'     => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return response()->json(['message' => 'User berhasil ditambahkan.']);
    }

    /** Ambil data user untuk modal edit */
    public function show(User $user)
    {
        return response()->json([
            'id'       => $user->id,
            'nama'     => $user->nama,
            'username' => $user->username,
            'role'     => $user->role,
        ]);
    }

    /** Update data user */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'role'     => 'required|in:admin,petugas',
        ], [
            'nama.required'     => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'role.required'     => 'Role wajib dipilih.',
        ]);

        $user->update([
            'nama'     => $request->nama,
            'username' => $request->username,
            'role'     => $request->role,
        ]);

        return response()->json(['message' => 'User berhasil diperbarui.']);
    }

    /** Ganti password user */
    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password berhasil diubah.']);
    }

    /** Hapus user */
    public function destroy(User $user)
    {
        // Tidak boleh hapus diri sendiri
        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'Tidak dapat menghapus akun yang sedang aktif.'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus.']);
    }
}
