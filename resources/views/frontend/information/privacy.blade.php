@extends('frontend.layout.app')

@section('content')

<section class="relative bg-black text-white min-h-screen py-28 px-6 overflow-hidden">

    {{-- BACKGROUND EFFECT --}}
    <div class="absolute inset-0">

        <div class="absolute top-0 left-0 w-[30rem] h-[30rem] bg-orange-500/10 blur-3xl rounded-full"></div>

        <div class="absolute bottom-0 right-0 w-[35rem] h-[35rem] bg-orange-600/10 blur-3xl rounded-full"></div>

    </div>


    <div class="relative max-w-5xl mx-auto">

        {{-- HEADER --}}
        <div class="text-center mb-16">

            <span class="inline-flex items-center gap-2 bg-orange-500/10 border border-orange-500/20 text-orange-400 px-5 py-2 rounded-full text-sm font-semibold">

                <i class="fas fa-shield-halved"></i>

                Privacy & Security

            </span>


            <h1 class="text-5xl md:text-6xl font-black mt-6 leading-tight">

                Privacy
                <span class="text-orange-500">
                    Policy
                </span>

            </h1>


            <p class="text-gray-400 mt-6 max-w-3xl mx-auto text-lg leading-relaxed">

                Kami berkomitmen untuk menjaga keamanan dan privasi seluruh
                pelanggan serta pengunjung website Sate Simpangtiga.

            </p>

        </div>



        {{-- CONTENT --}}
        <div class="space-y-8">

            {{-- CARD 1 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-user-shield text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Perlindungan Data Pelanggan
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Kami menjaga seluruh informasi pribadi pelanggan seperti nama,
                            email, nomor telepon, dan data reservasi dengan standar keamanan
                            yang baik untuk memastikan kenyamanan dan kepercayaan pelanggan.
                        </p>

                    </div>

                </div>

            </div>



            {{-- CARD 2 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-database text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Penggunaan Informasi
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Informasi pelanggan hanya digunakan untuk kebutuhan reservasi,
                            pelayanan restoran, konfirmasi pemesanan, dan peningkatan kualitas layanan
                            Sate Simpangtiga.
                        </p>

                    </div>

                </div>

            </div>



            {{-- CARD 3 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-lock text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Keamanan & Kerahasiaan
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Kami tidak menjual, membagikan, ataupun menyebarkan data pelanggan
                            kepada pihak ketiga tanpa persetujuan pengguna, kecuali apabila
                            diwajibkan oleh hukum yang berlaku.
                        </p>

                    </div>

                </div>

            </div>



            {{-- CARD 4 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-cookie-bite text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Cookies & Aktivitas Website
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Website kami dapat menggunakan cookies untuk meningkatkan pengalaman
                            pengguna, menganalisis aktivitas pengunjung, dan membantu optimalisasi layanan.
                        </p>

                    </div>

                </div>

            </div>



            {{-- CARD 5 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-credit-card text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Keamanan Pembayaran
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Seluruh transaksi pembayaran digital seperti transfer bank dan QRIS
                            diproses melalui sistem pembayaran yang aman dan terpercaya untuk menjaga
                            keamanan transaksi pelanggan.
                        </p>

                    </div>

                </div>

            </div>



            {{-- CARD 6 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-5">

                    <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-file-contract text-orange-400 text-xl"></i>

                    </div>


                    <div>

                        <h2 class="text-2xl font-bold text-white mb-4">
                            Persetujuan Pengguna
                        </h2>

                        <p class="text-gray-400 leading-relaxed text-lg">
                            Dengan menggunakan website dan layanan Sate Simpangtiga,
                            pengguna dianggap telah membaca, memahami, dan menyetujui
                            kebijakan privasi yang berlaku.
                        </p>

                    </div>

                </div>

            </div>

        </div>



        {{-- CONTACT BOX --}}
        <div class="mt-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-[2rem] p-10 text-center shadow-2xl shadow-orange-500/20">

            <h3 class="text-3xl font-black text-white mb-4">
                Keamanan & Kenyamanan Pelanggan Adalah Prioritas Kami
            </h3>

            <p class="text-white/80 max-w-3xl mx-auto leading-relaxed mb-8 text-lg">

                Kami selalu berupaya memberikan pengalaman reservasi,
                pemesanan, dan layanan restoran yang aman, nyaman, dan terpercaya.

            </p>

            <a
                href="{{ route('frontend.reservasi') }}"
                class="inline-flex items-center gap-3 bg-white text-orange-600 px-8 py-4 rounded-full font-bold hover:scale-105 transition duration-300 shadow-xl"
            >

                <i class="fas fa-calendar-check"></i>

                Reservasi Sekarang

            </a>

        </div>

    </div>

</section>

@endsection
