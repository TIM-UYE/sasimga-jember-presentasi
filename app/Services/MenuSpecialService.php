<?php

namespace App\Services;

use App\Models\MenuSpecial;
use App\Models\MenuSpecialItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class MenuSpecialService
{
    public function list(bool $activeOnly = false)
    {
        $query = MenuSpecial::withCount('items')->orderByDesc('created_at');

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->get();
    }

    public function get(MenuSpecial $special): MenuSpecial
    {
        return $special->load('items.komposisiBahan.stok');
    }

    public function create(array $data, array $items = []): MenuSpecial
    {
        $data['slug'] = $this->generateSlug($data['title']);
        $data['banner_image'] = $this->storeBannerImage($data['banner_image'] ?? null);
        $data['is_active'] = !empty($data['is_active']);

        $special = MenuSpecial::create($data);

        foreach ($items as $item) {
            $item['menu_special_id'] = $special->id;
            $item['is_available'] = !empty($item['is_available']);
            $item['image'] = $this->storeItemImage($item['image'] ?? null);
            MenuSpecialItem::create($item);
        }

        return $special;
    }

    public function update(MenuSpecial $special, array $data): MenuSpecial
    {
        $data['slug'] = $this->generateSlug($data['title'], $special->id);
        $data['is_active'] = !empty($data['is_active']);

        if (!empty($data['banner_image']) && $data['banner_image'] instanceof UploadedFile) {
            $data['banner_image'] = $this->storeBannerImage($data['banner_image'], $special->banner_image);
        } else {
            unset($data['banner_image']);
        }

        $special->update($data);

        return $special;
    }

    public function delete(MenuSpecial $special): void
    {
        if ($special->banner_image) {
            Storage::disk('public')->delete($special->banner_image);
        }

        $special->items()->each(function ($item) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
        });

        $special->delete();
    }

    protected function generateSlug(string $title, int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (MenuSpecial::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    protected function storeBannerImage(?UploadedFile $file, ?string $existing = null): ?string
    {
        if (! $file) {
            return $existing;
        }

        if ($existing) {
            Storage::disk('public')->delete($existing);
        }

        return $file->store('menu-specials', 'public');
    }

    protected function storeItemImage(?UploadedFile $file, ?string $existing = null): ?string
    {
        if (! $file) {
            return $existing;
        }

        if ($existing) {
            Storage::disk('public')->delete($existing);
        }

        return $file->store('menu-special-items', 'public');
    }
}