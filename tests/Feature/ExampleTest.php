<?php

namespace Tests\Feature;

use App\Organization;
use App\Server;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->get('/')->assertStatus(200);
    }

    public function testRecord()
    {
        $organization = new Organization();
        $organization->name = "TEST";
        $organization->save();

        $server = new Server();
        $server->name = "srv01";
        $organization->servers()->save($server);

        $this->post('/api/record/' . $server->id, [])->assertStatus(403);
        $this->post('/api/record/' . $server->id, ["token" => "abc123"])->assertStatus(403);

        $data = [
            "token" => $server->token,
            "version" => "0.1.2",
            "uname" => "Linux think 4.15.0-24-generic #26~16.04.1-Ubuntu SMP Fri Jun 15 14:35:08 UTC 2018"
            . " x86_64 x86_64 x86_64 GNU/Linux",
            "loadavg" => "0.83 0.87 0.70 2/1747 25404",
            "reboot" => true,
             "disks" => "Filesystem      1K-blocks    Used  Available Use% Mounted on
udev             12238236       0   12238236   0% /dev
tmpfs             2451716  264052    2187664  11% /run
/dev/sda1      1128926648 6545484 1065011924   1% /
tmpfs            12258572       4   12258568   1% /dev/shm
tmpfs                5120       0       5120   0% /run/lock
tmpfs            12258572       0   12258572   0% /sys/fs/cgroup
tmpfs             2451716       0    2451716   0% /run/user/1000
172.20.0.4:/srv/nfs/cylab03 1729930240 94934016 1547097088   6% /mnt/nfs",
            "updates" => "
6 packages can be updated.
0 updates are security updates.

",
            ];

        $this->post('/api/record/' . $server->id, $data)->assertStatus(200);
    }
}
