<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class LaundryOrderController extends Controller
{
    
    public function index(Request $request)
    {
        $limit = intval($request->get('limit', 10));
        $search = $request->get('search');
        $orderBy = $request->get('orderBy', 'created_at');
        $sortBy = strtolower($request->get('sortBy', 'desc')) === 'asc' ? 'asc' : 'desc';

        $query = LaundryOrder::query();

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
        'customer_name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:50',
        'address' => 'nullable|string',
        'service_type' => ['required', Rule::in(['kiloan','dry_cleaning','antar_jemput','setrika_premium'])],
        'weight' => 'nullable|numeric|min:0',
        'status' => ['nullable', Rule::in(['pending','in_progress','done','delivered'])],
        'price' => 'nullable|integer|min:0',
        'notes' => 'nullable|string',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    if ($request->hasFile('file')) {
        $data['file_path'] = $request->file('file')->store('laundry', 'public');
    }

    $data['weight'] ??= 0;
    $data['status'] ??= 'pending';
    $data['price'] ??= 0;

    $order = LaundryOrder::create($data);

    // Perbaikan di sini - handle web request
    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Order created',
            'data' => $order
        ], 201);
    }

    // Untuk web request, redirect dengan flash message
    return redirect('/orders')->with('success', 'Order berhasil dibuat!');
}

   
    public function show($id)
    {
        $order = LaundryOrder::find($id);

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
        'customer_name' => 'sometimes|string|max:255',
        'phone' => 'sometimes|nullable|string|max:50',
        'address' => 'sometimes|nullable|string',
        'service_type' => ['sometimes', Rule::in(['kiloan','dry_cleaning','antar_jemput','setrika_premium'])],
        'weight' => 'sometimes|numeric|min:0',
        'status' => ['sometimes', Rule::in(['pending','in_progress','done','delivered'])],
        'price' => 'sometimes|integer|min:0',
        'notes' => 'sometimes|nullable|string',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    if ($request->hasFile('file')) {
        if ($order->file_path) {
            Storage::disk('public')->delete($order->file_path);
        }
        $data['file_path'] = $request->file('file')->store('laundry', 'public');
    }

    $order->update($data);

    // Handle web request
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
}
