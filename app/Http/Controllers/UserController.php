<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('laboratoriums')->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $laboratoriums = Laboratorium::all();
        return view('users.create', compact('laboratoriums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_induk'        => 'required|string|max:50|unique:users,no_induk', 
            'nama'            => 'required|string|max:255',
            'email'           => 'required|email|max:255|unique:users,email',
            'password'        => 'required|string|min:6',
            'role'            => 'required|in:supervisor,admin,dosen',
            'laboratorium_id' => 'nullable|exists:laboratoriums,id',
        ]);

        $user = User::create([
            'no_induk' => $request->no_induk,
            'nama'     => $request->nama, // Menggunakan kolom 'nama' database
            'email'    => $request->email,
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
            'email'           => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'        => 'nullable|string|min:6',
            'role'            => 'required|in:supervisor,admin,dosen',
            'laboratorium_id' => 'nullable|exists:laboratoriums,id',
        ]);

        $data = [
            'no_induk' => $request->no_induk,
            'nama'     => $request->nama, // Menggunakan kolom 'nama' database
            'email'    => $request->email,
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

        return redirect()->route('users.index')->with('success', 'User berhasil deleted!');
    }
}