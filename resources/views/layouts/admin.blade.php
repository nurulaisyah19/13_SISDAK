{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'SISDAK - Panel Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- TOP NAV --}}
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 lg:px-6">
            <div class="flex items-center justify-between h-14">

                {{-- BRAND --}}
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold tracking-wide text-[#233B7B]">
                        SISDAK FMIPA UNILA
                    </span>
                </div>

                {{-- NAV LINKS --}}
                <div class="hidden md:flex items-center gap-6 text-sm font-semibold text-gray-600">
                    <a href="{{ route('admin.dashboard') }}"
                       class="{{ request()->routeIs('admin.dashboard') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('admin.kegiatan.index') }}"
                       class="{{ request()->routeIs('admin.kegiatan.*') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                        Data Kegiatan
                    </a>

                    <a href="{{ route('admin.laporan.index') }}"
                       class="{{ request()->routeIs('admin.laporan.*') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                        Laporan
                    </a>

                    <a href="{{ route('admin.ormawa-akun.index') }}"
                       class="{{ request()->routeIs('admin.ormawa-akun.*') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                        Akun ORMAWA
                    </a>

                    <a href="{{ route('kalender.public') }}"
                       class="{{ request()->routeIs('kalender.public') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                        Kalender
                    </a>
                </div>

                {{-- USER / LOGOUT --}}
                <div class="flex items-center gap-3">
                    @auth
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-[11px] text-gray-500">Admin</span>
                            <span class="text-xs font-semibold text-gray-800">
                                {{ auth()->user()->username ?? auth()->user()->name ?? 'admin' }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- Avatar bulat --}}
                            <div class="w-9 h-9 rounded-full bg-[#233B7B] text-white flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->username ?? 'AD', 0, 2)) }}
                            </div>

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="text-xs text-red-500 hover:text-red-600 font-semibold">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 lg:px-6">

            {{-- FLASH MESSAGE GLOBAL --}}
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-100">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-100">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</body>
</html>
