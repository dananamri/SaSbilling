@extends('layouts.admin')
@section('title', 'Bandwidth Monitoring')
@section('header', 'Live Bandwidth Monitoring')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b gap-3">
        <div>
            <h3 class="font-semibold text-gray-800">Traffic Real-Time Interface</h3>
            <p class="text-xs text-gray-500">Pantau kecepatan Download (RX) dan Upload (TX) jaringan.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-block w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
            <span class="text-xs font-medium text-gray-600">Live Polling (3 detik)</span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-6">
        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
            <p class="text-xs text-blue-600 uppercase font-semibold">Download (RX)</p>
            <p class="text-3xl font-extrabold text-blue-900 mt-1" id="rx-val">-- Mbps</p>
        </div>
        <div class="bg-purple-50 border border-purple-200 p-4 rounded-lg">
            <p class="text-xs text-purple-600 uppercase font-semibold">Upload (TX)</p>
            <p class="text-3xl font-extrabold text-purple-900 mt-1" id="tx-val">-- Mbps</p>
        </div>
    </div>

    <div class="border rounded-lg p-4 bg-gray-50 text-center">
        <p class="text-sm text-gray-500">Live interface monitor aktif untuk router terhubung.</p>
        <p class="text-xs text-gray-400 mt-1">Status router: OK | Target: ether1-WAN</p>
    </div>
</div>

<script>
    let routerId = {{ $routers->first()?->id ?? 0 }};
    if (routerId > 0) {
        setInterval(() => {
            fetch(`/admin/bandwidth/${routerId}/poll`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json())
            .then(d => {
                document.getElementById('rx-val').innerText = d.rx_mbps + ' Mbps';
                document.getElementById('tx-val').innerText = d.tx_mbps + ' Mbps';
            })
            .catch(() => {});
        }, 3000);
    }
</script>
@endsection
