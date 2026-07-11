<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%')
                  ->orWhere('role', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(15);
        
        // Get active session user IDs to determine online status
        $onlineUserIds = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', time() - 900) // 15 mins
            ->pluck('user_id')
            ->toArray();

        return view('admin.users.index', compact('users', 'onlineUserIds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = Guru::whereNull('user_id')->get();
        $siswas = Siswa::whereNull('user_id')->get();
        return view('admin.users.create', compact('gurus', 'siswas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'role' => 'required|in:admin,guru,siswa,kepsek',
            'password' => 'required|string|min:6',
        ]);

        $email = str_contains($request->username, '@') ? $request->username : $request->username . '@erapor.local';

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        if ($request->role === 'guru' && $request->guru_id) {
            Guru::where('id', $request->guru_id)->update(['user_id' => $user->id]);
        } elseif ($request->role === 'siswa' && $request->siswa_id) {
            Siswa::where('id', $request->siswa_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Generate all users automatically
     */
    public function generateAll()
    {
        $gurus = Guru::whereNull('user_id')->get();
        $siswas = Siswa::whereNull('user_id')->get();
        $count = 0;

        foreach ($gurus as $guru) {
            $username = $guru->nip ?: strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $guru->nama_lengkap));
            $checkUser = User::where('username', $username)->first();
            if ($checkUser) {
                $username = $username . rand(10, 99);
            }

            $user = User::create([
                'name' => $guru->nama_lengkap,
                'username' => $username,
                'email' => $username . '@erapor.local',
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'is_active' => true,
            ]);

            $guru->update(['user_id' => $user->id]);
            $count++;
        }

        foreach ($siswas as $siswa) {
            $username = $siswa->nisn;
            $checkUser = User::where('username', $username)->first();
            if ($checkUser) {
                $username = $username . rand(10, 99);
            }

            $user = User::create([
                'name' => $siswa->nama_lengkap,
                'username' => $username,
                'email' => $username . '@erapor.local',
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'is_active' => true,
            ]);

            $siswa->update(['user_id' => $user->id]);
            $count++;
        }

        return redirect()->back()->with('success', $count . ' Akun Pengguna berhasil di-generate secara otomatis.');
    }

    /**
     * Reset User Password
     */
    public function resetPassword(User $user)
    {
        $newPassword = 'password123';
        $user->password = Hash::make($newPassword);
        $user->save();

        return redirect()->back()->with('success', 'Password untuk ' . $user->name . ' telah di-reset menjadi: ' . $newPassword);
    }

    /**
     * Toggle Active Status
     */
    public function toggleActive(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', 'Pengguna ' . $user->name . ' berhasil ' . $status . '.');
    }

    /**
     * Reset Login (Delete Sessions)
     */
    public function resetLogin(User $user)
    {
        DB::table('sessions')->where('user_id', $user->id)->delete();
        return redirect()->back()->with('success', 'Status login pengguna ' . $user->name . ' telah di-reset menjadi offline.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Hapus data profil terkait (Siswa/Guru)
        Guru::where('user_id', $user->id)->delete();
        Siswa::where('user_id', $user->id)->delete();

        $user->delete();
        return redirect()->back()->with('success', 'Akun pengguna beserta data profil aslinya berhasil dihapus secara permanen.');
    }
}
