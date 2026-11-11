@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Kelola Pengguna Platform')
@section('page_description', 'Pantau akun wisatawan, pengelola homestay desa wisata, dan status keaktifan hak akses pengguna.')

@section('dashboard')
    <!-- KPI Summary Bar -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-6">
        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-stone-500">
                <span class="text-xs font-bold uppercase tracking-wider">Total Akun</span>
                <span class="material-symbols-outlined text-stone-400 text-lg">manage_accounts</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-stone-900">{{ $stats['total'] ?? $users->total() }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Seluruh entitas terdaftar</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-sky-600">
                <span class="text-xs font-bold uppercase tracking-wider">Wisatawan</span>
                <span class="material-symbols-outlined text-sky-500 text-lg">travel_explore</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-sky-800">{{ $stats['wisatawan'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Tamu penikmat liburan</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-[#13391b]">
                <span class="text-xs font-bold uppercase tracking-wider">Tuan Rumah</span>
                <span class="material-symbols-outlined text-emerald-600 text-lg">cottage</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-[#13391b]">{{ $stats['pemilik'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Pengelola homestay desa</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-rose-600">
                <span class="text-xs font-bold uppercase tracking-wider">Akun Nonaktif</span>
                <span class="material-symbols-outlined text-rose-500 text-lg">block</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-rose-800">{{ $stats['nonaktif'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Akses dibekukan</p>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="rounded-2xl border border-stone-200 bg-white p-4 sm:p-5 shadow-xs mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1.4fr_0.8fr_0.8fr_auto] lg:items-end">
            <div>
                <label for="q" class="block text-xs font-bold text-stone-700 mb-1">Cari Pengguna</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-stone-400 text-lg">search</span>
                    <input
                        id="q"
                        type="text"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="Username, email, atau nama lengkap..."
                        class="w-full text-xs sm:text-sm pl-9 pr-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none"
                    >
                </div>
            </div>

            <div>
                <label for="role" class="block text-xs font-bold text-stone-700 mb-1">Peran Akun</label>
                <select
                    id="role"
                    name="role"
                    class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none bg-white"
                >
                    <option value="">Semua Peran</option>
                    @foreach (['admin' => 'Administrator Pokdarwis', 'pemilik' => 'Pemilik Homestay', 'wisatawan' => 'Wisatawan'] as $val => $lbl)
                        <option value="{{ $val }}" @selected(($filters['role'] ?? '') === $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-stone-700 mb-1">Status Keaktifan</label>
                <select
                    id="status"
                    name="status"
                    class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none bg-white"
                >
                    <option value="">Semua Status</option>
                    <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(($filters['status'] ?? '') === 'nonaktif')>Nonaktif (Dibekukan)</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
                >
                    <span class="material-symbols-outlined text-sm">filter_alt</span>
                    <span>Terapkan</span>
                </button>
                @if (!empty($filters['q']) || !empty($filters['role']) || !empty($filters['status']))
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 text-xs font-semibold transition-all"
                        title="Reset Filter"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Pengguna -->
    <div class="rounded-3xl border border-stone-200 bg-white shadow-xs overflow-hidden">
        @if ($users->isEmpty())
            <div class="py-12 px-4 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-stone-100 text-stone-400 mb-3">
                    <span class="material-symbols-outlined text-3xl">person_off</span>
                </div>
                <h3 class="text-base font-bold text-stone-900">Pengguna Tidak Ditemukan</h3>
                <p class="mt-1 text-xs text-stone-500 max-w-sm mx-auto">
                    Tidak ada akun yang sesuai dengan kata kunci pencarian atau filter yang Anda pilih.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[760px]">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50/70 text-stone-500 font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-3.5 px-5">Profil Pengguna</th>
                            <th class="py-3.5 px-5">Peran</th>
                            <th class="py-3.5 px-5">Status</th>
                            <th class="py-3.5 px-5 text-right">Tindakan Keaktifan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-stone-50/50 transition-colors">
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $user->role->value === 'admin' ? 'bg-purple-100 text-purple-900' : ($user->role->value === 'pemilik' ? 'bg-amber-100 text-amber-900' : 'bg-sky-100 text-sky-900') }} font-extrabold text-xs">
                                            {{ strtoupper(substr($user->displayName(), 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-extrabold text-stone-900 text-sm">{{ $user->displayName() }}</p>
                                            <p class="text-[11px] text-stone-500">
                                                {{ '@'.$user->username }} &bull; {{ $user->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-5">
                                    @php
                                        $roleStyles = [
                                            'admin' => 'bg-purple-50 text-purple-800 border-purple-200',
                                            'pemilik' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            'wisatawan' => 'bg-sky-50 text-sky-800 border-sky-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $roleStyles[$user->role->value] ?? 'bg-stone-100 text-stone-700' }}">
                                        {{ $user->role->label() }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-5">
                                    @if ($user->status_aktif)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-800 border border-red-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            <span>Nonaktif</span>
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-5 text-right">
                                    @if ($user->id_user === auth()->id())
                                        <span class="text-xs font-semibold text-stone-400 italic">Akun Anda Sendiri</span>
                                    @elseif ($user->status_aktif)
                                        <x-confirm-button
                                            :action="route('admin.users.status', $user)"
                                            method="PATCH"
                                            label="Nonaktifkan"
                                            title="Nonaktifkan akun {{ $user->username }}?"
                                            message="Pengguna ini tidak akan dapat login ke dalam sistem hingga akun diaktifkan kembali."
                                            confirm-label="Ya, nonaktifkan"
                                        />
                                    @else
                                        <x-confirm-button
                                            :action="route('admin.users.status', $user)"
                                            method="PATCH"
                                            label="Aktifkan Akun"
                                            title="Aktifkan kembali akun {{ $user->username }}?"
                                            message="Pengguna akan dapat langsung login dan menggunakan layanan HomeStay Go."
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

            <div class="p-4 border-t border-stone-100">
                <x-pagination :paginator="$users" />
            </div>
        @endif
    </div>
@endsection
