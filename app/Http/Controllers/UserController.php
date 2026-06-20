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
        $query = User::with('laboratoriums')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('no_induk', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

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
        $role = $request->input('role');

        // =====================================================================
        // LOGIKA DOSEN: Hanya butuh email. Password di-generate acak & dikirim via email.
        // =====================================================================
        if ($role === 'dosen') {
            $request->validate([
                'email' => [
                    'required',
                    'email',
                    'max:100',
                    'unique:users,email',
                ],
                'role' => 'required|in:supervisor,admin,dosen',
            ], [
                'email.required'  => 'Email dosen wajib diisi.',
                'email.email'     => 'Format email tidak valid.',
                'email.unique'    => 'Email ini sudah terdaftar di sistem.',
            ]);

            // Generate no_induk sementara unik (DSN + 8 digit acak)
            do {
                $tempNoInduk = 'DSN' . str_pad(rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            } while (\App\Models\User::where('no_induk', $tempNoInduk)->exists());

            // Generate password acak 10 karakter (campuran huruf & angka)
            $temporaryPassword = \Illuminate\Support\Str::random(10);

            // Buat user dosen dengan is_first_login = true
            $user = User::create([
                'no_induk'       => $tempNoInduk,
                'nama'           => null,
                'email'          => $request->email,
                'no_hp'          => null,
                'password'       => Hash::make($temporaryPassword),
                'role'           => 'dosen',
                'is_first_login' => true,
            ]);

            // Kirim email selamat datang dengan password sementara
            try {
                \Illuminate\Support\Facades\Mail::to($request->email)
                    ->send(new \App\Mail\DosenWelcomeMail($request->email, $temporaryPassword));
            } catch (\Exception $e) {
                // Jika gagal kirim email, tetap lanjut (log error saja)
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email ke dosen: ' . $e->getMessage());
            }

            return redirect()->route('users.index')
                ->with('success', 'Akun dosen berhasil dibuat! Email berisi password sementara telah dikirim ke ' . $request->email);
        }

        // =====================================================================
        // LOGIKA ADMIN: Input manual lengkap dengan semua field.
        // =====================================================================
        $request->validate([
            'no_induk'        => 'required|string|max:20|unique:users,no_induk',
            'nama'            => 'required|string|max:100',
            'no_hp'           => 'required|digits_between:10,15',
            'email'           => [
                'required',
                'email',
                'max:100',
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
            'no_hp'    => $request->no_hp,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        if ($request->role === 'admin' && $request->laboratorium_id) {
            Laboratorium::where('id', $request->laboratorium_id)->update(['user_id' => $user->no_induk]);
            
            // Otomatis tugaskan pengajuan yang sudah disetujui ke admin baru
            $pengajuans = \App\Models\Pengajuan::where('status_persetujuan', 'disetujui')
                ->whereNull('tugaskan_admin')
                ->get()
                ->filter(function($pengajuan) use ($request) {
                    $labIds = is_string($pengajuan->lab_ids) ? json_decode($pengajuan->lab_ids, true) : $pengajuan->lab_ids;
                    return in_array($request->laboratorium_id, $labIds ?? []);
                });

            foreach ($pengajuans as $pengajuan) {
                $pengajuan->update([
                    'tugaskan_admin'  => $user->no_induk,
                    'tgl_penugasan'   => now()->toDateString(),
                    'status_progress' => 'progress',
                ]);
            }
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }


    public function edit($no_induk)
    {
        $user = User::where('no_induk', $no_induk)->firstOrFail();
        
        $laboratoriums = Laboratorium::all();
        
        // DIUBAH: Menggunakan $user->no_induk untuk mencari ID laboratorium yang saat ini dipegang
        $currentLabId = Laboratorium::where('user_id', $user->no_induk)->first()?->id;

        return view('users.edit', compact('user', 'laboratoriums', 'currentLabId'));
    }

    public function update(Request $request, $no_induk)
    {
        $user = User::where('no_induk', $no_induk)->firstOrFail();

        // Email validation: dosen bebas domain, admin/supervisor harus @lab.com
        $emailRule = [
            'required',
            'email',
            'max:100',
            'unique:users,email,' . $user->no_induk . ',no_induk',
        ];
        if ($request->role !== 'dosen') {
            $emailRule[] = function ($attribute, $value, $fail) {
                if (!preg_match('/@lab\.com$/i', $value)) {
                    $fail('Email harus menggunakan domain @lab.com');
                }
            };
        }

        $request->validate([
            'no_induk'        => 'required|string|max:20|unique:users,no_induk,' . $user->no_induk . ',no_induk',
            'nama'            => 'required|string|max:100',
            'no_hp'           => 'required|digits_between:10,15',
            'email'           => $emailRule,
            'password'        => 'nullable|string|min:6|max:100',
            'role'            => 'required|in:supervisor,admin,dosen',
            'laboratorium_id' => 'nullable|exists:laboratoriums,id',
        ]);

        $data = [
            'no_induk' => $request->no_induk,
            'nama'     => $request->nama, 
            'email'    => $request->email,
            'no_hp'    => $request->no_hp, 
            'role'     => $request->role,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // DIUBAH: Menggunakan $user->no_induk menggantikan $user->id agar relasi sinkron saat proses simpan perubahan
        if ($request->role === 'admin') {
            Laboratorium::where('user_id', $user->no_induk)->update(['user_id' => null]);
            if ($request->laboratorium_id) {
                Laboratorium::where('id', $request->laboratorium_id)->update(['user_id' => $user->no_induk]);
                
                // Otomatis tugaskan pengajuan yang sudah disetujui ke admin baru
                $pengajuans = \App\Models\Pengajuan::where('status_persetujuan', 'disetujui')
                    ->whereNull('tugaskan_admin')
                    ->get()
                    ->filter(function($pengajuan) use ($request) {
                        $labIds = is_string($pengajuan->lab_ids) ? json_decode($pengajuan->lab_ids, true) : $pengajuan->lab_ids;
                        return in_array($request->laboratorium_id, $labIds ?? []);
                    });

                foreach ($pengajuans as $pengajuan) {
                    $pengajuan->update([
                        'tugaskan_admin'  => $user->no_induk,
                        'tgl_penugasan'   => now()->toDateString(),
                        'status_progress' => 'progress',
                    ]);
                }
            }
        } else {
            Laboratorium::where('user_id', $user->no_induk)->update(['user_id' => null]);
        }

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui!');
    }


    public function destroy($no_induk)
    {
        $user = User::where('no_induk', $no_induk)->firstOrFail();
        
        // DIUBAH: Menggunakan $user->no_induk untuk melepas kepemilikan laboratorium sebelum user dihapus
        Laboratorium::where('user_id', $user->no_induk)->update(['user_id' => null]);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil deleted!');
    }
}