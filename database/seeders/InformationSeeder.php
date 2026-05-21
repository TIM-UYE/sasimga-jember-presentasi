<?php

namespace Database\Seeders;

use App\Models\Information;
use Illuminate\Database\Seeder;

class InformationSeeder extends Seeder
{
    public function run(): void
    {
        $information = [
            [
                'slug' => 'faq',
                'title' => 'FAQ - Sate Simpangtiga',
                'content' => json_encode([
                    'subtitle' => 'Frequently Asked Questions',
                    'description' => 'Temukan informasi lengkap seputar reservasi, pembayaran, pemesanan, layanan restoran, dan pengalaman kuliner terbaik di Rumah Makan Sate Simpangtiga.',
                    'items' => [
                        [
                            'icon' => 'fa-calendar-check',
                            'question' => 'Apakah bisa melakukan reservasi online?',
                            'answer' => 'Ya, pelanggan dapat melakukan reservasi meja secara online melalui halaman reservasi di website kami dengan mudah dan cepat.',
                        ],
                        [
                            'icon' => 'fa-money-bill-wave',
                            'question' => 'Metode pembayaran apa saja yang tersedia?',
                            'answer' => 'Kami menerima pembayaran tunai (cash), transfer bank, QRIS, dan berbagai metode pembayaran digital lainnya untuk memudahkan pelanggan.',
                        ],
                        [
                            'icon' => 'fa-utensils',
                            'question' => 'Apakah bisa makan langsung di tempat?',
                            'answer' => 'Tentu. Pelanggan dapat menikmati hidangan langsung di restoran dengan suasana nyaman dan pelayanan terbaik dari kami.',
                        ],
                        [
                            'icon' => 'fa-motorcycle',
                            'question' => 'Apakah tersedia layanan pesan online atau takeaway?',
                            'answer' => 'Ya, semua menu dapat dipesan secara online maupun dibawa pulang (takeaway) sesuai kebutuhan pelanggan.',
                        ],
                        [
                            'icon' => 'fa-clock',
                            'question' => 'Jam operasional restoran?',
                            'answer' => 'Sate Simpangtiga buka setiap hari mulai pukul 11.00 WIB hingga 23.00 WIB.',
                        ],
                        [
                            'icon' => 'fa-users',
                            'question' => 'Apakah bisa reservasi untuk acara keluarga?',
                            'answer' => 'Bisa. Kami melayani reservasi untuk acara keluarga, gathering, ulang tahun, dan acara spesial lainnya.',
                        ],
                        [
                            'icon' => 'fa-car-side',
                            'question' => 'Apakah tersedia area parkir?',
                            'answer' => 'Ya, tersedia area parkir yang luas dan aman untuk kendaraan motor maupun mobil pelanggan.',
                        ],
                        [
                            'icon' => 'fa-star',
                            'question' => 'Apa menu favorit di Sate Simpangtiga?',
                            'answer' => 'Menu favorit pelanggan kami adalah Sate Kambing, Sate Ayam, Gulai, dan berbagai menu khas Nusantara lainnya.',
                        ],
                    ],
                    'cta_text' => 'Masih punya pertanyaan?',
                    'cta_description' => 'Tim kami siap membantu Anda untuk reservasi, informasi menu, maupun layanan lainnya.',
                    'cta_button' => 'Reservasi Sekarang',
                    'cta_route' => 'frontend.reservasi',
                ]),
            ],
            [
                'slug' => 'about',
                'title' => 'About - Sate Simpang Tiga',
                'content' => json_encode([
                    'hero_badge' => 'About Us',
                    'hero_title' => 'Tentang',
                    'hero_title_highlight' => 'Sate Simpang Tiga',
                    'hero_description' => 'Mengenal lebih dekat perjalanan dan cita rasa autentik yang telah menjadi bagian dari pengalaman kuliner keluarga Indonesia.',
                    'section_badge' => 'Our Story',
                    'section_title' => 'Cita Rasa Autentik',
                    'section_title_highlight' => 'Sate Simpang Tiga',
                    'section_description1' => 'Berdiri sejak tahun 1975, Sate Simpang Tiga menghadirkan resep turun-temurun dengan bahan berkualitas dan cita rasa autentik yang tetap terjaga dari generasi ke generasi.',
                    'section_description2' => 'Kami percaya bahwa makanan bukan hanya tentang rasa, tetapi juga tentang pengalaman, kehangatan, dan kenangan yang tercipta di setiap hidangan.',
                    'features' => [
                        [
                            'icon' => 'fa-fire',
                            'title' => 'Dibakar Arang',
                            'description' => 'Menghasilkan aroma khas yang menggugah selera.',
                        ],
                        [
                            'icon' => 'fa-utensils',
                            'title' => 'Bahan Premium',
                            'description' => 'Menggunakan bahan segar dan bumbu pilihan terbaik.',
                        ],
                    ],
                    'image' => 'images/about/depan.jpg',
                    'since' => 'Sejak 1975',
                    'since_tagline' => 'Menjaga cita rasa autentik',
                ]),
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy - Sate Simpangtiga',
                'content' => json_encode([
                    'subtitle' => 'Privacy & Security',
                    'description' => 'Kami berkomitmen untuk menjaga keamanan dan privasi seluruh pelanggan serta pengunjung website Sate Simpangtiga.',
                    'items' => [
                        [
                            'icon' => 'fa-user-shield',
                            'title' => 'Perlindungan Data Pelanggan',
                            'description' => 'Kami menjaga seluruh informasi pribadi pelanggan seperti nama, email, nomor telepon, dan data reservasi dengan standar keamanan yang baik untuk memastikan kenyamanan dan kepercayaan pelanggan.',
                        ],
                        [
                            'icon' => 'fa-database',
                            'title' => 'Penggunaan Informasi',
                            'description' => 'Informasi pelanggan hanya digunakan untuk kebutuhan reservasi, pelayanan restoran, konfirmasi pemesanan, dan peningkatan kualitas layanan Sate Simpangtiga.',
                        ],
                        [
                            'icon' => 'fa-lock',
                            'title' => 'Keamanan & Kerahasiaan',
                            'description' => 'Kami tidak menjual, membagikan, ataupun menyebarkan data pelanggan kepada pihak ketiga tanpa persetujuan pengguna, kecuali apabila diwajibkan oleh hukum yang berlaku.',
                        ],
                        [
                            'icon' => 'fa-cookie-bite',
                            'title' => 'Cookies & Aktivitas Website',
                            'description' => 'Website kami dapat menggunakan cookies untuk meningkatkan pengalaman pengguna, menganalisis aktivitas pengunjung, dan membantu optimalisasi layanan.',
                        ],
                        [
                            'icon' => 'fa-credit-card',
                            'title' => 'Keamanan Pembayaran',
                            'description' => 'Seluruh transaksi pembayaran digital seperti transfer bank dan QRIS diproses melalui sistem pembayaran yang aman dan terpercaya untuk menjaga keamanan transaksi pelanggan.',
                        ],
                        [
                            'icon' => 'fa-file-contract',
                            'title' => 'Persetujuan Pengguna',
                            'description' => 'Dengan menggunakan website dan layanan Sate Simpangtiga, pengguna dianggap telah membaca, memahami, dan menyetujui kebijakan privasi yang berlaku.',
                        ],
                    ],
                    'cta_text' => 'Keamanan & Kenyamanan Pelanggan Adalah Prioritas Kami',
                    'cta_description' => 'Kami selalu berupaya memberikan pengalaman reservasi, pemesanan, dan layanan restoran yang aman, nyaman, dan terpercaya.',
                    'cta_button' => 'Reservasi Sekarang',
                    'cta_route' => 'frontend.reservasi',
                ]),
            ],
            [
                'slug' => 'terms-conditions',
                'title' => 'Terms & Conditions - Sate Simpangtiga',
                'content' => json_encode([
                    'subtitle' => 'Terms & Service',
                    'description' => 'Dengan menggunakan website dan layanan Sate Simpangtiga, pelanggan dianggap telah memahami dan menyetujui seluruh syarat dan ketentuan yang berlaku.',
                    'items' => [
                        [
                            'icon' => 'fa-user-check',
                            'title' => 'Informasi Pelanggan',
                            'description' => 'Pelanggan wajib memberikan data yang benar dan valid saat melakukan reservasi, pemesanan menu, maupun penggunaan layanan website.',
                        ],
                        [
                            'icon' => 'fa-calendar-check',
                            'title' => 'Reservasi & Pemesanan',
                            'description' => 'Reservasi dapat dilakukan secara online melalui website maupun langsung di restoran. Sate Simpangtiga berhak membatalkan reservasi apabila terjadi pelanggaran ketentuan atau data tidak valid.',
                        ],
                        [
                            'icon' => 'fa-credit-card',
                            'title' => 'Metode Pembayaran',
                            'description' => 'Kami menerima pembayaran tunai (cash), transfer bank, dan QRIS. Seluruh pembayaran wajib diselesaikan sesuai nominal transaksi yang tertera pada sistem atau kasir restoran.',
                        ],
                        [
                            'icon' => 'fa-utensils',
                            'title' => 'Ketersediaan Menu',
                            'description' => 'Seluruh menu yang ditampilkan pada website dapat berubah sewaktu-waktu tergantung ketersediaan bahan baku dan operasional restoran.',
                        ],
                        [
                            'icon' => 'fa-ban',
                            'title' => 'Pembatalan & Penyalahgunaan',
                            'description' => 'Kami berhak menolak layanan atau membatalkan transaksi apabila ditemukan aktivitas yang merugikan restoran, penyalahgunaan sistem, atau tindakan yang melanggar hukum.',
                        ],
                        [
                            'icon' => 'fa-copyright',
                            'title' => 'Hak Cipta & Konten',
                            'description' => 'Seluruh desain, gambar, logo, video, dan konten pada website merupakan milik Sate Simpangtiga dan tidak diperbolehkan digunakan tanpa izin resmi.',
                        ],
                        [
                            'icon' => 'fa-rotate',
                            'title' => 'Perubahan Ketentuan',
                            'description' => 'Sate Simpangtiga berhak memperbarui syarat dan ketentuan layanan kapan saja tanpa pemberitahuan sebelumnya demi meningkatkan kualitas layanan.',
                        ],
                    ],
                    'cta_text' => 'Nikmati Pengalaman Kuliner Terbaik Bersama Kami',
                    'cta_description' => 'Dengan menggunakan layanan kami, pelanggan membantu menciptakan pengalaman makan yang nyaman, aman, dan menyenangkan bagi semua pengunjung.',
                    'cta_button' => 'Lihat Menu Kami',
                    'cta_route' => 'frontend.menu',
                ]),
            ],
        ];

        foreach ($information as $data) {
            Information::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
