<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login user.
     *
     * - Validasi & autentikasi via LoginRequest
     * - Regenerate session (keamanan)
     * - Cek role user:
     *      admin   → /admin/dashboard
     *      ormawa  → /ormawa/dashboard
     * - Kalau suatu saat ada role lain → fallback ke '/'
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validasi + cek kredensial (diproses di LoginRequest)
        $request->authenticate();

        // Ganti session ID untuk keamanan (mencegah session fixation)
        $request->session()->regenerate();

        $user = $request->user();

        // Jika user admin → ke dashboard admin
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Jika user ormawa → ke dashboard ormawa
        if ($user->isOrmawa()) {
            return redirect()->intended(route('ormawa.dashboard'));
        }

        // Fallback kalau suatu saat ada role lain
        return redirect()->intended('/');
    }

    /**
     * Logout user & hapus session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
