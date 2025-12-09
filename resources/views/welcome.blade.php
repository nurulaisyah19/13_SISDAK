<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SISDAK FMIPA UNILA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#233B7B] flex items-center justify-center">
    <div class="max-w-5xl w-full bg-white/5 rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
        {{-- KIRI: Gambar --}}
        <div class="relative hidden md:block">
            <img
                src="{{ asset('images/mipa.jpg') }}"
                alt="Gedung FMIPA"
                class="w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="absolute bottom-6 left-6">
                <span class="text-white text-2xl tracking-widest"
                      style="font-family: 'Times New Roman', serif;">
                    SISDAK FMIPA UNILA
                </span>
            </div>
        </div>

        {{-- KANAN: Konten --}}
        <div class="flex flex-col items-center justify-center px-8 py-10 bg-white">
            <h1 class="text-xl md:text-2xl font-bold text-[#233B7B] text-center mb-4">
                Sistem Digitalisasi Administrasi<br>
                ORMAWA FMIPA Unila
            </h1>
            <p class="text-sm text-gray-600 text-center mb-8">
                Silakan login atau registrasi untuk mengakses sistem.
            </p>

            <div class="flex gap-4 w-full justify-center">
                <a href="{{ route('login') }}"
                   class="px-6 py-2.5 rounded-full bg-[#233B7B] text-white text-sm font-semibold shadow-md hover:bg-[#1b2e61] transition">
                    Login
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-6 py-2.5 rounded-full border border-[#233B7B] text-[#233B7B] text-sm font-semibold hover:bg-[#233B7B] hover:text-white transition">
                        Register
                    </a>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
