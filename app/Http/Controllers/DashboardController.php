<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Merchandise;

class DashboardController extends Controller
{
    public function index()
    {
        // total item terjual
        $totalSold = (int) Order::sum('jumlah');

        // total pendapatan: join orders -> merchandise dan sum(jumlah * harga)
        $totalRevenue = (int) Order::join('merchandise', 'orders.id_barang', '=', 'merchandise.id')
            ->selectRaw('COALESCE(SUM(orders.jumlah * merchandise.harga),0) as revenue')
            ->value('revenue');

        // total pesanan (record)
        $totalOrders = (int) Order::count();

        // jumlah pembeli unik
        $uniqueBuyers = (int) Order::distinct('no_hp')->count('no_hp');

        // best seller
        $bestSeller = Order::join('merchandise', 'orders.id_barang', '=', 'merchandise.id')
            ->selectRaw('merchandise.id, merchandise.nama_barang, COALESCE(SUM(orders.jumlah),0) as total_sold')
            ->groupBy('merchandise.id', 'merchandise.nama_barang')
            ->orderByDesc('total_sold')
            ->first();
        $bestSellerLabel = $bestSeller ? ($bestSeller->nama_barang . ' (' . $bestSeller->total_sold . ')') : '-';

        // monthly revenue (12 months)
        $start = now()->subMonths(11)->startOfMonth();
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $m = $start->copy()->addMonths($i);
            $months[] = $m->format('Y-m');
        }

        $monthlyQuery = Order::join('merchandise', 'orders.id_barang', '=', 'merchandise.id')
            ->selectRaw("DATE_FORMAT(orders.created_at, '%Y-%m') as ym, COALESCE(SUM(orders.jumlah * merchandise.harga),0) as revenue")
            ->where('orders.created_at', '>=', $start)
            ->groupBy('ym')
            ->pluck('revenue', 'ym')
            ->toArray();

        $monthlyRevenues = [];
        foreach ($months as $m) {
            $monthlyRevenues[] = isset($monthlyQuery[$m]) ? (int)$monthlyQuery[$m] : 0;
        }

        // product distribution (top 6)
        $productDistribution = Order::join('merchandise', 'orders.id_barang', '=', 'merchandise.id')
            ->selectRaw('merchandise.id, merchandise.nama_barang, COALESCE(SUM(orders.jumlah),0) as total_sold')
            ->groupBy('merchandise.id', 'merchandise.nama_barang')
            ->orderByDesc('total_sold')
            ->limit(6)
            ->get()
            ->map(function ($r) {
                return ['id' => $r->id, 'label' => $r->nama_barang, 'value' => (int)$r->total_sold];
            })
            ->toArray();

        // recent orders
        $recentOrders = Order::with('barang')->orderByDesc('created_at')->limit(5)->get();

        $stats = [
            'revenue' => $totalRevenue,
            'sold' => $totalSold,
            'orders' => $totalOrders,
            'buyers' => $uniqueBuyers,
            'best' => $bestSellerLabel,
            'months' => $months,
            'monthly_revenues' => $monthlyRevenues,
            'product_distribution' => $productDistribution,
            'recent_orders' => $recentOrders,
        ];

        return response()
            ->view('admin.dashboard', compact('stats'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
