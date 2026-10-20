<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\RolePermission;
use App\Support\AdminPermissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HakAksesController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeSuperAdmin();

        $roles = $this->manageableRoles();
        $selected = collect($roles)->first(
            fn (UserRole $role) => $role->value === $request->query('role')
        ) ?? $roles[0];

        $permission = RolePermission::where('role', $selected->value)->first();

        return view('admin.hak_akses.index', [
            'roles' => $roles,
            'selected' => $selected,
            'allowed' => $permission?->permissions,
            'configured' => $permission !== null,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'role' => ['required', 'string', Rule::in($this->roleValues())],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(AdminPermissions::allKeys())],
        ]);

        $permissions = array_values($validated['permissions'] ?? []);

        RolePermission::updateOrCreate(
            ['role' => $validated['role']],
            ['permissions' => $permissions],
        );

        ActivityLog::log(
            'updated',
            'hak_akses',
            "Mengatur hak akses role {$validated['role']}: ".count($permissions).' menu diizinkan',
        );

        return redirect()
            ->route('admin.hak-akses.index', ['role' => $validated['role']])
            ->with('success', 'Hak akses role berhasil disimpan.');
    }

    /**
     * @return array<int, UserRole>
     */
    private function manageableRoles(): array
    {
        return collect(UserRole::cases())
            ->reject(fn (UserRole $role) => $role === UserRole::SuperAdmin)
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function roleValues(): array
    {
        return array_map(
            fn (UserRole $role) => $role->value,
            $this->manageableRoles(),
        );
    }

    private function authorizeSuperAdmin(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403, 'Hanya Super Admin yang dapat mengatur hak akses.');
    }
}
