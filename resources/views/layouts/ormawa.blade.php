{{-- resources/views/layouts/ormawa.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>SISDAK ORMAWA FMIPA</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { background-color: #f5f7fb; }
    </style>
</head>
<body class="font-sans antialiased">

<header class="bg-white shadow border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="text-xl font-extrabold tracking-wide text-[#233B7B]">
            SISDAK FMIPA UNILA
        </div>

        <nav class="flex items-center gap-6 text-sm font-semibold text-gray-600">
            <a href="{{ route('ormawa.dashboard') }}"
               class="{{ request()->routeIs('ormawa.dashboard') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                Dashboard
            </a>
            <a href="{{ route('ormawa.kegiatan.index') }}"
               class="{{ request()->routeIs('ormawa.kegiatan.*') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                Kegiatan
            </a>
            <a href="{{ route('kalender.public') }}"
               class="hover:text-[#233B7B]">
                Kalender
            </a>
        </nav>

        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs text-gray-500">ORMAWA</p>
                <p class="text-sm font-semibold text-gray-800">
                    {{ auth()->user()->username ?? '' }}
                </p>
            </div>
            <div class="w-9 h-9 rounded-full bg-[#233B7B] text-white flex items-center justify-center text-sm font-bold">
                {{ strtoupper(substr(auth()->user()->username ?? 'OR', 0, 2)) }}
            </div>

            {{-- 🔴 Tombol Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="ml-2 text-xs px-3 py-1.5 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>

<main class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @yield('content')
    </div>
</main>

</body>
</html>
