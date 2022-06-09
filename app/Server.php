<?php

namespace App;

use App\Mongo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * App\Server
 *
 * @property int $id
 * @property int $organization_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $token
 * @property string $read_token
 * @property-read \App\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|Server newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server query()
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereReadToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Server extends Model
{

    protected $fillable = ["token"];

    /**
     * Last record from this server (used for caching).
     * @var String
     */
    private $last_record = null;
    private $records_1day = null;
    private $info = null;

    private static $sensors = [
        
        \App\Sensor\LoadAvg::class,
        \App\Sensor\MemInfo::class,
        \App\Sensor\Ifconfig::class,
        \App\Sensor\Netstat::class,
        \App\Sensor\Reboot::class,
        \App\Sensor\ListeningPorts::class,   
        \App\Sensor\Disks::class,
        \App\Sensor\Inodes::class,
        \App\Sensor\Logs::class,
        \App\Sensor\Date::class,
        \App\Sensor\Heartbeat::class,
       \App\Sensor\ClientVersion::class,
       \App\Sensor\Updates::class,
       \App\Sensor\CPUtemperature::class
    ];

    public function __construct(array $attributes = array())
    {
        $attributes["token"] = str_random(32);
        parent::__construct($attributes);
    }

    public function organization()
    {
        return $this->belongsTo("App\Organization");
    }

    public function lastRecord()
    {
        if ($this->last_record == null) {
            $collection = Mongo::get()->monitoring->records;
            $this->last_record =  $collection->findOne(
                ["server_id" => $this->id],
                ["sort" => ["_id" => -1]]
            );
        }

        return $this->last_record;
    }

    /**
     * Get the last day of data.
     * @return array
     */
    public function lastRecords1Day() : array
    {
        if ($this->records_1day !== null) {
            return $this->records_1day;
        }

        $start = time() - 24 * 3600;

        $this->records_1day = Mongo::get()->monitoring->records->find([
                "server_id" => $this->id,
                "time" => ['$gte' => $start]])
                ->toArray();
        return $this->records_1day;
    }

    public function hasData() : bool
    {
        return $this->lastRecord() != null;
    }

    public function info() : ServerInfo
    {
        if (is_null($this->info)) {
            $this->info = new ServerInfo($this->lastRecord());
        }

        return $this->info;
    }

    /**
     *
     * @return \App\Status
     */
    public function status() : Status
    {
        return Status::max($this->statusArray());
    }

    public function statusArray()
    {
        $status_array = [];
        foreach ($this->getSensors() as $sensor) {
            $sensor_name = $sensor->id();
            try {
                $status_array[$sensor_name] = $sensor->status();
            } catch (\Exception $ex) {
                $status_array[$sensor_name] = Status::UNKNOWN;
                Log::error("Sensor $sensor_name failed : " . $ex->getTraceAsString());
            }
        }
        return $status_array;
    }

    public function getSensorsNOK()
    {
        $sensorsNOK = [];
        foreach ($this->getSensors() as $sensor) {
            if ($sensor->status()->code() > 0) {
                $sensorsNOK[] = $sensor;
            }
        }
        return $sensorsNOK;
    }

    public function getSensors()
    {
        $records = $this->lastRecords1Day();

        $sensors = [];
        foreach (self::$sensors as $sensor) {
            $sensors[] = new SensorWrapper(new $sensor($this), $records);
        }
        return $sensors;
    }

    public function getChanges($count = 10)
    {
        return StatusChange::getLastChangesForServer($this->id, $count);
    }

    public static function id($id) : Server
    {
        return self::where("id", $id)->first();
    }
}
