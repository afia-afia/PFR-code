<?php

namespace Tests\Unit;

use App\Sensor\DiskEvolution;
use App\Sensor\Partition;
use App\Sensor\PartitionDelta;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Description of DiskEvolutionTest
 *
 * @group diskevolution
 * @author tibo
 */

class DiskEvolutionTest extends TestCase
{

    use RefreshDatabase;

    public function testSlowEvolution()
    {
        $p_t0 = new Partition();
        $p_t0->filesystem = "test";
        $p_t0->blocks = 20;
        $p_t0->used = 10;
        $p_t0->time = time();

        $p_end = new Partition();
        $p_end->filesystem = "test";
        $p_end->blocks = 20;
        $p_end->used = 11;
        $p_end->time = time() + 24 * 3600;

        $delta = new PartitionDelta($p_t0, $p_end);

        // test the result is correct...
        $this->assertEquals("test", $delta->filesystem());
        $this->assertEquals(9 * 24 * 3600, $delta->timeUntillFull());

        $sensor = new DiskEvolution(new \App\Server());
        $this->assertEquals(\App\Status::OK, $sensor->computeStatusFromDeltas([$delta]));
    }

    public function testQuickFull()
    {
        $p_t0 = new Partition();
        $p_t0->filesystem = "test";
        $p_t0->blocks = 20;
        $p_t0->used = 10;
        $p_t0->time = time();

        $p_end = new Partition();
        $p_end->filesystem = "test";
        $p_end->blocks = 20;
        $p_end->used = 12;
        $p_end->time = time() + 24 * 3600;

        $delta = new PartitionDelta($p_t0, $p_end);

        // test the result is correct...
        $this->assertEquals("test", $delta->filesystem());
        $this->assertEquals(4 * 24 * 3600, $delta->timeUntillFull());

        $sensor = new DiskEvolution(new \App\Server());
        $this->assertEquals(\App\Status::WARNING, $sensor->computeStatusFromDeltas([$delta]));
    }
}
