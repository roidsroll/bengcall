<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $branches = Branch::query()
            ->with('manager')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner
                        ->where('branch_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.branches.index', [
            'branches' => $branches,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('admin.branches.create', [
            'managers' => User::query()->orderBy('name')->get(['id', 'name', 'email']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'branch_code' => ['required', 'string', 'max:255', 'unique:branches,branch_code'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:2000'],
            'phone' => ['nullable', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['nullable', 'numeric', 'min:-180', 'max:180'],
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i'],
            'status' => ['required', 'in:active,inactive,maintenance'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        Branch::create([
            'branch_code' => $validated['branch_code'],
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'opening_time' => $validated['opening_time'],
            'closing_time' => $validated['closing_time'],
            'status' => $validated['status'],
            'manager_id' => ! empty($validated['manager_id']) ? (int) $validated['manager_id'] : null,
        ]);

        return redirect()->route('admin.branches.index')->with('status', 'Branch berhasil dibuat.');
    }

    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', [
            'branch' => $branch->load('manager'),
            'managers' => User::query()->orderBy('name')->get(['id', 'name', 'email']),
        ]);
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validate([
            'branch_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('branches', 'branch_code')->ignore($branch->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:2000'],
            'phone' => ['nullable', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['nullable', 'numeric', 'min:-180', 'max:180'],
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i'],
            'status' => ['required', 'in:active,inactive,maintenance'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $branch->update([
            'branch_code' => $validated['branch_code'],
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'opening_time' => $validated['opening_time'],
            'closing_time' => $validated['closing_time'],
            'status' => $validated['status'],
            'manager_id' => ! empty($validated['manager_id']) ? (int) $validated['manager_id'] : null,
        ]);

        return redirect()->route('admin.branches.index')->with('status', 'Branch berhasil diupdate.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();

        return redirect()->route('admin.branches.index')->with('status', 'Branch berhasil dihapus.');
    }
}

