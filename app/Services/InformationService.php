<?php

namespace App\Services;

class InformationService
{
    /**
     * Build content array based on slug type from form input.
     */
    public static function buildContent(string $slug, array $data): string
    {
        return match ($slug) {
            'about' => self::buildAboutContent($data),
            'faq' => self::buildFaqContent($data),
            'privacy-policy' => self::buildPolicyContent($data),
            'terms-conditions' => self::buildTermsContent($data),
            default => json_encode($data),
        };
    }

    /**
     * Parse content JSON back to form array based on slug type.
     */
    public static function parseContent(string $slug, string $json): array
    {
        $data = json_decode($json, true) ?? [];
        return $data;
    }

    /**
     * Get available information types.
     */
    public static function getTypes(): array
    {
        return [
            'about' => [
                'label' => 'About Us',
                'icon' => 'fa-circle-info',
                'description' => 'Halaman tentang perusahaan / restoran',
            ],
            'faq' => [
                'label' => 'FAQ',
                'icon' => 'fa-circle-question',
                'description' => 'Pertanyaan yang sering diajukan',
            ],
            'privacy-policy' => [
                'label' => 'Privacy Policy',
                'icon' => 'fa-shield-halved',
                'description' => 'Kebijakan privasi & keamanan data',
            ],
            'terms-conditions' => [
                'label' => 'Terms & Conditions',
                'icon' => 'fa-file-signature',
                'description' => 'Syarat dan ketentuan layanan',
            ],
        ];
    }

    /**
     * Get available routes for CTA button.
     */
    public static function getAvailableRoutes(): array
    {
        return [
            'frontend.home' => 'Home',
            'frontend.about' => 'About',
            'frontend.menu' => 'Menu',
            'frontend.reservasi' => 'Reservasi',
            'frontend.faq' => 'FAQ',
            'frontend.privacy' => 'Privacy Policy',
            'frontend.terms' => 'Terms & Conditions',
        ];
    }

    /**
     * Get available Font Awesome icons for features.
     */
    public static function getAvailableIcons(): array
    {
        return [
            'fa-fire' => 'Fire',
            'fa-utensils' => 'Utensils',
            'fa-user-shield' => 'User Shield',
            'fa-database' => 'Database',
            'fa-lock' => 'Lock',
            'fa-cookie-bite' => 'Cookie',
            'fa-credit-card' => 'Credit Card',
            'fa-file-contract' => 'Contract',
            'fa-user-check' => 'User Check',
            'fa-calendar-check' => 'Calendar Check',
            'fa-ban' => 'Ban',
            'fa-copyright' => 'Copyright',
            'fa-rotate' => 'Rotate',
            'fa-clock' => 'Clock',
            'fa-motorcycle' => 'Motorcycle',
            'fa-money-bill-wave' => 'Money',
            'fa-users' => 'Users',
            'fa-car-side' => 'Car',
            'fa-star' => 'Star',
            'fa-heart' => 'Heart',
            'fa-thumbs-up' => 'Thumbs Up',
            'fa-gem' => 'Gem',
            'fa-leaf' => 'Leaf',
            'fa-truck' => 'Truck',
            'fa-handshake' => 'Handshake',
            'fa-medal' => 'Medal',
            'fa-certificate' => 'Certificate',
            'fa-award' => 'Award',
            'fa-check-circle' => 'Check Circle',
            'fa-info-circle' => 'Info Circle',
            'fa-exclamation-circle' => 'Exclamation',
            'fa-question-circle' => 'Question',
        ];
    }

    // ──────────────────────────────────────────────
    // CONTENT BUILDERS
    // ──────────────────────────────────────────────

    private static function buildAboutContent(array $data): string
    {
        // Determine image: prefer new upload, fallback to existing, fallback to default
        $image = $data['image'] ?? ($data['existing_image'] ?? 'images/about/depan.jpg');

        $content = [
            'hero_badge' => $data['hero_badge'] ?? '',
            'hero_title' => $data['hero_title'] ?? '',
            'hero_title_highlight' => $data['hero_title_highlight'] ?? '',
            'hero_description' => $data['hero_description'] ?? '',
            'section_badge' => $data['section_badge'] ?? '',
            'section_title' => $data['section_title'] ?? '',
            'section_title_highlight' => $data['section_title_highlight'] ?? '',
            'section_description1' => $data['section_description1'] ?? '',
            'section_description2' => $data['section_description2'] ?? '',
            'features' => [],
            'image' => $image,
            'since' => $data['since'] ?? '',
            'since_tagline' => $data['since_tagline'] ?? '',
        ];

        // Parse features from form array
        if (isset($data['features_icon']) && is_array($data['features_icon'])) {
            foreach ($data['features_icon'] as $i => $icon) {
                if (!empty($data['features_title'][$i] ?? '')) {
                    $content['features'][] = [
                        'icon' => $icon,
                        'title' => $data['features_title'][$i] ?? '',
                        'description' => $data['features_description'][$i] ?? '',
                    ];
                }
            }
        }

        return json_encode($content);
    }

    private static function buildFaqContent(array $data): string
    {
        $content = [
            'subtitle' => $data['subtitle'] ?? '',
            'description' => $data['description'] ?? '',
            'items' => [],
            'cta_text' => $data['cta_text'] ?? '',
            'cta_description' => $data['cta_description'] ?? '',
            'cta_button' => $data['cta_button'] ?? '',
            'cta_route' => $data['cta_route'] ?? '',
        ];

        if (isset($data['items_icon']) && is_array($data['items_icon'])) {
            foreach ($data['items_icon'] as $i => $icon) {
                if (!empty($data['items_question'][$i] ?? '')) {
                    $content['items'][] = [
                        'icon' => $icon,
                        'question' => $data['items_question'][$i] ?? '',
                        'answer' => $data['items_answer'][$i] ?? '',
                    ];
                }
            }
        }

        return json_encode($content);
    }

    private static function buildPolicyContent(array $data): string
    {
        $content = [
            'subtitle' => $data['subtitle'] ?? '',
            'description' => $data['description'] ?? '',
            'items' => [],
            'cta_text' => $data['cta_text'] ?? '',
            'cta_description' => $data['cta_description'] ?? '',
            'cta_button' => $data['cta_button'] ?? '',
            'cta_route' => $data['cta_route'] ?? '',
        ];

        if (isset($data['items_icon']) && is_array($data['items_icon'])) {
            foreach ($data['items_icon'] as $i => $icon) {
                if (!empty($data['items_title'][$i] ?? '')) {
                    $content['items'][] = [
                        'icon' => $icon,
                        'title' => $data['items_title'][$i] ?? '',
                        'description' => $data['items_description'][$i] ?? '',
                    ];
                }
            }
        }

        return json_encode($content);
    }

    private static function buildTermsContent(array $data): string
    {
        // Terms has same structure as privacy policy
        return self::buildPolicyContent($data);
    }
}
