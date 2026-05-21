<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\StokLog;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index()
    {
        $stok = Stok::latest()->get();

        return view('admin.stok.index', compact('stok'));
    }

    public function create()
    {
        return view('admin.stok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'jumlah_stok' => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
        ]);

        $stok = Stok::create($request->only([
            'nama_bahan',
            'satuan',
            'jumlah_stok',
            'stok_minimum',
        ]));

        StokLog::create([
            'stok_id' => $stok->id,
            'tipe' => 'masuk',
            'jumlah' => $stok->jumlah_stok,
            'stok_sebelum' => 0,
            'stok_sesudah' => $stok->jumlah_stok,
            'keterangan' => 'Stok awal bahan',
        ]);

        return redirect()->route('admin.stok.index')
            ->with('success', 'Data stok bahan berhasil ditambahkan.');
    }

    public function edit(Stok $stok)
    {
        return view('admin.stok.edit', compact('stok'));
    }

    public function update(Request $request, Stok $stok)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'jumlah_stok' => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
        ]);

        $stokSebelum = $stok->jumlah_stok;

        $stok->update($request->only([
            'nama_bahan',
            'satuan',
            'jumlah_stok',
            'stok_minimum',
        ]));

        if ($stokSebelum != $stok->jumlah_stok) {
            StokLog::create([
                'stok_id' => $stok->id,
                'tipe' => $stok->jumlah_stok > $stokSebelum ? 'masuk' : 'keluar',
                'jumlah' => abs($stok->jumlah_stok - $stokSebelum),
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stok->jumlah_stok,
                'keterangan' => 'Perubahan stok manual oleh admin',
            ]);
        }

        return redirect()->route('admin.stok.index')
            ->with('success', 'Data stok bahan berhasil diperbarui.');
    }

    public function destroy(Stok $stok)
    {
        $stok->delete();

        return redirect()->route('admin.stok.index')
            ->with('success', 'Data stok bahan berhasil dihapus.');
    }
}