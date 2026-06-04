<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. TAMPILKAN SEMUA USER (Read)
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // 2. TAMPILKAN FORM TAMBAH USER (Create)
    public function create()
    {
        return view('users.create');
    }

    // 3. SIMPAN DATA USER BARU (Create - Proses)
    public function store(Request $request)
    {
        $request->validate([
            'no_induk' => 'required|string|unique:users,no_induk',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'no_hp' => 'nullable|string',
            'role' => 'required|in:supervisor,admin,dosen',
        ]);

        User::create([
            'no_induk' => $request->no_induk,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'no_hp' => $request->no_hp,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // 4. TAMPILKAN DETAIL USER (Opsional, kita lewati dulu)
    public function show(User $user)
    {
        //
    }

    // 5. TAMPILKAN FORM EDIT USER (Update)
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // 6. PROSES UPDATE DATA USER (Update - Proses)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'no_induk' => 'required|string|unique:users,no_induk,' . $user->id,
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6', // Password boleh kosong jika tidak diubah
            'no_hp' => 'nullable|string',
            'role' => 'required|in:supervisor,admin,dosen',
        ]);

        $data = [
            'no_induk' => $request->no_induk,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
        ];

        // Jika password diisi, enkripsi dan masukkan ke data update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    // 7. HAPUS DATA USER (Delete)
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}