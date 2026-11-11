<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in(array_column(Role::cases(), 'value'))],
            'status' => ['nullable', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $users = User::query()
            ->with(['wisatawan', 'pemilikHomestay'])
            ->when($filters['q'] ?? null, function ($query, $keyword) {
                $query->where(function ($builder) use ($keyword) {
                    $builder->where('username', 'like', '%'.$keyword.'%')
                        ->orWhere('email', 'like', '%'.$keyword.'%')
                        ->orWhereHas('wisatawan', fn ($relation) => $relation->where('nama_wisatawan', 'like', '%'.$keyword.'%'))
                        ->orWhereHas('pemilikHomestay', fn ($relation) => $relation->where('nama_pemilik', 'like', '%'.$keyword.'%'));
                });
            })
            ->when($filters['role'] ?? null, fn ($query, $role) => $query->where('role', $role))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status_aktif', $status === 'aktif'))
            ->orderBy('username')
            ->paginate(12)
            ->withQueryString();

        $allUsers = User::all();
        $stats = [
            'total' => $allUsers->count(),
            'wisatawan' => $allUsers->where('role', Role::Wisatawan)->count(),
            'pemilik' => $allUsers->where('role', Role::Pemilik)->count(),
            'nonaktif' => $allUsers->where('status_aktif', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'filters', 'stats'));
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->id_user === $user->id_user, 403, 'Anda tidak dapat menonaktifkan akun sendiri.');

        $user->update(['status_aktif' => ! $user->status_aktif]);

        return back()->with(
            'success',
            $user->status_aktif
                ? 'Akun '.$user->username.' berhasil diaktifkan kembali.'
                : 'Akun '.$user->username.' berhasil dinonaktifkan.'
        );
    }
}
