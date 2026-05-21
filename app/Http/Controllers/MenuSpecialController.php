<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MenuSpecial;
use App\Models\Stok;
use App\Services\MenuSpecialService;
use Illuminate\Http\Request;

class MenuSpecialController extends Controller
{
    protected MenuSpecialService $service;

    public function __construct(MenuSpecialService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $specials = $this->service->list();

        return view('Admin.special_menu.index', compact('specials'));
    }

    public function create()
    {
        return view('Admin.special_menu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'is_active' => 'sometimes|boolean',
            'items' => 'nullable|array',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.price' => 'required_with:items|numeric|min:0',
            'items.*.description' => 'nullable|string|max:600',
            'items.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'items.*.is_available' => 'sometimes|boolean',
        ]);

        $items = $validated['items'] ?? [];
        unset($validated['items']);

        $this->service->create($validated, $items);

        return redirect()->route('admin.menu-specials.index')
            ->with('success', 'Menu Special berhasil ditambahkan.');
    }

    public function edit(MenuSpecial $menu_special)
    {
        $special = $this->service->get($menu_special);

        $stoks = Stok::orderBy('nama_bahan')->get();

        return view('Admin.special_menu.edit', compact('special', 'stoks'));
    }

    public function update(Request $request, MenuSpecial $menu_special)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'is_active' => 'sometimes|boolean',
        ]);

        $this->service->update($menu_special, $validated);

        return redirect()->route('admin.menu-specials.edit', $menu_special)
            ->with('success', 'Menu Special berhasil diperbarui.');
    }

    public function destroy(MenuSpecial $menu_special)
    {
        $this->service->delete($menu_special);

        return redirect()->route('admin.menu-specials.index')
            ->with('success', 'Menu Special berhasil dihapus.');
    }
}