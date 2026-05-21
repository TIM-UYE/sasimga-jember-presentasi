<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    public function index()
    {
        $mejas = Meja::orderBy('posisi_row')
            ->orderBy('posisi_col')
            ->get();

        return view('admin.meja.index', compact('mejas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_meja' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1',
            'posisi_row' => 'required|string|max:5',
            'posisi_col' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        Meja::create([
            'nama_meja' => $request->nama_meja,
            'kategori' => $request->kategori,
            'kapasitas' => $request->kapasitas,
            'posisi_row' => strtoupper($request->posisi_row),
            'posisi_col' => $request->posisi_col,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.meja.index')
            ->with('success', 'Meja berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $meja = Meja::findOrFail($id);
        $meja->delete();

        return redirect()
            ->route('admin.meja.index')
            ->with('success', 'Meja berhasil dihapus.');
    }
}
