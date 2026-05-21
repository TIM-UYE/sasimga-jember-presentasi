<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MenuSpecial;
use App\Models\MenuSpecialItem;
use App\Services\MenuSpecialItemService;
use Illuminate\Http\Request;

class MenuSpecialItemController extends Controller
{
    protected MenuSpecialItemService $service;

    public function __construct(MenuSpecialItemService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request, MenuSpecial $menu_special)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:600',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'is_available' => 'sometimes|boolean',

            'stok_id' => 'nullable|array',
            'stok_id.*' => 'nullable|exists:stok,id',
            'jumlah_dibutuhkan' => 'nullable|array',
            'jumlah_dibutuhkan.*' => 'nullable|numeric|min:0.01',
        ]);

        $this->service->store($menu_special, $validated);

        return redirect()->route('admin.menu-specials.edit', $menu_special)
            ->with('success', 'Varian menu berhasil ditambahkan.');
    }

    public function update(Request $request, MenuSpecial $menu_special, MenuSpecialItem $menu_special_item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:600',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'is_available' => 'sometimes|boolean',

            'stok_id' => 'nullable|array',
            'stok_id.*' => 'nullable|exists:stok,id',
            'jumlah_dibutuhkan' => 'nullable|array',
            'jumlah_dibutuhkan.*' => 'nullable|numeric|min:0.01',
        ]);

        $this->service->update($menu_special_item, $validated);

        return redirect()->route('admin.menu-specials.edit', $menu_special)
            ->with('success', 'Varian menu berhasil diperbarui.');
    }

    public function destroy(MenuSpecial $menu_special, MenuSpecialItem $menu_special_item)
    {
        $this->service->delete($menu_special_item);

        return redirect()->route('admin.menu-specials.edit', $menu_special)
            ->with('success', 'Varian menu berhasil dihapus.');
    }
}