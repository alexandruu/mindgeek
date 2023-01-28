<?php

namespace App\Console\Commands;

use App\Events\ActorImported;
use App\Models\Actor;
use Illuminate\Console\Command;

class CacheActorsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actors:cache-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache images for actors';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Actor::chunk(10, function ($actors) {
            foreach ($actors as $actor) {
                ActorImported::dispatch($actor);
            }
        });

        return Command::SUCCESS;
    }
}
