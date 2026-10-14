@extends('layouts.admin')
@section('title', 'Peta Pelanggan')
@section('header', 'Peta Pelanggan & Jaringan ODP')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* Keep Leaflet below mobile sidebar (sidebar is z-[9999]) */
    .leaflet-pane { z-index: 400 !important; }
    .leaflet-top, .leaflet-bottom { z-index: 500 !important; }
    .leaflet-control { z-index: 500 !important; }
</style>
<div class="bg-white rounded-lg shadow p-3 sm:p-4 overflow-hidden">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 mb-4 border-b gap-3">
        <div class="min-w-0">
            <h3 class="font-semibold text-gray-800 text-sm sm:text-base">Visualisasi Geografis (GIS)</h3>
            <p class="text-xs text-gray-500">Titik lokasi rumah pelanggan dan box ODP jaringan fiber optik.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs shrink-0">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span> ODP</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span> Pelanggan Aktif</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span> Pelanggan Terisolir</span>
        </div>
    </div>

    <div class="relative z-0 isolate overflow-hidden rounded-lg border">
        <div id="map" class="w-full h-[62vh] min-h-[360px] max-h-[640px] sm:h-[520px] lg:h-[600px] rounded-lg"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([-6.200000, 106.816666], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        fetch('{{ route("admin.map.geojson") }}')
            .then(res => res.json())
            .then(data => {
                const bounds = [];
                data.features.forEach(feat => {
                    const [lng, lat] = feat.geometry.coordinates;
                    bounds.push([lat, lng]);

                    if (feat.properties.type === 'odp') {
                        L.circleMarker([lat, lng], { radius: 8, color: '#2563eb', fillColor: '#3b82f6', fillOpacity: 0.9 })
                            .bindPopup(`<b>${feat.properties.title}</b><br>Port: ${feat.properties.capacity}<br>Status: ${feat.properties.status}`)
                            .addTo(map);
                    } else {
                        const isIsolated = feat.properties.status === 'isolated';
                        L.circleMarker([lat, lng], { radius: 6, color: isIsolated ? '#dc2626' : '#16a34a', fillColor: isIsolated ? '#ef4444' : '#22c55e', fillOpacity: 0.85 })
                            .bindPopup(`<b>${feat.properties.name}</b><br>Paket: ${feat.properties.package}<br>HP: ${feat.properties.phone}`)
                            .addTo(map);
                    }
                });

                if (bounds.length > 0) {
                    map.fitBounds(bounds, { padding: [40, 40] });
                }
            })
            .catch(() => {});
    });
</script>
@endsection
