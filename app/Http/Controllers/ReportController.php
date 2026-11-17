<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Laporan Rekap Bulanan
     */
    public function monthly(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year  = (int) $request->get('year', now()->year);
        
        if ($month < 1 || $month > 12) $month = now()->month;
        if ($year < 2000 || $year > 2100) $year = now()->year;

        // Query dasar
        $query = LaundryOrder::whereMonth('created_at', $month)
                             ->whereYear('created_at', $year);

        // Summary
        $totalTransactions = (clone $query)->count();
        $totalRevenue      = (clone $query)->sum('total_price') ?: 0;
        $avgPerOrder       = $totalTransactions > 0 
                                ? $totalRevenue / $totalTransactions 
                                : 0;

        // Breakdown per Layanan
        $byService = LaundryOrder::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->selectRaw('service_type, COUNT(*) as count, SUM(total_price) as revenue')
            ->groupBy('service_type')
            ->get();

        // Breakdown per Status
        $byStatus = LaundryOrder::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Top 5 Customer
        $topCustomers = LaundryOrder::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->whereNotNull('customer_id')
            ->selectRaw('customer_id, COUNT(*) as total_transactions, SUM(total_price) as total_spent')
            ->groupBy('customer_id')
            ->orderByDesc('total_spent')
            ->with('customer')
            ->take(5)
            ->get();

        // Dropdown Bulan
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // ✅ FIX: Available years tanpa YEAR() — ambil langsung dari collection
        $availableYears = LaundryOrder::select('created_at')
            ->orderByDesc('created_at')
            ->pluck('created_at')
            ->map(fn($date) => $date->year)
            ->unique()
            ->values()
            ->toArray();

        // Pastikan tahun sekarang & sebelumnya ada di list
        $currentYear = now()->year;
        for ($i = 0; $i < 3; $i++) {
            $y = $currentYear - $i;
            if (!in_array($y, $availableYears)) {
                $availableYears[] = $y;
            }
        }
        rsort($availableYears);

        return view('reports.monthly', compact(
            'month', 'year', 'months', 'availableYears',
            'totalTransactions', 'totalRevenue', 'avgPerOrder',
            'byService', 'byStatus', 'topCustomers'
        ));
    }

    /**
     * Export Laporan Bulanan ke PDF
     */
    public function exportMonthlyPdf(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year  = (int) $request->get('year', now()->year);
        
        if ($month < 1 || $month > 12) $month = now()->month;
        if ($year < 2000 || $year > 2100) $year = now()->year;

        $totalTransactions = LaundryOrder::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
        
        $totalRevenue = LaundryOrder::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('total_price') ?: 0;
        
        $avgPerOrder = $totalTransactions > 0 
                        ? $totalRevenue / $totalTransactions 
                        : 0;

        $byService = LaundryOrder::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->selectRaw('service_type, COUNT(*) as count, SUM(total_price) as revenue')
            ->groupBy('service_type')
            ->get();

        $byStatus = LaundryOrder::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $topCustomers = LaundryOrder::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->whereNotNull('customer_id')
            ->selectRaw('customer_id, COUNT(*) as total_transactions, SUM(total_price) as total_spent')
            ->groupBy('customer_id')
            ->orderByDesc('total_spent')
            ->with('customer')
            ->take(5)
            ->get();

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $data = compact(
            'month', 'year', 'months',
            'totalTransactions', 'totalRevenue', 'avgPerOrder',
            'byService', 'byStatus', 'topCustomers'
        );

        $pdf = Pdf::loadView('reports.monthly-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Laporan-Bulanan-' . $months[$month] . '-' . $year . '.pdf';
        return $pdf->download($filename);
    }
}