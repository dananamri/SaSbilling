@extends('layouts.admin')
@section('title', 'Hak Akses')
@section('header', 'Hak Akses Pengguna')

@section('content')
<div x-data="{
        checkAll(value) {
            document.querySelectorAll('.perm-check').forEach((el) => { el.checked = value; });
        },
    }">

    <div class="bg-white rounded-lg shadow p-6 mb-6 max-w-5xl">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h3 class="font-semibold text-gray-800">Atur Menu per Role</h3>
                <p class="text-xs text-gray-500 mt-1">Centang menu yang boleh diakses oleh role terpilih. Menu yang tidak dicentang akan disembunyikan dari sidebar dan diblokir (403) jika diakses paksa lewat URL.</p>
            </div>
            <div class="flex items-center gap-2 text-xs shrink-0">
                <span class="px-2 py-1 rounded {{ $configured ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $configured ? 'Sudah disetel' : 'Belum disetel — semua menu diizinkan' }}
                </span>
            </div>
        </div>

        <div class="mt-4 p-3 bg-blue-50 border border-blue-100 rounded text-xs text-blue-700">
            <strong>Super Admin</strong> selalu memiliki akses penuh ke semua menu dan tidak dapat dibatasi.
            Role yang belum pernah disetel otomatis diizinkan mengakses semua menu sampai Anda menyimpan pengaturan pertama kali.
        </div>
    </div>

    <div class="bg-white rounded-lg shadow max-w-5xl">
        <div class="px-6 pt-6">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider mr-1">Role:</span>
                @foreach($roles as $role)
                    <a href="{{ route('admin.hak-akses.index', ['role' => $role->value]) }}"
                       class="px-3 py-1.5 rounded-full text-sm {{ $role->value === $selected->value
                           ? 'bg-blue-600 text-white'
                           : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $role->label() }}
                    </a>
                @endforeach
            </div>

            <div class="flex flex-wrap gap-2 mt-4 pb-4 border-b">
                <button type="button" @click="checkAll(true)"
                        class="px-3 py-1.5 text-xs border rounded hover:bg-gray-50 text-gray-600">Centang Semua</button>
                <button type="button" @click="checkAll(false)"
                        class="px-3 py-1.5 text-xs border rounded hover:bg-gray-50 text-gray-600">Kosongkan Semua</button>
            </div>
        </div>

        @if($errors->any())
            <div class="mx-6 mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.hak-akses.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="role" value="{{ $selected->value }}">

            <div class="p-6 space-y-6">
                @foreach(\App\Support\AdminPermissions::SECTIONS as $section => $menus)
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-700">{{ $section }}</h4>
                            <span class="text-[11px] text-gray-400">{{ count($menus) }} menu</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach($menus as $key => $label)
                                <label class="flex items-center gap-3 px-3 py-2.5 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                           class="perm-check w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           {{ $allowed === null || in_array($key, $allowed, true) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 border-t bg-gray-50 rounded-b-lg flex items-center justify-end gap-3">
                <span class="text-xs text-gray-400 mr-auto">Menu <strong>Dashboard</strong> selalu terbuka untuk semua role.</span>
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm border rounded text-gray-600 hover:bg-gray-100">Batal</a>
                <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Hak Akses</button>
            </div>
        </form>
    </div>
</div>
@endsection
