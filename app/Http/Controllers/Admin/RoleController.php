<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $roles = Role::query()
            ->withCount('users')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.roles.index', [
            'roles' => $roles,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Role::create([
            'name' => $validated['name'],
        ]);

        return redirect()->route('admin.roles.index')->with('status', 'Role berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', [
            'role' => $role,
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('admin.roles.index')->with('status', 'Role berhasil diupdate.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->loadCount('users');

        if ($role->users_count > 0) {
            return back()->with('status', 'Role tidak bisa dihapus karena masih dipakai user.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('status', 'Role berhasil dihapus.');
    }
}

