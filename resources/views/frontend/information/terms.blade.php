@extends('frontend.layout.app')

@section('content')

<section class="relative bg-black text-white min-h-screen py-28 px-6 overflow-hidden">

    {{-- BACKGROUND EFFECT --}}
    <div class="absolute inset-0">

        <div class="absolute top-0 left-0 w-[28rem] h-[28rem] bg-orange-500/10 blur-3xl rounded-full"></div>

        <div class="absolute bottom-0 right-0 w-[35rem] h-[35rem] bg-orange-600/10 blur-3xl rounded-full"></div>

    </div>


    <div class="relative max-w-5xl mx-auto">

        {{-- HEADER --}}
        <div class="text-center mb-16">

            <span class="inline-flex items-center gap-2 bg-orange-500/10 border border-orange-500/20 text-orange-400 px-5 py-2 rounded-full text-sm font-semibold">

                <i class="fas fa-file-signature"></i>

                Terms & Service

            </span>


            <h1 class="text-5xl md:text-6xl font-black mt-6 leading-tight">

                Terms &
                <span class="text-orange-500">
                    Conditions
                </span>

            </h1>


            <p class="text-gray-400 mt-6 max-w-3xl mx-auto text-lg leading-relaxed">

                Dengan menggunakan website dan layanan Sate Simpangtiga,
                pelanggan dianggap telah memahami dan menyetujui seluruh syarat
                dan ketentuan yang berlaku.

            </p>

        </div>



        {{-- TERMS LIST --}}
        <div class="space-y-8">

            {{-- CARD 1 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-user-check text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Informasi Pelanggan
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Pelanggan wajib memberikan data yang benar dan valid
                            saat melakukan reservasi, pemesanan menu, maupun penggunaan layanan website.
                        </p>

                    </div>

                </div>

            </div>



            {{-- CARD 2 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-calendar-check text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Reservasi & Pemesanan
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Reservasi dapat dilakukan secara online melalui website
                            maupun langsung di restoran. Sate Simpangtiga berhak membatalkan
                            reservasi apabila terjadi pelanggaran ketentuan atau data tidak valid.
                        </p>

                    </div>

                </div>

            </div>



            {{-- CARD 3 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-credit-card text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Metode Pembayaran
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Kami menerima pembayaran tunai (cash), transfer bank,
                            dan QRIS. Seluruh pembayaran wajib diselesaikan sesuai nominal transaksi
                            yang tertera pada sistem atau kasir restoran.
                        </p>

                    </div>

                </div>

            </div>



            {{-- CARD 4 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-utensils text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Ketersediaan Menu
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Seluruh menu yang ditampilkan pada website dapat berubah sewaktu-waktu
                            tergantung ketersediaan bahan baku dan operasional restoran.
                        </p>

                    </div>

                </div>

            </div>



            {{-- CARD 5 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-ban text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Pembatalan & Penyalahgunaan
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Kami berhak menolak layanan atau membatalkan transaksi
                            apabila ditemukan aktivitas yang merugikan restoran,
                            penyalahgunaan sistem, atau tindakan yang melanggar hukum.
                        </p>

                    </div>

                </div>

            </div>



            {{-- CARD 6 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-copyright text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Hak Cipta & Konten
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Seluruh desain, gambar, logo, video, dan konten pada website
                            merupakan milik Sate Simpangtiga dan tidak diperbolehkan digunakan
                            tanpa izin resmi.
                        </p>

                    </div>

                </div>

            </div>



            {{-- CARD 7 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-rotate text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Perubahan Ketentuan
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Sate Simpangtiga berhak memperbarui syarat dan ketentuan
                            layanan kapan saja tanpa pemberitahuan sebelumnya demi meningkatkan kualitas layanan.
                        </p>

                    </div>

                </div>

            </div>

        </div>



        {{-- CTA --}}
        <div class="mt-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-[2rem] p-10 text-center shadow-2xl shadow-orange-500/20">

            <h3 class="text-3xl font-black text-white mb-4">
                Nikmati Pengalaman Kuliner Terbaik Bersama Kami
            </h3>

            <p class="text-white/80 max-w-3xl mx-auto leading-relaxed mb-8 text-lg">

                Dengan menggunakan layanan kami, pelanggan membantu menciptakan
                pengalaman makan yang nyaman, aman, dan menyenangkan bagi semua pengunjung.

            </p>

            <a
                href="{{ route('frontend.menu') }}"
                class="inline-flex items-center gap-3 bg-white text-orange-600 px-8 py-4 rounded-full font-bold hover:scale-105 transition duration-300 shadow-xl"
            >

                <i class="fas fa-utensils"></i>

                Lihat Menu Kami

            </a>

        </div>

    </div>

</section>

@endsection
