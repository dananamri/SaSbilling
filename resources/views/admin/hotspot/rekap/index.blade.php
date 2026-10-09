@extends('layouts.admin')
    @section('title', 'Rekap Hotspot')
    @section('header', 'Rekap Hotspot')

    @section('content')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs sm:text-sm text-gray-500">Total Profil</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ $totalProfiles }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs sm:text-sm text-gray-500">Total Voucher</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ $totalVouchers }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs sm:text-sm text-gray-500">Voucher Terpakai</p>
            <p class="text-xl sm:text-2xl font-bold text-orange-600">{{ $usedVouchers }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs sm:text-sm text-gray-500">Total Member</p>
            <p class="text-xl sm:text-2xl font-bold text-purple-600">{{ $totalMembers }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Voucher per Profil</h3>
            <div class="space-y-3">
                @foreach($vouchersByProfile as $item)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>{{ $item->profile_name }}</span>
                            <span>{{ $item->used_count }}/{{ $item->total_count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $item->total_count > 0 ? ($item->used_count / $item->total_count * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Member per Profil</h3>
            <div class="space-y-3">
                @foreach($membersByProfile as $item)
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <span class="text-sm">{{ $item->profile_name }}</span>
                        <span class="text-sm font-medium">{{ $item->member_count }} member</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Voucher Terpakai per Bulan</h3>
        <div class="space-y-2">
            @foreach($vouchersByMonth as $item)
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                    <span class="text-sm">{{ $item->month }}</span>
                    <span class="text-sm font-medium">{{ $item->count }} voucher</span>
                </div>
            @endforeach
        </div>
    </div>
    @endsection
