<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Tampilkan daftar customer
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Filter by tier
        if ($request->filled('tier')) {
            $query->where('tier', $request->tier);
        }

        $customers = $query->latest()->paginate(10);

        return view('customers.index', compact('customers'));
    }

    /**
     * Tampilkan form tambah customer
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Simpan customer baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:15|unique:customers,phone',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'tier'    => ['required', Rule::in(['regular', 'silver', 'gold', 'platinum'])],
        ], [
            'name.required'  => 'Nama customer wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.unique'   => 'Nomor telepon sudah terdaftar.',
            'tier.required'  => 'Tier member wajib dipilih.',
        ]);

        Customer::create($data);

        return redirect()->route('customers.index')
                         ->with('success', 'Customer berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail customer
     */
    public function show($id)
    {
        $customer = Customer::with('orders')->findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    /**
     * Tampilkan form edit customer
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => ['required', 'string', 'max:15', Rule::unique('customers', 'phone')->ignore($customer->id)],
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'tier'    => ['required', Rule::in(['regular', 'silver', 'gold', 'platinum'])],
        ]);

        $customer->update($data);

        return redirect()->route('customers.index')
                         ->with('success', 'Customer berhasil diupdate!');
    }

    /**
     * Hapus customer (soft delete)
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')
                         ->with('success', 'Customer berhasil dihapus!');
    }
}