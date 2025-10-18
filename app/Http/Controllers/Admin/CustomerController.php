<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $query = Customer::query();

        // Lọc theo từ khóa tìm kiếm
        if (request('search')) {
            $search = request('search');
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
        }

        // Thống kê
        $stats = [
            'total' => Customer::count(),
            'vip' => Customer::where('is_vip', true)->count(),
            'new_month' => Customer::whereMonth('created_at', now()->month)->count(),
            'return_rate' => 75, // ví dụ cứng, có thể tính dựa trên orders
        ];

        // Danh sách khách hàng có phân trang
        $customers = $query->withCount('orders')
            ->paginate(10)
            ->withQueryString();

        // Fallback: if no customers table rows exist but users do, map users to the expected shape
        if ($customers->total() === 0 && DB::table('users')->count() > 0) {
            $userQuery = User::query();
            if (request('search')) {
                $s = request('search');
                $userQuery->where('name', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%");
            }

            // Map users to a simple paginator-compatible collection
            $users = $userQuery->paginate(10)->withQueryString();

            // Transform users into objects that the view expects (provide orders_count and total_spent)
            $customers = $users->getCollection()->map(function ($u) {
                return (object) [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'phone' => $u->phone ?? '-',
                    'is_vip' => $u->is_admin ? true : false,
                    'orders_count' => $u->orders()->count() ?? 0,
                    'total_spent' => $u->orders()->sum('total') ?? 0,
                    'created_at' => $u->created_at,
                ];
            });

            // Replace the paginator's collection while keeping pagination metadata
            $customers = new \Illuminate\Pagination\LengthAwarePaginator(
                $customers,
                $users->total(),
                $users->perPage(),
                $users->currentPage(),
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show($id)
    {
        $customer = Customer::with('orders')->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        // Try to load a Customer row first; if not found, try User
        $customer = Customer::find($id);
        $user = null;
        if (!$customer) {
            $user = User::findOrFail($id);
        }

        return view('admin.customers.edit', compact('customer', 'user'));
    }

    public function update($id)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:32',
            'is_vip' => 'nullable|boolean',
        ]);

        $customer = Customer::find($id);
        if ($customer) {
            $customer->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'is_vip' => (bool) ($data['is_vip'] ?? false),
            ]);
        } else {
            // Update fallback user record
            $user = User::findOrFail($id);
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);
        }

        return redirect()->route('admin.customers.index')->with('success', 'Cập nhật khách hàng thành công');
    }
}