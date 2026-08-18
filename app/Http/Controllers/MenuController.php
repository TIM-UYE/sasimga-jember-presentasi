<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\KategoriMenu;
use App\Models\MenuSpecial;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategori')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.menu.index', compact('menus'));
    }

    public function frontend()
    {
        $menus = Menu::with('kategori')
            ->where('is_available', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $specials = MenuSpecial::with('items')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $kategoris = KategoriMenu::where('is_active', true)
            ->withCount(['menus' => function ($query) {
                $query->where('is_available', true);
            }])
            ->orderBy('nama_kategori')
            ->get();

        return view('frontend.menu.index', compact('menus', 'kategoris', 'specials'));
    }

    public function create()
    {
        $kategoris = KategoriMenu::where('is_active', true)
            ->orderBy('nama_kategori')
            ->get();

        return view('admin.menu.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'kategori_id' => 'nullable|exists:kategori_menu,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_available' => 'boolean',
            'ukuran' => 'nullable|string|max:100',
            'bahan' => 'nullable|string',
            'durasi_persiapan' => 'nullable|integer|min:1',
        ]);

        $data = $request->except(['gambar']);

        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $filename = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('storage/menu'), $filename);
            $data['gambar'] = $filename;
        }

        $menu = Menu::create($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    public function show(Menu $menu)
    {
        $menu->load('kategori');

        return view('admin.menu.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        $kategoris = KategoriMenu::where('is_active', true)
            ->orderBy('nama_kategori')
            ->get();

        $menu->load('kategori');

        return view('admin.menu.edit', compact('menu', 'kategoris'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'kategori_id' => 'nullable|exists:kategori_menu,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_available' => 'boolean',
            'ukuran' => 'nullable|string|max:100',
            'bahan' => 'nullable|string',
            'durasi_persiapan' => 'nullable|integer|min:1',
        ]);

        $data = $request->except(['gambar']);

        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('gambar')) {
            if ($menu->gambar && file_exists(public_path('storage/menu/' . $menu->gambar))) {
                unlink(public_path('storage/menu/' . $menu->gambar));
            }

            $gambar = $request->file('gambar');
            $filename = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('storage/menu'), $filename);
            $data['gambar'] = $filename;
        }

        $menu->update($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->gambar && file_exists(public_path('storage/menu/' . $menu->gambar))) {
            unlink(public_path('storage/menu/' . $menu->gambar));
        }

        $menu->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus!');
    }
}

