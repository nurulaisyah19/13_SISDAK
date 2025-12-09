<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Ormawa;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration view.
     */
    public function create(): View
    {
        // kalau mau ORMAWA dipilih saat register, ambil list-nya
        $ormawas = Ormawa::orderBy('nama_ormawa')->get();

        return view('auth.register', compact('ormawas'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'username'  => $data['username'],
            'password'  => Hash::make($data['password']),
            'role'      => 'ormawa', // default semua yang register jadi ORMAWA
            'id_ormawa' => $data['id_ormawa'] ?? null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // redirect sesuai role
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('ormawa.dashboard'));
    }
}
