<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Notification;

class ProfileController extends Controller
{
    // tampilkan halaman profil ringkas
    public function index()
    {
        $user = Auth::user();
        $activities = Notification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->take(4)
            ->get()
            ->map(function (Notification $notification) {
                return [
                    'label' => $notification->title ?: $notification->message,
                    'time' => $notification->created_at
                        ? $notification->created_at->format('d M Y, H:i')
                        : '-',
                ];
            })
            ->values()
            ->all();

        return view('profile.index', [
            'user' => $user,
            'activities' => $activities,
        ]);
    }

    // tampilkan halaman edit profile
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    // tampilkan halaman ganti password
    public function password()
    {
        return view('profile.password', [
            'user' => Auth::user()
        ]);
    }

    // update profile (nama & email)
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
        ])->save();

        return back()->with('success', 'Profile berhasil diupdate');
    }

    // CHANGE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        // update password
        $user->fill([
            'password' => Hash::make($request->password),
        ])->save();

        return back()->with('success_password', 'Password berhasil diubah');
    }
}