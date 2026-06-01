<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lantai;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LayoutController extends Controller
{
    /**
     * Display layout editor for a floor.
     */
    public function index($slug = null)
    {
        $lantais = Lantai::orderBy('urutan')->get();

        if (!$slug) {
            $lantai = $lantais->first();
        } else {
            $lantai = Lantai::where('slug', $slug)->firstOrFail();
        }

        $mejas = Meja::where('lantai_id', $lantai->id)
            ->orderBy('nomor_meja')
            ->get();

        return view('admin.layout.index', compact('lantais', 'lantai', 'mejas'));
    }

    /**
     * Update floor preview image.
     */
    public function updatePreview(Request $request, $id)
    {
        $lantai = Lantai::findOrFail($id);

        $request->validate([
            'preview_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('preview_image')) {
            // Delete old image
            if ($lantai->preview_image) {
                Storage::disk('public')->delete($lantai->preview_image);
            }

            $path = $request->file('preview_image')->store('layouts', 'public');
            $lantai->update(['preview_image' => $path]);

            return redirect()->back()->with('success', 'Gambar preview lantai berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal mengupload gambar.');
    }

    /**
     * Store a new table.
     */
    public function storeTable(Request $request)
    {
        $request->validate([
            'lantai_id' => 'required|exists:lantai,id',
            'nama_meja' => 'required|string|max:255',
            'nomor_meja' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1|max:20',
            'pos_x' => 'required|integer|min:0',
            'pos_y' => 'required|integer|min:0',
            'kategori' => 'required|in:regular,vip,booth',
            'is_mergeable' => 'boolean',
            'merge_group' => 'nullable|string|max:50',
        ]);

        $meja = Meja::create([
            'lantai_id' => $request->lantai_id,
            'nama_meja' => $request->nama_meja,
            'nomor_meja' => $request->nomor_meja,
            'kapasitas' => $request->kapasitas,
            'pos_x' => $request->pos_x,
            'pos_y' => $request->pos_y,
            'posisi_row' => 'X',
            'posisi_col' => $request->nomor_meja,
            'kategori' => $request->kategori,
            'is_mergeable' => $request->boolean('is_mergeable', false),
            'merge_group' => $request->merge_group,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil ditambahkan.',
            'table' => $meja->load('lantai'),
        ]);
    }

    /**
     * Update table position (drag & drop).
     */
    public function updateTablePosition(Request $request, $id)
    {
        $request->validate([
            'pos_x' => 'required|integer|min:0',
            'pos_y' => 'required|integer|min:0',
        ]);

        $meja = Meja::findOrFail($id);
        $meja->update([
            'pos_x' => $request->pos_x,
            'pos_y' => $request->pos_y,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Posisi meja berhasil diperbarui.',
        ]);
    }

    /**
     * Get single table data.
     */
    public function getTable($id)
    {
        $meja = Meja::with('lantai')->findOrFail($id);

        return response()->json([
            'success' => true,
            'table' => $meja,
        ]);
    }

    /**
     * Update table details.
     */
    public function updateTable(Request $request, $id)
    {
        $meja = Meja::findOrFail($id);

        $request->validate([
            'nama_meja' => 'required|string|max:255',
            'nomor_meja' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1|max:20',
            'kategori' => 'required|in:regular,vip,booth',
            'is_mergeable' => 'boolean',
            'merge_group' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $meja->update([
            'nama_meja' => $request->nama_meja,
            'nomor_meja' => $request->nomor_meja,
            'kapasitas' => $request->kapasitas,
            'kategori' => $request->kategori,
            'is_mergeable' => $request->boolean('is_mergeable', false),
            'merge_group' => $request->merge_group ?: null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('table_image')) {
            $request->validate([
                'table_image' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            if ($meja->table_image) {
                Storage::disk('public')->delete($meja->table_image);
            }

            $path = $request->file('table_image')->store('tables', 'public');
            $meja->update(['table_image' => $path]);
        }

        return redirect()->back()->with('success', 'Meja berhasil diperbarui.');
    }

    /**
     * Delete a table.
     */
    public function destroyTable($id)
    {
        $meja = Meja::findOrFail($id);

        if ($meja->table_image) {
            Storage::disk('public')->delete($meja->table_image);
        }

        $meja->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil dihapus.',
        ]);
    }

    /**
     * Bulk update table positions.
     */
    public function bulkUpdatePositions(Request $request)
    {
        $request->validate([
            'positions' => 'required|array',
            'positions.*.id' => 'required|exists:meja,id',
            'positions.*.pos_x' => 'required|integer|min:0',
            'positions.*.pos_y' => 'required|integer|min:0',
        ]);

        foreach ($request->positions as $position) {
            Meja::where('id', $position['id'])->update([
                'pos_x' => $position['pos_x'],
                'pos_y' => $position['pos_y'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Posisi meja berhasil diperbarui.',
        ]);
    }
}
