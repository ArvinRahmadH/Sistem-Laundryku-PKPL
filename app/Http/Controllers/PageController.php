<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PageController extends Controller
{
    public function downloadInvoice($id)
    {
        $order = LaundryOrder::findOrFail($id);
        
        $invoiceNumber = 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . '-' . $order->created_at->format('Ymd');
        
        $data = [
            'order' => $order,
            'invoiceNumber' => $invoiceNumber,
            'services' => [
                'kiloan' => 'Kiloan',
                'dry_cleaning' => 'Dry Cleaning',
                'antar_jemput' => 'Antar Jemput',
                'setrika_premium' => 'Setrika Premium'
            ],
            'statuses' => [
                'pending' => 'Pending',
                'in_progress' => 'In Progress',
                'done' => 'Done',
                'delivered' => 'Delivered'
            ]
        ];

        // Generate PDF
        $pdf = Pdf::loadView('orders.invoice-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        
        // Langsung download
        return $pdf->download('Invoice-' . $invoiceNumber . '.pdf');
    }

    /**
     * Download invoice sebagai HTML (untuk print/save as PDF via browser)
     */
    public function downloadInvoiceHtml($id)
    {
        $order = LaundryOrder::findOrFail($id);
        $invoiceNumber = 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . '-' . $order->created_at->format('Ymd');
        
        $data = [
            'order' => $order,
            'invoiceNumber' => $invoiceNumber,
            'services' => [
                'kiloan' => 'Kiloan',
                'dry_cleaning' => 'Dry Cleaning',
                'antar_jemput' => 'Antar Jemput',
                'setrika_premium' => 'Setrika Premium'
            ],
            'statuses' => [
                'pending' => 'Pending',
                'in_progress' => 'In Progress',
                'done' => 'Done',
                'delivered' => 'Delivered'
            ]
        ];

        return view('orders.invoice', $data);
    }
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function dashboard()
    {
        $stats = [
            'pending' => LaundryOrder::where('status', 'pending')->count(),
            'in_progress' => LaundryOrder::where('status', 'in_progress')->count(),
            'done' => LaundryOrder::where('status', 'done')->count(),
            'total' => LaundryOrder::count(),
        ];

        $orders = LaundryOrder::latest()->take(10)->get();

        return view('orders.index', compact('stats', 'orders'));
    }

    public function orderList(Request $request)
    {
        $query = LaundryOrder::query();

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('customer_name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orderBy = $request->get('orderBy', 'created_at');
        $sortBy = $request->get('sortBy', 'desc');

        if (!in_array($orderBy, ['id', 'customer_name', 'price', 'created_at'])) {
            $orderBy = 'created_at';
        }

        $orders = $query->orderBy($orderBy, $sortBy)
                       ->paginate($request->get('limit', 10));

        return view('orders.list', compact('orders'));
    }

    public function createOrder()
    {
        return view('orders.create');
    }

    public function showOrder($id)
    {
        $order = LaundryOrder::findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function editOrder($id)
    {
        $order = LaundryOrder::findOrFail($id);
        return view('orders.edit', compact('order'));
    }

    public function showFile($filename)
{
    $path = storage_path('app/public/laundry/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
}
}
