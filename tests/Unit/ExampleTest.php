<?php

namespace Tests\Unit;

use App\User;
use App\Notification;
use App\Organization;
use App\Server;
use App\ServerInfo;
use App\Sensor\Disks;
use App\Sensor\CPUtemperature;
use App\Sensor\USBtemperature;
use App\Sensor\Ifconfig;
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
        $this->assertTrue(true);
    }

    public function testClassInstance()
    {
        $class = Server::class;
        $server = new $class;
        $this->assertEquals("App\Server", get_class($server));
    }

    public function testRelations()
    {
        $user = new User();
        $user->name = "test";
        $user->email = "test@example.com";
        $user->password = "abc123";
        $user->save();

        $organization = new Organization();
        $organization->name = "Org";
        $organization->save();

        $organization->users()->save($user);

        $this->assertEquals(
            "Org",
            $user->organizations()->first()->name
        );
    }

    /**
     * @group ifconfig
     * @group sensors
     */
    public function testIfconfig()
    {
        $string = file_get_contents(__DIR__ . "/ifconfig");
        $sensor = new Ifconfig(new \App\Server());
        $interfaces = $sensor->parseIfconfig($string);
        $this->assertEquals(2, count($interfaces));
        $this->assertEquals("enp0s31f6", $interfaces[0]->name);
        $this->assertEquals("10.67.1.32", $interfaces[1]->address);
        $this->assertEquals(1074590056, $interfaces[1]->rx);
        $this->assertEquals(2074977132, $interfaces[1]->tx);
    }

    /**
     * Test parsing of ifconfig string from a ubuntu 18.04 server
     *
     * @group ifconfig
     * @group sensors
     */
    public function testIfconfig1804()
    {
        $string = file_get_contents(__DIR__ . "/ifconfig1804");
        $sensor = new Ifconfig(new \App\Server());
        $interfaces = $sensor->parseIfconfig($string);
        $this->assertEquals(2, count($interfaces));
        $this->assertEquals("eno1", $interfaces[0]->name);
        $this->assertEquals("172.20.0.8", $interfaces[1]->address);
        $this->assertEquals(185252610, $interfaces[1]->rx);
        $this->assertEquals(266912412, $interfaces[1]->tx);
    }

    /**
     * @group Disks
     */

    public function testDisksSensor()
    {
        $string = file_get_contents(__DIR__ . "/df");
        $sensor = new Disks(new Server());
        $disks = $sensor->parse($string);
        $this->assertEquals(2, count($disks));
        $this->assertEquals("/dev/sda1", $disks[0]->filesystem);
        $this->assertEquals(1128926648, $disks[0]->blocks);
    }

    public function testNetstatListening()
    {
        $string = file_get_contents(__DIR__ . "/netstat-tcp");
        $sensor = new \App\Sensor\ListeningPorts(new Server());
        $ports = $sensor->parse($string);
        $this->assertEquals(16, count($ports));
        $this->assertEquals("31933/cloud-backup-", $ports[4]->process);
        $this->assertEquals(1024, $ports[4]->port);
        $this->assertEquals("127.0.0.1", $ports[4]->bind);
    }

    public function testSsacli()
    {
        $string = file_get_contents(__DIR__ . "/ssacli");
        $sensor = new \App\Sensor\Ssacli(new Server());
        $disks = $sensor->parse($string);
        $this->assertEquals("OK", $disks[0]->status);
    }

    public function testPerccli()
    {
        $string = file_get_contents(__DIR__ . "/perccli");
        $sensor = new \App\Sensor\Perccli(new Server());
        $disks = $sensor->parse($string);
        $this->assertEquals("Onln", $disks[0]->status);
        $this->assertEquals("SSD", $disks[0]->type);
        $this->assertEquals("446.625 GB", $disks[0]->size);
    }

    public function testUpdates()
    {
        $sensor = new \App\Sensor\Updates(new \App\Server());

        $string1 = "6 packages can be updated.
2 updates are security updates.";
        $status = $sensor->parse($string1);
        $this->assertEquals(2, $status["security"]);

        $string2 = "1 package can be updated.
1 update is a security update.
";
        $status2 = $sensor->parse($string2);
        $this->assertEquals(1, $status2["security"]);
    }

    public function testMeminfo()
    {
        $string = file_get_contents(__DIR__ . "/meminfo");
        $mem_total = (new ServerInfo(null))->parseMeminfo($string);
        $this->assertEquals("15954328", $mem_total);
    }

    /**
     * @group cpuinfo
     */
    public function testCpuinfo()
    {
        $string = file_get_contents(__DIR__ . "/cpuinfo");
        $cpuinfo = (new ServerInfo(null))->parseCpuinfo($string);
        $this->assertEquals(8, $cpuinfo["threads"]);
        $this->assertEquals("Intel(R) Core(TM) i7-7700HQ CPU @ 2.80GHz", $cpuinfo["cpu"]);
    }

    /**
     * @group uptime
     */
    public function testUptime()
    {
        $string = "24439.45 190434.65";
        $uptime = (new ServerInfo(null))->parseUptime($string);
        $this->assertEquals("6 hours", $uptime);
    }

    public function testUUID()
    {
        $uuid = (new ServerInfo(null))->parseUUID(file_get_contents(__DIR__ . "/system"));
        $this->assertEquals("74F7C34C-2924-11B2-A85C-DC427DCA7109", $uuid);
    }

    /**
     * @group cpuinfo
     */
    public function testCpuinfoSingleCPU()
    {
        $string = file_get_contents(__DIR__ . "/cpuinfo_1cpu");
        $cpuinfo = (new ServerInfo(null))->parseCpuinfo($string);
        $this->assertEquals(1, $cpuinfo["threads"]);
        $this->assertEquals("Intel(R) Core(TM) i7-7700HQ CPU @ 2.80GHz", $cpuinfo["cpu"]);
    }

    public function testManufacturer()
    {
        $string = file_get_contents(__DIR__ . "/system");
        $manufacturer = (new ServerInfo(null))->parseManufacturer($string);
        $this->assertEquals("LENOVO", $manufacturer);
    }

    public function testProductName()
    {
        $string = file_get_contents(__DIR__ . "/system");
        $manufacturer = (new ServerInfo(null))->parseProductName($string);
        $this->assertEquals("20J60018MB", $manufacturer);
    }

    public function testClientVersion()
    {
        $server = new \App\Server();
        $client_version = new \App\Sensor\ClientVersion($server);
        $this->assertStringMatchesFormat('%d.%d.%d', $client_version->latestVersion());
    }

    /**
     * @group netstat
     */
    public function testNetstat()
    {
        $string = file_get_contents(__DIR__ . "/netstat");
        $server = new \App\Server();
        $netstat = new \App\Sensor\Netstat($server);
        $this->assertEquals(24004, $netstat->parse($string)->tcp_segments_retransmitted);
    }

    /**
     * @group status-change
     */
    public function testStatusChangeDetection()
    {
        $organization = new Organization();
        $organization->name = "ACME";
        $organization->save();

        $server = new \App\Server();
        $server->name = "My test server";
        $server->organization()->associate($organization);
        $server->save();

        $server_id = $server->id;

        $user = new User();
        $user->name = "Test";
        $user->email = "thibault.debatty@gmail.com";
        $user->password = "qmlskdj";
        $user->save();
        $organization->users()->attach($user->id);

        $this->assertEquals($server_id, \App\StatusChange::getLastChangeForServer(1)->server_id);

        // Insert a fake status change
        $change = new \App\StatusChange();
        $change->status = 155;
        $change->server_id = $server_id;
        $change->save();

        // Run change detection
        $change_detection_job = new \App\Jobs\StatusChangeDetection();
        $change_detection_job->detectChangeForServer($server);

        // Check if a new StatusChange was inserted in Mongo
        $last_change = \App\StatusChange::getLastChangeForServer($server_id);
        $records = $server->lastRecords1Day();
        $this->assertEquals(
            $server->status($records)->code(),
            $last_change->status
        );

        // Check if a notification were inserted
        $this->assertTrue(Notification::findForServer($server_id)->count() > 0);

        // Insert multiple status changes to simulate bouncing

        for ($i = 0; $i < 4; $i++) {
            $change = new \App\StatusChange();
            $change->status = 155;
            $change->server_id = $server_id;
            $change->save();

            // Run change detection
            $change_detection_job = new \App\Jobs\StatusChangeDetection();
            $change_detection_job->detectChangeForServer($server);
        }
    }
    /**
     * @group CPUtemp
     */
    public function testCPUtemp()
    {
        $string = file_get_contents(__DIR__ . "/sensors");
        $sensor = new CPUtemperature(new Server());
        $CPUTEMPS = $sensor->parse($string);
        $this->assertEquals(4, count($CPUTEMPS));
        $this->assertEquals("Core 3", $CPUTEMPS[3]->name);
        $this->assertEquals("34.0", $CPUTEMPS[3]->value);
    }
    /**
     * @group USBtemp
     */
    public function testTEMPer()
    {
        $string = file_get_contents(__DIR__ . "/TEMPer");
        $TEMPer = new USBtemperature(new Server());
        $USBTemp = $TEMPer->parse($string);
        $this->assertEquals("09", $USBTemp->part1);
        $this->assertEquals("47", $USBTemp->part2);
        $this->assertEquals("23", $USBTemp->temp[1]);
        $this->assertEquals("75", $USBTemp->temp[2]);
    }
    /**
     * @group multicpu
     */
    public function testmultiCPUtemp()
    {
        $string = file_get_contents(__DIR__ . "/sensors");
        $sensor = new CPUtemperature(new Server());
        $CPUTEMPS = $sensor->parseCPUtemperature($string);
        $this->assertEquals(4, count($CPUTEMPS));
        $this->assertEquals("Core 3", $CPUTEMPS[3]->name);
        $this->assertEquals("34.0", $CPUTEMPS[3]->corevalue);
    }
}
