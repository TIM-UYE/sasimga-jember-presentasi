<?php

namespace Tests\Feature;

use Tests\TestCase;

class GalleryPageTest extends TestCase
{
    public function test_gallery_page_is_accessible()
    {
        $response = $this->get('/gallery');

        $response->assertStatus(200);
        $response->assertSeeText('Galeri');
    }
}
