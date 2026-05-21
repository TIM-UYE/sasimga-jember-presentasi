<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InformationRequest;
use App\Models\Information;
use App\Services\InformationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformationController extends Controller
{
    public function __construct()
    {
        $this->infoService = new InformationService();
    }

    /**
     * Display a listing of the information pages.
     */
    public function index()
    {
        $information = Information::orderBy('slug')->get();
        return view('admin.information.index', compact('information'));
    }

    /**
     * Show the form for creating a new information page.
     */
    public function create()
    {
        $types = InformationService::getTypes();
        $icons = InformationService::getAvailableIcons();
        $routes = InformationService::getAvailableRoutes();
        return view('admin.information.create', compact('types', 'icons', 'routes'));
    }

    /**
     * Store a newly created information page in storage.
     */
    public function store(InformationRequest $request)
    {
        $slug = $request->slug;
        $data = $request->all();

        // Handle image upload for About page
        if ($slug === 'about' && $request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('information/about', 'public');
        }

        $content = InformationService::buildContent($slug, $data);

        Information::create([
            'slug' => $slug,
            'title' => $request->title,
            'content' => $content,
        ]);

        return redirect()
            ->route('admin.information.index')
            ->with('success', 'Halaman informasi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified information page.
     */
    public function edit(Information $information)
    {
        $types = InformationService::getTypes();
        $icons = InformationService::getAvailableIcons();
        $routes = InformationService::getAvailableRoutes();
        $parsedContent = InformationService::parseContent($information->slug, $information->content);

        return view('admin.information.edit', compact(
            'information',
            'types',
            'icons',
            'routes',
            'parsedContent'
        ));
    }

    /**
     * Update the specified information page in storage.
     */
    public function update(InformationRequest $request, Information $information)
    {
        $slug = $request->slug;
        $data = $request->all();

        // Handle image upload for About page
        if ($slug === 'about') {
            if ($request->hasFile('image_file')) {
                // Delete old image if exists and is in storage
                $oldContent = json_decode($information->content, true);
                $oldImage = $oldContent['image'] ?? null;
                if ($oldImage && str_starts_with($oldImage, 'information/')) {
                    Storage::disk('public')->delete($oldImage);
                }
                // Store new image
                $data['image'] = $request->file('image_file')->store('information/about', 'public');
            } else {
                // Keep existing image from hidden field or stored content
                $data['image'] = $request->existing_image;
                if (!$data['image']) {
                    $oldContent = json_decode($information->content, true);
                    $data['image'] = $oldContent['image'] ?? 'images/about/depan.jpg';
                }
            }
        }

        $content = InformationService::buildContent($slug, $data);

        $information->update([
            'slug' => $slug,
            'title' => $request->title,
            'content' => $content,
        ]);

        return redirect()
            ->route('admin.information.index')
            ->with('success', 'Halaman informasi berhasil diperbarui.');
    }

    /**
     * Remove the specified information page from storage.
     */
    public function destroy(Information $information)
    {
        // Delete associated image if stored in storage
        $content = json_decode($information->content, true);
        $image = $content['image'] ?? null;
        if ($image && str_starts_with($image, 'information/')) {
            Storage::disk('public')->delete($image);
        }

        $information->delete();

        return redirect()
            ->route('admin.information.index')
            ->with('success', 'Halaman informasi berhasil dihapus.');
    }
}
