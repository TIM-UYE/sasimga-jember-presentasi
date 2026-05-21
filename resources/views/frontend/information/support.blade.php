@extends('frontend.layout.app')

@section('content')

<section class="bg-black text-white min-h-screen py-24 px-6">

    <div class="max-w-5xl mx-auto">

        <h1 class="text-5xl font-bold text-orange-500 mb-6">
            Support
        </h1>

        <p class="text-gray-400 mb-10">
            Hubungi kami jika mengalami kendala atau membutuhkan bantuan.
        </p>

        <div class="grid md:grid-cols-2 gap-6">

            <div class="bg-zinc-900 rounded-3xl p-8 border border-white/10">

                <h2 class="text-2xl font-bold mb-4">
                    Customer Service
                </h2>

                <div class="space-y-3 text-gray-300">

                    <p>
                        📞 +62 812-3456-7890
                    </p>

                    <p>
                        ✉️ support@satesimpangtiga.com
                    </p>

                    <p>
                        📍 Blitar, Jawa Timur
                    </p>

                </div>

            </div>

            <div class="bg-zinc-900 rounded-3xl p-8 border border-white/10">

                <h2 class="text-2xl font-bold mb-4">
                    Jam Operasional
                </h2>

                <div class="space-y-3 text-gray-300">

                    <p>
                        Senin - Jumat : 09.00 - 21.00
                    </p>

                    <p>
                        Sabtu - Minggu : 10.00 - 22.00
                    </p>

                    <p>
                        Hari Libur Nasional tetap buka.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
