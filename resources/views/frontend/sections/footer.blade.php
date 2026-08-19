<footer
    id="footer"
    class="relative overflow-hidden
    border-t border-orange-300/20
    bg-gradient-to-br
    from-[#FF7315]
    via-[#F05A0B]
    to-[#C63D08]">

    {{-- =========================================================
        BACKGROUND DECORATION
    ========================================================== --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden">

        {{-- Cream glow kiri --}}
        <div
            class="absolute -left-32 -top-24
            h-[420px] w-[420px]
            rounded-full
            bg-amber-100/25 blur-[140px]">
        </div>

        {{-- Amber glow kanan --}}
        <div
            class="absolute -right-32 bottom-[-180px]
            h-[520px] w-[520px]
            rounded-full
            bg-amber-300/20 blur-[150px]">
        </div>

        {{-- Dark orange glow tengah --}}
        <div
            class="absolute left-1/2 top-1/2
            h-96 w-96
            -translate-x-1/2 -translate-y-1/2
            rounded-full
            bg-red-900/10 blur-[130px]">
        </div>

        {{-- Subtle top shadow --}}
        <div
            class="absolute inset-x-0 top-0 h-28
            bg-gradient-to-b
            from-black/10 to-transparent">
        </div>

    </div>


    {{-- =========================================================
        FOOTER CONTENT
    ========================================================== --}}
    <div
        class="container-main relative z-10
        py-12 sm:py-14 lg:py-16">

        {{-- =====================================================
            MAIN FOOTER CARD
        ====================================================== --}}
        <div
            class="rounded-3xl
            border border-orange-100/20
            bg-gradient-to-br
            from-[#8E2D0D]/70
            via-[#9F320C]/65
            to-[#7A230A]/70
            p-5
            shadow-[0_35px_100px_rgba(103,32,4,0.35)]
            backdrop-blur-xl
            sm:p-7 lg:p-9">

            <div
                class="grid grid-cols-1 gap-10
                md:grid-cols-2
                lg:grid-cols-[1.1fr_1fr_0.75fr_1.15fr]
                lg:gap-8">

                {{-- =============================================
                    BRAND
                ============================================== --}}
                <div>

                    {{-- Logo and badge --}}
                    <div class="mb-5 flex items-center gap-3">

                        <img
                            src="{{ asset('images/logo/logo.png') }}"
                            alt="Logo Sate Simpang Tiga"
                            style="
                                display: block !important;
                                width: 64px !important;
                                max-width: 64px !important;
                                height: auto !important;
                                object-fit: contain !important;
                            "
                            class="drop-shadow-xl">

                        <span
                            class="inline-flex items-center gap-2
                            rounded-full
                            border border-orange-100/25
                            bg-orange-950/25
                            px-3 py-1.5
                            text-[9px] font-semibold uppercase
                            tracking-[0.16em]
                            text-orange-100">

                            <span
                                class="h-1.5 w-1.5
                                animate-pulse rounded-full
                                bg-amber-300">
                            </span>

                            {{ __('frontend.footer.brand.badge') }}

                        </span>

                    </div>


                    {{-- Title --}}
                    <h2
                        class="mb-4 text-2xl
                        font-bold leading-tight
                        text-white sm:text-3xl">

                        {{ __('frontend.footer.brand.title_white') }}

                        <span class="block text-amber-300">
                            {{ __('frontend.footer.brand.title_dark') }}
                        </span>

                    </h2>


                    {{-- Description --}}
                    <p
                        class="max-w-sm text-sm
                        leading-relaxed text-orange-100/75">

                        {{ __('frontend.footer.brand.description') }}

                    </p>


                    {{-- Operating hours --}}
                    <div
                        class="mt-5 inline-flex
                        items-center gap-3
                        rounded-xl
                        border border-orange-100/15
                        bg-orange-950/25
                        px-4 py-3
                        shadow-inner">

                        <span class="relative flex h-2.5 w-2.5">

                            <span
                                class="absolute inline-flex
                                h-full w-full animate-ping
                                rounded-full
                                bg-green-300 opacity-70">
                            </span>

                            <span
                                class="relative inline-flex
                                h-2.5 w-2.5
                                rounded-full bg-green-400">
                            </span>

                        </span>

                        <div>

                            <p
                                class="text-[9px] uppercase
                                tracking-[0.14em]
                                text-orange-200/60">

                                Jam Operasional

                            </p>

                            <p
                                class="mt-0.5 text-xs
                                font-semibold text-white">

                                Setiap hari, 11.00–23.00

                            </p>

                        </div>

                    </div>

                </div>


                {{-- =============================================
                    GOOGLE MAPS
                ============================================== --}}
                <div>

                    <h3
                        class="mb-4 flex items-center gap-2
                        text-base font-semibold
                        text-white sm:text-lg">

                        <span
                            class="flex h-8 w-8
                            items-center justify-center
                            rounded-lg bg-amber-300/15
                            text-amber-300">

                            <i class="fas fa-map-marker-alt text-xs"></i>

                        </span>

                        {{ __('frontend.footer.location.title') }}

                    </h3>

                    <div
                        class="group relative overflow-hidden
                        rounded-2xl
                        border border-orange-100/20
                        bg-orange-950/20
                        shadow-[0_15px_40px_rgba(77,23,3,0.25)]">

                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.1226353399134!2d113.64975941055611!3d-8.190399582096555!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6915b16ade4f7%3A0x67dfee97e6b4020e!2sSate%20Simpang%20Tiga%20Mangli%20Jember!5e0!3m2!1sid!2sid!4v1778102290206!5m2!1sid!2sid"
                            width="100%"
                            height="190"
                            style="border: 0;"
                            allowfullscreen
                            loading="lazy"
                            title="Lokasi Sate Simpang Tiga"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="block w-full
                            saturate-[0.85]
                            transition duration-500
                            group-hover:saturate-100">
                        </iframe>

                        <a
                            href="https://www.google.com/maps?ll=-8.190405,113.65234&z=15&t=m&hl=id&gl=ID&mapclient=embed&cid=7484963441891082766"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="absolute bottom-3 left-3
                            inline-flex items-center gap-2
                            rounded-lg
                            border border-white/10
                            bg-[#6E2109]/90
                            px-3 py-2
                            text-[10px] font-medium
                            text-white shadow-lg
                            backdrop-blur-md
                            transition duration-300
                            hover:-translate-y-0.5
                            hover:bg-orange-500
                            focus:outline-none
                            focus:ring-2
                            focus:ring-amber-300
                            focus:ring-offset-2
                            focus:ring-offset-orange-900">

                            <i class="fas fa-location-arrow"></i>

                            Buka di Google Maps

                        </a>

                    </div>

                </div>


                {{-- =============================================
                    INFORMATION
                ============================================== --}}
                <div>

                    <h3
                        class="mb-4 flex items-center gap-2
                        text-base font-semibold
                        text-white sm:text-lg">

                        <span
                            class="flex h-8 w-8
                            items-center justify-center
                            rounded-lg bg-amber-300/15
                            text-amber-300">

                            <i class="fas fa-circle-info text-xs"></i>

                        </span>

                        {{ __('frontend.footer.information.title') }}

                    </h3>

                    <nav aria-label="Informasi footer">

                        <ul class="space-y-1">

                            <li>
                                <a
                                    href="{{ route('frontend.faq') }}"
                                    class="group flex items-center
                                    justify-between gap-3
                                    rounded-lg px-3 py-2.5
                                    text-sm text-orange-100/70
                                    transition duration-300
                                    hover:bg-orange-100/10
                                    hover:text-white
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-amber-300">

                                    <span>
                                        {{ __('frontend.footer.information.faq') }}
                                    </span>

                                    <i
                                        class="fas fa-arrow-right
                                        text-[9px] text-amber-300
                                        opacity-0
                                        transition duration-300
                                        group-hover:translate-x-1
                                        group-hover:opacity-100">
                                    </i>

                                </a>
                            </li>

                            <li>
                                <a
                                    href="{{ route('frontend.about') }}"
                                    class="group flex items-center
                                    justify-between gap-3
                                    rounded-lg px-3 py-2.5
                                    text-sm text-orange-100/70
                                    transition duration-300
                                    hover:bg-orange-100/10
                                    hover:text-white
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-amber-300">

                                    <span>
                                        {{ __('frontend.footer.information.about') }}
                                    </span>

                                    <i
                                        class="fas fa-arrow-right
                                        text-[9px] text-amber-300
                                        opacity-0
                                        transition duration-300
                                        group-hover:translate-x-1
                                        group-hover:opacity-100">
                                    </i>

                                </a>
                            </li>

                            <li>
                                <a
                                    href="{{ route('frontend.privacy') }}"
                                    class="group flex items-center
                                    justify-between gap-3
                                    rounded-lg px-3 py-2.5
                                    text-sm text-orange-100/70
                                    transition duration-300
                                    hover:bg-orange-100/10
                                    hover:text-white
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-amber-300">

                                    <span>
                                        {{ __('frontend.footer.information.privacy') }}
                                    </span>

                                    <i
                                        class="fas fa-arrow-right
                                        text-[9px] text-amber-300
                                        opacity-0
                                        transition duration-300
                                        group-hover:translate-x-1
                                        group-hover:opacity-100">
                                    </i>

                                </a>
                            </li>

                            <li>
                                <a
                                    href="{{ route('frontend.terms') }}"
                                    class="group flex items-center
                                    justify-between gap-3
                                    rounded-lg px-3 py-2.5
                                    text-sm text-orange-100/70
                                    transition duration-300
                                    hover:bg-orange-100/10
                                    hover:text-white
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-amber-300">

                                    <span>
                                        {{ __('frontend.footer.information.terms') }}
                                    </span>

                                    <i
                                        class="fas fa-arrow-right
                                        text-[9px] text-amber-300
                                        opacity-0
                                        transition duration-300
                                        group-hover:translate-x-1
                                        group-hover:opacity-100">
                                    </i>

                                </a>
                            </li>

                        </ul>

                    </nav>

                </div>


                {{-- =============================================
                    CONTACT
                ============================================== --}}
                <div>

                    <h3
                        class="mb-4 flex items-center gap-2
                        text-base font-semibold
                        text-white sm:text-lg">

                        <span
                            class="flex h-8 w-8
                            items-center justify-center
                            rounded-lg bg-amber-300/15
                            text-amber-300">

                            <i class="fas fa-headset text-xs"></i>

                        </span>

                        {{ __('frontend.footer.contact.title') }}

                    </h3>


                    <div class="space-y-3">

                        {{-- Address --}}
                        <a
                            href="https://www.google.com/maps?ll=-8.190405,113.65234&z=15&t=m&hl=id&gl=ID&mapclient=embed&cid=7484963441891082766"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex items-start gap-3
                            rounded-xl
                            border border-orange-100/10
                            bg-orange-950/20 p-3
                            transition duration-300
                            hover:border-amber-300/40
                            hover:bg-orange-100/10
                            focus:outline-none
                            focus:ring-2
                            focus:ring-amber-300">

                            <span
                                class="flex h-9 w-9 shrink-0
                                items-center justify-center
                                rounded-lg
                                bg-amber-300/15
                                text-amber-300
                                transition duration-300
                                group-hover:bg-amber-300
                                group-hover:text-orange-950">

                                <i class="fas fa-map-marker-alt text-sm"></i>

                            </span>

                            <span
                                class="text-xs leading-relaxed
                                text-orange-100/70
                                transition
                                group-hover:text-white">

                                {{ __('frontend.footer.contact.address') }}

                            </span>

                        </a>


                        {{-- Email --}}
                        <a
                            href="mailto:satesimpangtiga@gmail.com"
                            class="group flex items-center gap-3
                            rounded-xl
                            border border-orange-100/10
                            bg-orange-950/20 p-3
                            transition duration-300
                            hover:border-amber-300/40
                            hover:bg-orange-100/10
                            focus:outline-none
                            focus:ring-2
                            focus:ring-amber-300">

                            <span
                                class="flex h-9 w-9 shrink-0
                                items-center justify-center
                                rounded-lg
                                bg-amber-300/15
                                text-amber-300
                                transition duration-300
                                group-hover:bg-amber-300
                                group-hover:text-orange-950">

                                <i class="fas fa-envelope text-sm"></i>

                            </span>

                            <span
                                class="min-w-0 truncate
                                text-xs text-orange-100/70
                                transition
                                group-hover:text-white">

                                satesimpangtiga@gmail.com

                            </span>

                        </a>


                        {{-- WhatsApp CTA --}}
                        <a
                            href="https://wa.me/6281234567890?text=Halo%20Sate%20Simpang%20Tiga,%20saya%20ingin%20bertanya."
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex items-center gap-3
                            rounded-xl
                            border border-green-300/25
                            bg-green-950/25 p-3
                            transition duration-300
                            hover:-translate-y-0.5
                            hover:border-green-300/60
                            hover:bg-green-900/35
                            hover:shadow-lg
                            hover:shadow-green-950/20
                            focus:outline-none
                            focus:ring-2
                            focus:ring-green-300">

                            <span
                                class="flex h-10 w-10 shrink-0
                                items-center justify-center
                                rounded-lg bg-green-500
                                text-lg text-white
                                shadow-lg shadow-green-950/20
                                transition duration-300
                                group-hover:rotate-6
                                group-hover:scale-110">

                                <i class="fab fa-whatsapp"></i>

                            </span>

                            <span class="min-w-0 flex-1">

                                <span
                                    class="block text-xs
                                    font-semibold text-white">

                                    Chat WhatsApp

                                </span>

                                <span
                                    class="mt-0.5 block
                                    text-[10px] text-green-100/55">

                                    Menu dan reservasi

                                </span>

                            </span>

                            <i
                                class="fas fa-arrow-up-right-from-square
                                text-[10px] text-green-300
                                transition duration-300
                                group-hover:translate-x-0.5
                                group-hover:-translate-y-0.5">
                            </i>

                        </a>


                        {{-- Instagram CTA --}}
                        <a
                            href="https://www.instagram.com/satesimpangtiga.jember?igsh=MTlpMnp1bWw0bDZqdA=="
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex items-center gap-3
                            rounded-xl
                            border border-pink-200/20
                            bg-gradient-to-r
                            from-purple-950/20
                            via-pink-950/20
                            to-orange-950/20
                            p-3 transition duration-300
                            hover:-translate-y-0.5
                            hover:border-pink-200/50
                            hover:from-purple-900/30
                            hover:via-pink-900/30
                            hover:to-orange-900/30
                            hover:shadow-lg
                            hover:shadow-pink-950/20
                            focus:outline-none
                            focus:ring-2
                            focus:ring-pink-300">

                            <span
                                class="flex h-10 w-10 shrink-0
                                items-center justify-center
                                rounded-lg
                                bg-gradient-to-br
                                from-purple-500
                                via-pink-500
                                to-orange-400
                                text-lg text-white
                                shadow-lg shadow-pink-950/20
                                transition duration-300
                                group-hover:-rotate-6
                                group-hover:scale-110">

                                <i class="fab fa-instagram"></i>

                            </span>

                            <span class="min-w-0 flex-1">

                                <span
                                    class="block text-xs
                                    font-semibold text-white">

                                    Instagram

                                </span>

                                <span
                                    class="mt-0.5 block
                                    text-[10px] text-pink-100/55">

                                    Promo dan update terbaru

                                </span>

                            </span>

                            <i
                                class="fas fa-arrow-up-right-from-square
                                text-[10px] text-pink-300
                                transition duration-300
                                group-hover:translate-x-0.5
                                group-hover:-translate-y-0.5">
                            </i>

                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            BOTTOM FOOTER
        ====================================================== --}}
        <div
            class="mt-7 flex flex-col
            items-center justify-between
            gap-5
            border-t border-orange-100/20
            pt-6 text-center
            md:flex-row md:text-left">

            {{-- Copyright --}}
            <p class="text-xs text-orange-100/70 sm:text-sm">
                {{ __('frontend.footer.copyright', ['year' => date('Y')]) }}
            </p>


            {{-- Social icons --}}
            <div class="flex items-center gap-2">

                {{-- Instagram --}}
                <a
                    href="https://www.instagram.com/satesimpangtiga.jember?igsh=MTlpMnp1bWw0bDZqdA=="
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Instagram Sate Simpang Tiga"
                    class="flex h-10 w-10
                    items-center justify-center
                    rounded-full
                    border border-orange-100/20
                    bg-orange-950/20
                    text-orange-100/70
                    transition duration-300
                    hover:-translate-y-1
                    hover:border-pink-200/60
                    hover:bg-pink-500/20
                    hover:text-white
                    focus:outline-none
                    focus:ring-2
                    focus:ring-pink-300">

                    <i class="fab fa-instagram"></i>

                </a>


                {{-- TikTok --}}
                <a
                    href="https://www.tiktok.com/@satesimpangtiga.jember?_r=1&_t=ZS-96DXpAHuZk1"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="TikTok Sate Simpang Tiga"
                    class="flex h-10 w-10
                    items-center justify-center
                    rounded-full
                    border border-orange-100/20
                    bg-orange-950/20
                    text-orange-100/70
                    transition duration-300
                    hover:-translate-y-1
                    hover:border-white/50
                    hover:bg-white/15
                    hover:text-white
                    focus:outline-none
                    focus:ring-2
                    focus:ring-white">

                    <i class="fab fa-tiktok"></i>

                </a>


                {{-- WhatsApp --}}
                <a
                    href="https://wa.me/6281234567890"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="WhatsApp Sate Simpang Tiga"
                    class="flex h-10 w-10
                    items-center justify-center
                    rounded-full
                    border border-orange-100/20
                    bg-orange-950/20
                    text-orange-100/70
                    transition duration-300
                    hover:-translate-y-1
                    hover:border-green-300/60
                    hover:bg-green-500/20
                    hover:text-white
                    focus:outline-none
                    focus:ring-2
                    focus:ring-green-300">

                    <i class="fab fa-whatsapp"></i>

                </a>

            </div>

        </div>

    </div>

</footer>
