<?php

namespace App\Providers;

use App\Models\MatchRequest;
use App\Models\Team;
use App\Models\Game;
use App\Policies\MatchRequestPolicy;
use App\Policies\TeamPolicy;
use App\Policies\GamePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        Game::class => GamePolicy::class,
        MatchRequest::class  => MatchRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
