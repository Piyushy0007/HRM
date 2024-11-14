<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $path = request()->path();
        $pathchunks = explode('/', $path);

        // dd(isset($pathchunks[0]) && $pathchunks[0] == 'api');

        if (isset($pathchunks[0]) && $pathchunks[0] == 'api') {
            Broadcast::routes(["middleware" => "auth:api"]);
        } else {
            Broadcast::routes(["middleware" => "web"]);
        }

        require base_path('routes/channels.php');
    }
}
