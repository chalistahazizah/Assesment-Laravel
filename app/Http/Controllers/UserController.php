<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|exists:roles,name',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'manager_id' => $request->manager_id,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('user-management.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function index(): View|Application|Factory
    {
        $user = User::find(Auth::id());

        if ($user->hasRole('admin')) {
            $admins = User::role('admin')->get();
            $managers = User::role('manager')->get();
            $users = User::role('user')->get();
        } elseif ($user->hasRole('manager')) {
            $admins = collect();
            $managers = collect();
            $users = User::where('manager_id', $user->id)->get();
        } else {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $roles = Role::all();
        $allManagers = User::has('manager')->get();

        return view('user-management.user-management-dashboard', compact('admins', 'managers', 'users', 'roles', 'allManagers'));
    }

    public function destroy($id): RedirectResponse
    {
        $userToDelete = User::findOrFail($id);

        $currentUser = Auth::user();

        if (!($currentUser instanceof User) || !$currentUser->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus user.');
        }

        if ($currentUser->id === $userToDelete->id) {
            return redirect()->route('user-management.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userToDelete->delete();

        return redirect()->route('user-management.index')->with('success', 'User berhasil dihapus.');
    }

    public function edit($id): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles->first()->name ?? '',
            'manager_id' => $user->manager_id
        ]);
    }



    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|exists:roles,name',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'manager_id' => $request->manager_id,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return response()->json([
            'message' => 'User berhasil diperbarui!',
            'user' => $user
        ]);
    }


}
