<x-guest-layout>
    <div class="min-h-screen bg-[#233B7B] flex items-center justify-center px-4">
        {{-- CARD UTAMA --}}
        <div class="w-full max-w-5xl bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">

                {{-- KIRI: FOTO GEDUNG --}}
                <div class="relative h-64 md:h-full">
                    <img
                        src="{{ asset('images/mipa.jpg') }}"
                        alt="Gedung FMIPA"
                        class="w-full h-full object-cover"
                    >
                    <div class="absolute bottom-5 left-6">
                        <span class="text-white text-lg md:text-xl tracking-widest font-semibold"
                              style="font-family: 'Times New Roman', serif;">
                            SISDAK FMIPA UNILA
                        </span>
                    </div>
                </div>

                {{-- KANAN: FORM REGISTER --}}
                <div class="bg-white flex items-center">
                    <div class="flex w-full">
                        {{-- Garis vertikal navy --}}
                       
                        <div class="flex-1 px-8 md:px-10 py-10">
                            {{-- Judul --}}
                            <h1 class="text-xl md:text-2xl font-bold text-[#233B7B] leading-snug text-center">
                                Daftar Akun SISDAK<br>
                                ORMAWA FMIPA Unila
                            </h1>

                            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                                @csrf

                                {{-- NAMA LENGKAP --}}
                                <div>
                                    <label for="name"
                                           class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama lengkap
                                    </label>
                                    <div class="relative">
                                        <input
                                            id="name"
                                            name="name"
                                            type="text"
                                            autocomplete="name"
                                            value="{{ old('name') }}"
                                            required
                                            placeholder="Masukkan nama"
                                            class="block w-full rounded-full border border-[#233B7B] px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-1 focus:ring-[#233B7B] focus:border-[#233B7B]"
                                        >
                                        <span class="absolute inset-y-0 right-4 flex items-center text-[#233B7B]">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="1.8"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                                                <path d="M4 20c0-2.76 3.13-5 7-5s7 2.24 7 5"/>
                                            </svg>
                                        </span>
                                    </div>
                                    @error('name')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- EMAIL / USERNAME --}}
                                <div>
                                    <label for="username"
                                           class="block text-sm font-medium text-gray-700 mb-1">
                                        Email / Username
                                    </label>
                                    <div class="relative">
                                        <input
                                            id="username"
                                            name="username"
                                            type="text"
                                            autocomplete="username"
                                            value="{{ old('username') }}"
                                            required
                                            placeholder="Masukkan email institusi"
                                            class="block w-full rounded-full border border-[#233B7B] px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-1 focus:ring-[#233B7B] focus:border-[#233B7B]"
                                        >
                                        <span class="absolute inset-y-0 right-4 flex items-center text-[#233B7B]">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="1.8"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                                                <path d="M4 20c0-2.76 3.13-5 7-5s7 2.24 7 5"/>
                                            </svg>
                                        </span>
                                    </div>
                                    @error('username')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- PASSWORD --}}
                                <div>
                                    <label for="password"
                                           class="block text-sm font-medium text-gray-700 mb-1">
                                        Kata sandi
                                    </label>
                                    <div class="relative">
                                        <input
                                            id="password"
                                            name="password"
                                            type="password"
                                            autocomplete="new-password"
                                            required
                                            placeholder="Minimal 8 karakter"
                                            class="block w-full rounded-full border border-[#233B7B] px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-1 focus:ring-[#233B7B] focus:border-[#233B7B]"
                                        >
                                        <span class="absolute inset-y-0 right-4 flex items-center text-[#233B7B]">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="1.8"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="16" r="1"></circle>
                                                <path d="M17 7a5 5 0 0 0-9.9 1"/>
                                                <rect x="7" y="10" width="10" height="9" rx="2" ry="2"/>
                                            </svg>
                                        </span>
                                    </div>
                                    @error('password')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- KONFIRMASI PASSWORD --}}
                                <div>
                                    <label for="password_confirmation"
                                           class="block text-sm font-medium text-gray-700 mb-1">
                                        Konfirmasi kata sandi
                                    </label>
                                    <div class="relative">
                                        <input
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            type="password"
                                            autocomplete="new-password"
                                            required
                                            placeholder="Ulangi kata sandi"
                                            class="block w-full rounded-full border border-[#233B7B] px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-1 focus:ring-[#233B7B] focus:border-[#233B7B]"
                                        >
                                        <span class="absolute inset-y-0 right-4 flex items-center text-[#233B7B]">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="1.8"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="16" r="1"></circle>
                                                <path d="M17 7a5 5 0 0 0-9.9 1"/>
                                                <rect x="7" y="10" width="10" height="9" rx="2" ry="2"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                {{-- TOMBOL DAFTAR --}}
                                <div class="pt-2 space-y-3">
                                    <button type="submit"
                                            class="w-full inline-flex justify-center items-center rounded-full bg-[#233B7B] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#1b2e61] transition">
                                        Daftar
                                    </button>

                                    <p class="text-xs text-center text-gray-500">
                                        Sudah punya akun?
                                        <a href="{{ route('login') }}"
                                           class="text-[#233B7B] font-semibold hover:underline">
                                            Login
                                        </a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>
