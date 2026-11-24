<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class LaundryOrderController extends Controller
{
    /**
     * Harga tetap per kg
     */
    private const PRICE_PER_KG = 10000;

    public function index(Request $request)
    {
        $limit = intval($request->get('limit', 10));
        $search = $request->get('search');
        $orderBy = $request->get('orderBy', 'created_at');
        $sortBy = strtolower($request->get('sortBy', 'desc')) === 'asc' ? 'asc' : 'desc';

        $query = LaundryOrder::with('customer');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if (!in_array($orderBy, ['id', 'customer_name', 'service_type', 'status', 'price', 'weight', 'created_at', 'updated_at'])) {
            $orderBy = 'created_at';
        }

        $paginator = $query->orderBy($orderBy, $sortBy)->paginate($limit)->appends($request->query());

        return response()->json($paginator, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'    => 'nullable|exists:customers,id',
            'customer_name'  => 'required|string|max:255',
            'phone'          => 'nullable|string|max:10',
            'address'        => 'nullable|string',
            'service_type'   => ['required', Rule::in(['kiloan','dry_cleaning','antar_jemput','setrika_premium'])],
            'weight'         => 'required|numeric|min:0.1|max:200',
            'status'         => ['nullable', Rule::in(['pending','in_progress','done','delivered'])],
            'price'          => 'nullable|integer|min:0',
            'notes'          => 'nullable|string',
            'file'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1000',
        ]);

        $data['weight'] ??= 0;
        $data['status'] ??= 'pending';
        $data['price'] = self::PRICE_PER_KG; // ✅ Paksa harga Rp 10.000/kg

        // Hitung diskon
        $this->calculateDiscount($data);
        // Upload file
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('laundry', 'public');
        }

        $order = LaundryOrder::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Order created',
                'data' => $order
            ], 201);
        }

        return redirect('/orders')->with('success', 'Order berhasil dibuat!');
    }

    public function show($id)
    {
        $order = LaundryOrder::with('customer')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order, 200);
    }

    public function update(Request $request, $id)
    {
        $order = LaundryOrder::find($id);
        if (!$order) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Order not found'], 404);
            }
            return back()->with('error', 'Order not found');
        }

        $data = $request->validate([
            'customer_id'    => 'nullable|exists:customers,id',
            'customer_name'  => 'sometimes|string|max:255',
            'phone'          => 'sometimes|nullable|string|max:10',
            'address'        => 'sometimes|nullable|string',
            'service_type'   => ['sometimes', Rule::in(['kiloan','dry_cleaning','antar_jemput','setrika_premium'])],
            'weight'         => 'required|numeric|min:0.1|max:200',
            'status'         => ['sometimes', Rule::in(['pending','in_progress','done','delivered'])],
            'price'          => 'sometimes|integer|min:0',
            'notes'          => 'sometimes|nullable|string',
            'file'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1000',
        ]);

        // ✅ Paksa harga Rp 10.000/kg
        $data['price'] = self::PRICE_PER_KG;

        // Gabungkan data lama + baru untuk hitung diskon
        $mergedData = array_merge([
            'weight'      => $order->weight,
            'price'       => self::PRICE_PER_KG,
            'customer_id' => $order->customer_id,
        ], $data);

        $this->calculateDiscount($mergedData);

        $data['subtotal']         = $mergedData['subtotal'];
        $data['discount_percent'] = $mergedData['discount_percent'];
        $data['discount_amount']  = $mergedData['discount_amount'];
        $data['total_price']      = $mergedData['total_price'];

        // Upload file baru
        if ($request->hasFile('file')) {
            if ($order->file_path) {
                Storage::disk('public')->delete($order->file_path);
            }
            $data['file_path'] = $request->file('file')->store('laundry', 'public');
        }

        $order->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Order updated',
                'data' => $order
            ]);
        }

        return redirect('/orders/' . $order->id)->with('success', 'Order berhasil diupdate!');
    }

    public function destroy($id)
    {
        $order = LaundryOrder::withTrashed()->find($id);

        if (!$order) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Order not found'], 404);
            }
            return back()->with('error', 'Order not found');
        }

        $order->forceDelete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Order terhapus'], 200);
        }

        return redirect('/orders')->with('success', 'Order berhasil dihapus');
    }

    /**
     * Hitung subtotal, diskon, dan total berdasarkan tier customer
     */



    private function calculateDiscount(array &$data): void
    {
        // S1: Menghitung subtotal (berat x harga per kg)
        $subtotal = ($data['weight'] ?? 0) * self::PRICE_PER_KG; 
        
        // S2: Menginisialisasi (menyetel awal) persentase diskon menjadi 0
        $discountPercent = 0;                                     

        // S3: Mengecek apakah customer_id tidak kosong
        if (!empty($data['customer_id'])) {                       
            
            // S4: Mencari data customer ke database berdasarkan ID
            $customer = Customer::find($data['customer_id']);     
            
            // S5: Mengecek apakah data customer berhasil ditemukan
            if ($customer) {                                      
                
                // S6: Mengambil nilai persentase diskon milik customer
                $discountPercent = $customer->discount_percent;   
            }
        }

        // S7: Menghitung nominal potongan diskon dalam Rupiah
        $discountAmount = $subtotal * ($discountPercent / 100);   
        
        // S8: Menghitung total akhir (subtotal dikurangi diskon)
        $totalPrice = $subtotal - $discountAmount;                

        // S9: Memasukkan hasil perhitungan ke dalam array data
        $data['subtotal']         = $subtotal;                    
        $data['discount_percent'] = $discountPercent;
        $data['discount_amount']  = $discountAmount;
        $data['total_price']      = $totalPrice;
    }
}