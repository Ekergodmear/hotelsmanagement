<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Hiển thị danh sách dịch vụ.
     */
    public function index(): View
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    /**
     * Hiển thị form tạo dịch vụ mới.
     */
    public function create(): View
    {
        return view('services.create');
    }

    /**
     * Lưu dịch vụ mới vào cơ sở dữ liệu.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:room_service,laundry,food,transport,other',
            'is_active' => 'boolean',
        ]);

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Dịch vụ đã được tạo thành công.');
    }

    /**
     * Hiển thị thông tin chi tiết của dịch vụ.
     */
    public function show(Service $service): View
    {
        return view('services.show', compact('service'));
    }

    /**
     * Hiển thị form chỉnh sửa dịch vụ.
     */
    public function edit(Service $service): View
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Cập nhật thông tin dịch vụ trong cơ sở dữ liệu.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:room_service,laundry,food,transport,other',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')
            ->with('success', 'Thông tin dịch vụ đã được cập nhật thành công.');
    }

    /**
     * Xóa dịch vụ khỏi cơ sở dữ liệu.
     */
    public function destroy(Service $service): RedirectResponse
    {
        // Kiểm tra xem dịch vụ có đang được sử dụng không
        if ($service->bookingServices()->exists()) {
            return back()->with('error', 'Không thể xóa dịch vụ vì đang được sử dụng trong đặt phòng.');
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Dịch vụ đã được xóa thành công.');
    }
}
