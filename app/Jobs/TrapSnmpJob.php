<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ndum\Laravel\SnmpTrapServer; 
use App\TrapListener;
use App\TrapSnmp;

use App\Post;

// Using it directly in the code without the use operator won't.
    $posts = \App\Post::all();
class TrapSnmpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
 # default options
$options = [
    'ip' => '0.0.0.0',
    'port' => 162,
    'transport' => 'udp',
    'version' => null,
    'community' => null,
    'whitelist' => null,
    'timeout_connect' => 10,
];

$listener = new TrapListener(); 
$server = new SnmpTrapServer();
$server->prepare($listener, $options); 

$server->listen();
//dd("jhhhjhjs");
$trap=new TrapSnmp();
$trap->message=$listener->message;
$trap->ip=$listener->ipAddress;

    }
}
