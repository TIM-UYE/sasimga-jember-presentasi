<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\KategoriMenu;
use App\Models\MenuSpecial;
use App\Models\Stok;
use App\Models\MenuBahan;
use App\Services\StockCalculationService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected StockCalculationService $stockService;

    public function __construct(StockCalculationService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $menus = Menu::with('kategori', 'komposisiBahan.stok')
            ->orderBy('created_at', 'desc')
            ->get();

        $stockData = $this->stockService->calculateMenusStock($menus);
        $lowStockIngredients = $this->stockService->getLowStockIngredients();

        return view('admin.menu.index', compact('menus', 'stockData', 'lowStockIngredients'));
    }

    public function frontend()
    {
        $menus = Menu::with('kategori', 'komposisiBahan.stok')
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

        $stoks = Stok::orderBy('nama_bahan')->get();

        return view('admin.menu.create', compact('kategoris', 'stoks'));
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

            'bahan_stok_id' => 'nullable|array',
            'bahan_stok_id.*' => 'nullable|exists:stok,id',
            'jumlah_dibutuhkan' => 'nullable|array',
            'jumlah_dibutuhkan.*' => 'nullable|numeric|min:0',
            'satuan_bahan' => 'nullable|array',
            'satuan_bahan.*' => 'nullable|string|max:50',
        ]);

        $data = $request->except([
            'gambar',
            'bahan_stok_id',
            'jumlah_dibutuhkan',
            'satuan_bahan',
        ]);

        $data['is_available'] = $request->has('is_available');
        $data['stok'] = 0; // Stock is auto-calculated

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $filename = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('storage/menu'), $filename);
            $data['gambar'] = $filename;
        }

        $menu = Menu::create($data);

        if ($request->has('bahan_stok_id')) {
            foreach ($request->bahan_stok_id as $index => $stokId) {
                $jumlah = $request->jumlah_dibutuhkan[$index] ?? null;
                $satuan = $request->satuan_bahan[$index] ?? null;

                if ($stokId && $jumlah && $jumlah > 0) {
                    // Auto-detect satuan from stok if not provided
                    if (!$satuan) {
                        $stok = Stok::find($stokId);
                        $satuan = $stok ? $stok->satuan : 'gram';
                    }

                    MenuBahan::create([
                        'menuable_id' => $menu->id,
                        'menuable_type' => Menu::class,
                        'stok_id' => $stokId,
                        'jumlah_dibutuhkan' => $jumlah,
                        'satuan' => $satuan,
                    ]);
                }
            }
        }

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    public function show(Menu $menu)
    {
        $menu->load('kategori', 'komposisiBahan.stok');
        $stockCalc = $menu->getStockCalculationDetails();

        return view('admin.menu.show', compact('menu', 'stockCalc'));
    }

    public function edit(Menu $menu)
    {
        $kategoris = KategoriMenu::where('is_active', true)
            ->orderBy('nama_kategori')
            ->get();

        $stoks = Stok::orderBy('nama_bahan')->get();

        $menu->load('komposisiBahan.stok');

        $stockCalc = $menu->getStockCalculationDetails();

        // Dapatkan stok_id yang sudah dipilih untuk opsi ini saja
        $selectedStokIds = $menu->komposisiBahan->pluck('stok_id')->toArray();

        return view('admin.menu.edit', compact('menu', 'kategoris', 'stoks', 'stockCalc', 'selectedStokIds'));
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

            'bahan_stok_id' => 'nullable|array',
            'bahan_stok_id.*' => 'nullable|exists:stok,id',
            'jumlah_dibutuhkan' => 'nullable|array',
            'jumlah_dibutuhkan.*' => 'nullable|numeric|min:0',
            'satuan_bahan' => 'nullable|array',
            'satuan_bahan.*' => 'nullable|string|max:50',
        ]);

        $data = $request->except([
            'gambar',
            'bahan_stok_id',
            'jumlah_dibutuhkan',
            'satuan_bahan',
        ]);

        $data['is_available'] = $request->has('is_available');
        // Keep stok as auto-calculated
        $data['stok'] = 0;

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

        $menu->komposisiBahan()->delete();

        if ($request->has('bahan_stok_id')) {
            foreach ($request->bahan_stok_id as $index => $stokId) {
                $jumlah = $request->jumlah_dibutuhkan[$index] ?? null;
                $satuan = $request->satuan_bahan[$index] ?? null;

                if ($stokId && $jumlah && $jumlah > 0) {
                    if (!$satuan) {
                        $stok = Stok::find($stokId);
                        $satuan = $stok ? $stok->satuan : 'gram';
                    }

                    MenuBahan::create([
                        'menuable_id' => $menu->id,
                        'menuable_type' => Menu::class,
                        'stok_id' => $stokId,
                        'jumlah_dibutuhkan' => $jumlah,
                        'satuan' => $satuan,
                    ]);
                }
            }
        }

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->gambar && file_exists(public_path('storage/menu/' . $menu->gambar))) {
            unlink(public_path('storage/menu/' . $menu->gambar));
        }

        $menu->komposisiBahan()->delete();

        $menu->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus!');
    }
}
