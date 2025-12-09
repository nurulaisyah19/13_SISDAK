<?php

namespace App\Providers;

use App\Models\Kegiatan;
use App\Models\Dokumen;
use App\Policies\KegiatanPolicy;
use App\Policies\DokumenPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Kegiatan::class => KegiatanPolicy::class,
        Dokumen::class  => DokumenPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
