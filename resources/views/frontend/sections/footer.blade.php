<footer class="relative bg-gradient-to-br from-orange-500 via-orange-600 to-amber-600 overflow-hidden">

    {{-- BACKGROUND GLOW --}}
    <div class="absolute inset-0 opacity-20 pointer-events-none">

        <div
            class="absolute top-0 left-[-20%] sm:left-0
            w-56 h-56 sm:w-80 sm:h-80 lg:w-96 lg:h-96
            bg-white rounded-full blur-3xl">
        </div>

        <div
            class="absolute bottom-0 right-[-25%] sm:right-0
            w-64 h-64 sm:w-96 sm:h-96 lg:w-[500px] lg:h-[500px]
            bg-amber-300 rounded-full blur-3xl">
        </div>

    </div>


    <div class="relative z-10 container-main py-12 sm:py-16 lg:py-24">

        {{-- TOP --}}
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-7 sm:gap-8 lg:gap-10
            bg-black/20 backdrop-blur-xl border border-white/10
            rounded-2xl sm:rounded-[32px]
            p-5 sm:p-8 lg:p-12
            shadow-xl lg:shadow-2xl">

            {{-- BRAND --}}
            <div>

                {{-- BADGE --}}
                <span
                    class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 rounded-full bg-black/20 text-white text-[10px] sm:text-xs font-medium tracking-[0.16em] sm:tracking-[0.2em] uppercase ring-1 ring-white/10 mb-4 sm:mb-6">

                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>

                    {{ __('frontend.footer.brand.badge') }}

                </span>


                {{-- TITLE --}}
                <h2 class="text-2xl sm:text-3xl font-bold text-white leading-tight mb-3 sm:mb-5">

                    {{ __('frontend.footer.brand.title_white') }}

                    <span class="block text-black">
                        {{ __('frontend.footer.brand.title_dark') }}
                    </span>

                </h2>


                {{-- DESC --}}
                <p class="text-black/80 leading-relaxed text-sm max-w-sm">

                    {{ __('frontend.footer.brand.description') }}

                </p>

            </div>



            {{-- GOOGLE MAPS --}}
            <div>

                <h3 class="text-lg sm:text-xl font-bold text-white mb-3 sm:mb-5">
                    {{ __('frontend.footer.location.title') }}
                </h3>

                <div class="rounded-xl sm:rounded-2xl overflow-hidden border border-white/10 shadow-lg sm:shadow-xl">

                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.1226353399134!2d113.64975941055611!3d-8.190399582096555!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6915b16ade4f7%3A0x67dfee97e6b4020e!2sSate%20Simpang%20Tiga%20Mangli%20Jember!5e0!3m2!1sid!2sid!4v1778102290206!5m2!1sid!2sid"
                        width="100%" height="180" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" class="sm:h-[220px]">
                    </iframe>

                </div>

            </div>



            {{-- INFORMATION --}}
            <div>

                <h3 class="text-lg sm:text-xl font-bold text-white mb-3 sm:mb-5">
                    {{ __('frontend.footer.information.title') }}
                </h3>

                <ul class="space-y-3 sm:space-y-4 text-sm">

                    <li>
                        <a href="{{ route('frontend.faq') }}"
                            class="text-black/80 hover:text-white transition-all duration-300">

                            {{ __('frontend.footer.information.faq') }}

                        </a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.about') }}"
                            class="text-black/80 hover:text-white transition-all duration-300">

                            {{ __('frontend.footer.information.about') }}

                        </a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.privacy') }}"
                            class="text-black/80 hover:text-white transition-all duration-300">

                            {{ __('frontend.footer.information.privacy') }}

                        </a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.terms') }}"
                            class="text-black/80 hover:text-white transition-all duration-300">

                            {{ __('frontend.footer.information.terms') }}

                        </a>
                    </li>

                </ul>

            </div>



            {{-- CONTACT --}}
            <div>

                <h3 class="text-lg sm:text-xl font-bold text-white mb-3 sm:mb-5">
                    {{ __('frontend.footer.contact.title') }}
                </h3>

                <div class="space-y-4 sm:space-y-5 text-sm">

                    {{-- ADDRESS --}}
                    <div class="flex items-start gap-3">

                        <div
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-black/20 flex items-center justify-center shrink-0 mt-0.5 sm:mt-1">

                            <i class="fas fa-map-marker-alt text-white text-sm sm:text-base"></i>

                        </div>

                        <div>

                            <p class="text-black/80 leading-relaxed">

                                {{ __('frontend.footer.contact.address') }}

                            </p>

                        </div>

                    </div>


                    {{-- PHONE --}}
                    <div class="flex items-center gap-3">

                        <div
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-black/20 flex items-center justify-center shrink-0">

                            <i class="fas fa-phone text-white text-sm sm:text-base"></i>

                        </div>

                        <div class="flex items-center min-h-9 sm:min-h-10">

                            <a href="https://wa.me/6281234567890" target="_blank"
                                class="text-black/80 hover:text-white transition duration-300 leading-none">

                                +62 812-3456-7890

                            </a>

                        </div>

                    </div>


                    {{-- EMAIL --}}
                    <div class="flex items-center gap-3">

                        <div
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-black/20 flex items-center justify-center shrink-0">

                            <i class="fas fa-envelope text-white text-sm sm:text-base"></i>

                        </div>

                        <div class="min-w-0">

                            <a href="mailto:satesimpangtiga@gmail.com"
                                class="block text-black/80 hover:text-white transition duration-300 break-words">

                                satesimpangtiga@gmail.com

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- BOTTOM --}}
        <div
            class="mt-6 sm:mt-10 flex flex-col md:flex-row items-center justify-between gap-4 text-xs sm:text-sm text-black/70 text-center md:text-left">

            {{-- COPYRIGHT --}}
            <p>
                {{ __('frontend.footer.copyright', ['year' => date('Y')]) }}
            </p>


            {{-- SOCIAL MEDIA --}}
            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-6 font-medium">

                {{-- INSTAGRAM --}}
                <a href="https://www.instagram.com/satesimpangtiga.jember?igsh=MTlpMnp1bWw0bDZqdA==" target="_blank"
                    class="flex items-center gap-2 hover:text-white transition duration-300">

                    <i class="fab fa-instagram"></i>

                    Instagram

                </a>


                {{-- TIKTOK --}}
                <a href="https://www.tiktok.com/@satesimpangtiga.jember?_r=1&_t=ZS-96DXpAHuZk1" target="_blank"
                    class="flex items-center gap-2 hover:text-white transition duration-300">

                    <i class="fab fa-tiktok"></i>

                    TikTok

                </a>


                {{-- WHATSAPP --}}
                <a href="https://wa.me/6281234567890" target="_blank"
                    class="flex items-center gap-2 hover:text-white transition duration-300">

                    <i class="fab fa-whatsapp"></i>

                    WhatsApp

                </a>

            </div>

        </div>

    </div>

</footer>
