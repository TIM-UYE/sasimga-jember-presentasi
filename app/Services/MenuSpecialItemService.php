<?php

namespace App\Services;

use App\Models\MenuSpecial;
use App\Models\MenuSpecialItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class MenuSpecialItemService
{
    public function store(MenuSpecial $special, array $data): MenuSpecialItem
    {
        unset($data['stok_id'], $data['jumlah_dibutuhkan']);

        $data['menu_special_id'] = $special->id;
        $data['is_available'] = !empty($data['is_available']);
        $data['image'] = $this->storeItemImage($data['image'] ?? null);

        $item = MenuSpecialItem::create($data);

        return $item;
    }

    public function update(MenuSpecialItem $item, array $data): MenuSpecialItem
    {
        unset($data['stok_id'], $data['jumlah_dibutuhkan']);

        $data['is_available'] = !empty($data['is_available']);

        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->storeItemImage($data['image'], $item->image);
        } else {
            unset($data['image']);
        }

        $item->update($data);

        return $item;
    }

    public function delete(MenuSpecialItem $item): void
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();
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
