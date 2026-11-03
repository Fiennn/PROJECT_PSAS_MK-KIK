@extends('layouts.dashboard')

@section('title', 'Pengguna')
@section('page_title', 'Kelola Pengguna')
@section('page_description', 'Cari pengguna, tinjau peran, dan aktifkan atau nonaktifkan akun.')

@section('dashboard')
    <x-card>
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-[1.4fr_0.7fr_0.7fr_auto] lg:items-end">
            <div>
                <label for="q" class="label">Cari pengguna</label>
                <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Username, email, atau nama..." class="field">
            </div>
            <x-form.select name="role" label="Peran">
                <option value="">Semua peran</option>
                @foreach (['admin' => 'Administrator', 'pemilik' => 'Pemilik Homestay', 'wisatawan' => 'Wisatawan'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['role'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </x-form.select>
            <x-form.select name="status" label="Status">
                <option value="">Semua status</option>
                <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Aktif</option>
                <option value="nonaktif" @selected(($filters['status'] ?? '') === 'nonaktif')>Nonaktif</option>
            </x-form.select>
            <div class="flex gap-2">
                <x-button type="submit">Terapkan</x-button>
                <x-button :href="route('admin.users.index')" variant="secondary">Reset</x-button>
            </div>
        </form>
    </x-card>

    <x-card padding="p-0" class="mt-6">
        @if ($users->isEmpty())
            <div class="p-5">
                <x-empty-state title="Pengguna tidak ditemukan" description="Coba kata kunci atau filter lain." />
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="bg-sand-50 text-xs uppercase tracking-wider text-stone-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Pengguna</th>
                            <th class="px-5 py-3 font-semibold">Peran</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 text-right font-semibold">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sand-100">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-forest-950">{{ $user->displayName() }}</p>
                                    <p class="mt-0.5 text-xs text-stone-400">{{ '@'.$user->username }} · {{ $user->email }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full bg-sand-100 px-3 py-1 text-xs font-semibold text-sand-800">{{ $user->role->label() }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($user->status_aktif)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-forest-50 px-3 py-1 text-xs font-semibold text-forest-800 ring-1 ring-inset ring-forest-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-forest-500" aria-hidden="true"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-700 ring-1 ring-inset ring-stone-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-stone-400" aria-hidden="true"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if ($user->id_user === auth()->id())
                                        <span class="text-xs text-stone-400">Akun Anda</span>
                                    @elseif ($user->status_aktif)
                                        <x-confirm-button
                                            :action="route('admin.users.status', $user)"
                                            method="PATCH"
                                            label="Nonaktifkan"
                                            title="Nonaktifkan akun ini?"
                                            message="Pengguna tidak akan bisa masuk sampai akun diaktifkan kembali."
                                            confirm-label="Ya, nonaktifkan"
                                        />
                                    @else
                                        <x-confirm-button
                                            :action="route('admin.users.status', $user)"
                                            method="PATCH"
                                            label="Aktifkan"
                                            title="Aktifkan akun ini?"
                                            message="Pengguna akan bisa masuk kembali ke HomeStay Go."
                                            confirm-label="Ya, aktifkan"
                                            variant="primary"
                                        />
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

    <x-pagination :paginator="$users" />
@endsection
