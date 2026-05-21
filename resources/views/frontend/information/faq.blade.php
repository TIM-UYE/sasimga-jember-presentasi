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

                <i class="fas fa-circle-question"></i>

                Frequently Asked Questions

            </span>


            <h1 class="text-5xl md:text-6xl font-black mt-6 leading-tight">

                FAQ
                <span class="text-orange-500">
                    Sate Simpangtiga
                </span>

            </h1>


            <p class="text-gray-400 mt-6 max-w-2xl mx-auto text-lg leading-relaxed">

                Temukan informasi lengkap seputar reservasi, pembayaran,
                pemesanan, layanan restoran, dan pengalaman kuliner terbaik
                di Rumah Makan Sate Simpangtiga.

            </p>

        </div>



        {{-- FAQ LIST --}}
        <div class="space-y-6">

            {{-- FAQ 1 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-7 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-4">

                    <div class="h-12 w-12 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-calendar-check text-orange-400"></i>

                    </div>

                    <div>

                        <h2 class="text-2xl font-bold mb-3 text-white">
                            Apakah bisa melakukan reservasi online?
                        </h2>

                        <p class="text-gray-400 leading-relaxed">
                            Ya, pelanggan dapat melakukan reservasi meja secara online melalui halaman reservasi di website kami dengan mudah dan cepat.
                        </p>

                    </div>

                </div>

            </div>



            {{-- FAQ 2 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-7 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-4">

                    <div class="h-12 w-12 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-money-bill-wave text-orange-400"></i>

                    </div>

                    <div>

                        <h2 class="text-2xl font-bold mb-3 text-white">
                            Metode pembayaran apa saja yang tersedia?
                        </h2>

                        <p class="text-gray-400 leading-relaxed">
                            Kami menerima pembayaran tunai (cash), transfer bank, QRIS, dan berbagai metode pembayaran digital lainnya untuk memudahkan pelanggan.
                        </p>

                    </div>

                </div>

            </div>



            {{-- FAQ 3 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-7 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-4">

                    <div class="h-12 w-12 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-utensils text-orange-400"></i>

                    </div>

                    <div>

                        <h2 class="text-2xl font-bold mb-3 text-white">
                            Apakah bisa makan langsung di tempat?
                        </h2>

                        <p class="text-gray-400 leading-relaxed">
                            Tentu. Pelanggan dapat menikmati hidangan langsung di restoran dengan suasana nyaman dan pelayanan terbaik dari kami.
                        </p>

                    </div>

                </div>

            </div>



            {{-- FAQ 4 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-7 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-4">

                    <div class="h-12 w-12 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-motorcycle text-orange-400"></i>

                    </div>

                    <div>

                        <h2 class="text-2xl font-bold mb-3 text-white">
                            Apakah tersedia layanan pesan online atau takeaway?
                        </h2>

                        <p class="text-gray-400 leading-relaxed">
                            Ya, semua menu dapat dipesan secara online maupun dibawa pulang (takeaway) sesuai kebutuhan pelanggan.
                        </p>

                    </div>

                </div>

            </div>



            {{-- FAQ 5 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-7 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-4">

                    <div class="h-12 w-12 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-clock text-orange-400"></i>

                    </div>

                    <div>

                        <h2 class="text-2xl font-bold mb-3 text-white">
                            Jam operasional restoran?
                        </h2>

                        <p class="text-gray-400 leading-relaxed">
                            Sate Simpangtiga buka setiap hari mulai pukul 11.00 WIB hingga 23.00 WIB.
                        </p>

                    </div>

                </div>

            </div>



            {{-- FAQ 6 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-7 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-4">

                    <div class="h-12 w-12 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-users text-orange-400"></i>

                    </div>

                    <div>

                        <h2 class="text-2xl font-bold mb-3 text-white">
                            Apakah bisa reservasi untuk acara keluarga?
                        </h2>

                        <p class="text-gray-400 leading-relaxed">
                            Bisa. Kami melayani reservasi untuk acara keluarga, gathering, ulang tahun, dan acara spesial lainnya.
                        </p>

                    </div>

                </div>

            </div>



            {{-- FAQ 7 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-7 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-4">

                    <div class="h-12 w-12 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-car-side text-orange-400"></i>

                    </div>

                    <div>

                        <h2 class="text-2xl font-bold mb-3 text-white">
                            Apakah tersedia area parkir?
                        </h2>

                        <p class="text-gray-400 leading-relaxed">
                            Ya, tersedia area parkir yang luas dan aman untuk kendaraan motor maupun mobil pelanggan.
                        </p>

                    </div>

                </div>

            </div>



            {{-- FAQ 8 --}}
            <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-7 border border-white/10 hover:border-orange-500/30 transition duration-300">

                <div class="flex items-start gap-4">

                    <div class="h-12 w-12 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">

                        <i class="fas fa-star text-orange-400"></i>

                    </div>

                    <div>

                        <h2 class="text-2xl font-bold mb-3 text-white">
                            Apa menu favorit di Sate Simpangtiga?
                        </h2>

                        <p class="text-gray-400 leading-relaxed">
                            Menu favorit pelanggan kami adalah Sate Kambing, Sate Ayam, Gulai, dan berbagai menu khas Nusantara lainnya.
                        </p>

                    </div>

                </div>

            </div>

        </div>



        {{-- CONTACT BOX --}}
        <div class="mt-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-[2rem] p-10 text-center shadow-2xl shadow-orange-500/20">

            <h3 class="text-3xl font-black text-white mb-4">
                Masih punya pertanyaan?
            </h3>

            <p class="text-white/80 max-w-2xl mx-auto leading-relaxed mb-8">

                Tim kami siap membantu Anda untuk reservasi,
                informasi menu, maupun layanan lainnya.

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
