<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Buat base query
        $query = User::with('laboratoriums')->latest();

        // 2. Cek jika ada input pencarian teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('no_induk', 'LIKE', "%{$search}%");
            });
        }

        // 3. Cek jika ada filter dropdown role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 4. Eksekusi query dengan pagination (10 per halaman)
        $users = $query->paginate(10);
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $laboratoriums = Laboratorium::all();
        $roles = ['supervisor', 'admin', 'dosen'];
        $role = null; 

        return view('users.create', compact('laboratoriums', 'roles', 'role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_induk'        => 'required|string|max:50|unique:users,no_induk', 
            'nama'            => 'required|string|max:255',
            // Menambahkan validasi no_hp
            'no_hp'           => 'required|string|max:20', 
            'email'           => [
                'required', 
                'email', 
                'max:255', 
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/@lab\.com$/i', $value)) {
                        $fail('Email harus menggunakan domain @lab.com');
                    }
                },
            ],
            'password'        => 'required|string|min:6',
            'role'            => 'required|in:supervisor,admin,dosen',
            'laboratorium_id' => 'nullable|exists:laboratoriums,id',
        ]);

        $user = User::create([
            'no_induk' => $request->no_induk,
            'nama'     => $request->nama, 
            'email'    => $request->email,
            // Menyimpan no_hp ke database
            'no_hp'    => $request->no_hp, 
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        if ($request->role === 'admin' && $request->laboratorium_id) {
            Laboratorium::where('id', $request->laboratorium_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $laboratoriums = Laboratorium::all();
        $currentLabId = Laboratorium::where('user_id', $user->id)->first()?->id;

        return view('users.edit', compact('user', 'laboratoriums', 'currentLabId'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'no_induk'        => 'required|string|max:50|unique:users,no_induk,' . $user->id,
            'nama'            => 'required|string|max:255',
            // Menambahkan validasi no_hp saat update
            'no_hp'           => 'required|string|max:20', 
            'email'           => [
                'required', 
                'email', 
                'max:255', 
                'unique:users,email,' . $user->id,
                function ($attribute, $value, $fail) {
                    if (!preg_match('/@lab\.com$/i', $value)) {
                        $fail('Email harus menggunakan domain @lab.com');
                    }
                },
            ],
            'password'        => 'nullable|string|min:6',
            'role'            => 'required|in:supervisor,admin,dosen',
            'laboratorium_id' => 'nullable|exists:laboratoriums,id',
        ]);

        $data = [
            'no_induk' => $request->no_induk,
            'nama'     => $request->nama, 
            'email'    => $request->email,
            // Memasukkan no_hp ke array update
            'no_hp'    => $request->no_hp, 
            'role'     => $request->role,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->role === 'admin') {
            Laboratorium::where('user_id', $user->id)->update(['user_id' => null]);
            if ($request->laboratorium_id) {
                Laboratorium::where('id', $request->laboratorium_id)->update(['user_id' => $user->id]);
            }
        } else {
            Laboratorium::where('user_id', $user->id)->update(['user_id' => null]);
        }

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        Laboratorium::where('user_id', $user->id)->update(['user_id' => null]);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}