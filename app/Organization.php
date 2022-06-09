<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Organization
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $dashboard_token
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Server[] $servers
 * @property-read int|null $servers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereDashboardToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Organization extends Model
{


    public function users()
    {
        return $this->belongsToMany("App\User");
    }

    public function servers()
    {
        return $this->hasMany("App\Server");
    }

    public function MonitoringSnmp()
    {
        return $this->hasMany("App\MonitoringSnmp");
    }
    public function PhysicalSensor()
    {
        return $this->hasMany("App\PhysicalSensor");
    }
    public function url() : string
    {
        return action('OrganizationController@show', ["organization" => $this]);
    }
}
