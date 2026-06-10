<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\NotificationHelper;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('role', 'like', '%' . $search . '%');
                });
            })
            ->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,manajer,manager,administrator'
        ], [
            'email.unique' => 'Email sudah digunakan.',
            'role.in' => 'Role tidak valid.'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::normalizeRoleValue($request->role)
        ]);

        NotificationHelper::send(
            'user_created',
            'User Baru Ditambahkan',
            'Akun user baru dengan nama ' .
                $request->name .
                ' (role: ' . $request->role .
                ') berhasil dibuat.',
            'user',
            'success'
        );

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manajer,manager,administrator'
        ], [
            'email.unique' => 'Email sudah digunakan.',
            'role.in' => 'Role tidak valid.'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => User::normalizeRoleValue($request->role)
        ]);

        NotificationHelper::send(
            'user_updated',
            'Data User Diperbarui',
            'Data user telah diperbarui.',
            'edit',
            'primary'
        );

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        NotificationHelper::send(
            'user_deleted',
            'User Dihapus',
            'Akun user ' . $user->name .
                ' (role: ' . $user->role .
                ') telah dihapus dari sistem.',
            'trash',
            'danger'
        );
        return back()->with('success', 'User dihapus');
    }
}