@extends('layouts.admin')

@section('content')
<main class="p-6 md:p-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
        <div class="text-sm text-gray-500">Ringkasan penjualan & performa</div>
    </div>

    {{-- Statistic cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="p-3 rounded-md bg-yellow-50 text-yellow-600">
                <!-- icon -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3-1.567 3-3.5S13.657 1 12 1 9 2.567 9 4.5 10.343 8 12 8zM6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"></path></svg>
            </div>
            <div class="flex-1">
                <div class="text-xs text-gray-500">Jumlah Pendapatan</div>
                <div class="text-lg font-semibold text-gray-800 mt-1">{{ 'Rp ' . number_format($stats['revenue'] ?? 0,0,',','.') }}</div>
                <div class="text-xs text-green-600 mt-1">+{{ $stats['monthly_revenues'][count($stats['monthly_revenues'] ?? [])-1] ?? 0 | 0 }} dari bulan lalu</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="p-3 rounded-md bg-indigo-50 text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
            </div>
            <div class="flex-1">
                <div class="text-xs text-gray-500">Jumlah Terjual (item)</div>
                <div class="text-lg font-semibold text-gray-800 mt-1">{{ $stats['sold'] ?? 0 }}</div>
                <div class="text-xs text-gray-500 mt-1">Total item terjual</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="p-3 rounded-md bg-green-50 text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l3 8 4-16 3 8h4"/></svg>
            </div>
            <div class="flex-1">
                <div class="text-xs text-gray-500">Jumlah Pembeli (unik)</div>
                <div class="text-lg font-semibold text-gray-800 mt-1">{{ $stats['buyers'] ?? 0 }}</div>
                <div class="text-xs text-gray-500 mt-1">Berdasarkan nomor HP</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="p-3 rounded-md bg-red-50 text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3-1.567 3-3.5S13.657 1 12 1 9 2.567 9 4.5 10.343 8 12 8zM6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/></svg>
            </div>
            <div class="flex-1">
                <div class="text-xs text-gray-500">Total Pesanan</div>
                <div class="text-lg font-semibold text-gray-800 mt-1">{{ $stats['orders'] ?? 0 }}</div>
                <div class="text-xs text-gray-500 mt-1">Jumlah record order</div>
            </div>
        </div>
    </div>

    {{-- Main area: charts + recent orders --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Pendapatan Per Bulan</h3>
                <div class="text-sm text-gray-500">12 bulan terakhir</div>
            </div>
            <div class="w-full h-64"> {{-- ubah h-64 ke tinggi yang kamu suka --}}
                <canvas id="revenueChart" class="w-full h-full"></canvas>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                @foreach(($stats['product_distribution'] ?? []) as $p)
                    <div class="p-3 bg-gray-50 rounded">
                        <div class="text-xs text-gray-500 truncate">{{ $p['label'] }}</div>
                        <div class="text-sm font-semibold text-gray-800">{{ $p['value'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Produk Teratas</h3>
                <a href="{{ route('admin.merch.index') }}" class="text-sm text-indigo-600">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse($stats['product_distribution'] ?? [] as $p)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center text-sm text-gray-600">
                            {{ strtoupper(substr($p['label'],0,1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-800 truncate">{{ $p['label'] }}</div>
                            <div class="text-xs text-gray-500">{{ $p['value'] }} terjual</div>
                        </div>
                        <div class="text-sm font-semibold text-gray-700">{{ $p['value'] }}</div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">-</div>
                @endforelse
            </div>

            <hr class="my-4">

            <h4 class="text-sm font-semibold text-gray-700 mb-2">Recent Orders</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs text-gray-500 text-left">
                        <tr>
                            <th class="pb-2">#</th>
                            <th class="pb-2">Produk</th>
                            <th class="pb-2">Pemesan</th>
                            <th class="pb-2">Jumlah</th>
                            <th class="pb-2">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse($stats['recent_orders'] ?? [] as $i => $o)
                            <tr class="border-t">
                                <td class="py-2">{{ $i+1 }}</td>
                                <td class="py-2">{{ $o->barang->nama_barang ?? '-' }}</td>
                                <td class="py-2">{{ $o->nama_pemesan ?? $o->no_hp }}</td>
                                <td class="py-2">{{ $o->jumlah }}</td>
                                <td class="py-2">Rp {{ number_format( ($o->jumlah * ($o->barang->harga ?? 0)),0,',','.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-sm text-gray-500">Belum ada order.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
    const months = {!! json_encode($stats['months'] ?? []) !!};
    const revenueData = {!! json_encode($stats['monthly_revenues'] ?? []) !!};

    const monthLabels = months.map(m => {
        const [y, mo] = m.split('-');
        const date = new Date(y, parseInt(mo,10)-1, 1);
        return date.toLocaleString('default', { month: 'short', year: 'numeric' });
    });

    // revenue line chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenueData,
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245,158,11,0.08)',
                fill: true,
                tension: 0.35,
                pointRadius: 3,
                pointBackgroundColor: '#f97316'
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + Number(context.parsed.y).toLocaleString();
                        }
                    }
                }
            }
        }
    });
})();
</script>

@endsection