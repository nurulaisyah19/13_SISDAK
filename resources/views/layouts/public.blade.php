{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'SISDAK - Kalender Kegiatan')</title>
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
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] rounded-full bg-blue-50 text-blue-700">
                        Kalender Publik
                    </span>
                </div>

                {{-- NAV LINKS --}}
                <div class="hidden md:flex items-center gap-6 text-sm font-semibold text-gray-600">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="{{ request()->routeIs('admin.dashboard') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                                Dashboard
                            </a>

                            <a href="{{ route('admin.kegiatan.index') }}"
                               class="{{ request()->routeIs('admin.kegiatan.*') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                                Kegiatan
                            </a>

                            <a href="{{ route('admin.ormawa-akun.index') }}"
                               class="{{ request()->routeIs('admin.ormawa-akun.*') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                                Akun ORMAWA
                            </a>
                        @elseif(auth()->user()->isOrmawa())
                            <a href="{{ route('ormawa.dashboard') }}"
                               class="{{ request()->routeIs('ormawa.dashboard') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                                Dashboard
                            </a>

                            <a href="{{ route('ormawa.kegiatan.index') }}"
                               class="{{ request()->routeIs('ormawa.kegiatan.*') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                                Kegiatan
                            </a>
                        @endif
                    @endauth

                    <a href="{{ route('kalender.public') }}"
                       class="{{ request()->routeIs('kalender.public') ? 'text-[#233B7B]' : 'hover:text-[#233B7B]' }}">
                        Kalender
                    </a>
                </div>

                {{-- USER / LOGIN / LOGOUT --}}
                <div class="flex items-center gap-3">
                    @auth
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-[11px] text-gray-500">
                                {{ auth()->user()->isAdmin() ? 'Admin' : 'ORMAWA' }}
                            </span>
                            <span class="text-xs font-semibold text-gray-800">
                                {{ auth()->user()->username ?? auth()->user()->name }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="w-9 h-9 rounded-full bg-[#233B7B] text-white flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->username ?? 'US', 0, 2)) }}
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="text-xs text-red-500 hover:text-red-600 font-semibold">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-xs text-[#233B7B] font-semibold hover:underline">
                            Login
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 lg:px-6">
            @yield('content')
        </div>
    </main>

</body>
</html>
