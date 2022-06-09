<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

/**
 * App\Notification
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int $server_id
 * @property string $change_id
 * @property string $type
 * @property-read \App\Server $server
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereChangeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Notification extends Model
{

    protected $dateFormat = 'U';


    public function server()
    {
        return $this->belongsTo('\App\Server');
    }

    public function saveAndSend()
    {
        $this->save();
        $change = $this->change();

        $mail_class = \App\Mail\StatusChanged::class;
        if ($this->type == "bouncing") {
            $mail_class = \App\Mail\StatusBouncing::class;
        }

        foreach ($this->server->organization->users as $user) {
            $mail = new $mail_class($change);
            Mail::to($user)->send($mail);
        }
    }

    public function change() : StatusChange
    {
        return StatusChange::find($this->change_id);
    }

    public static function findForServer(int $server_id, int $since = 0)
    {
        return self::where('server_id', $server_id)
                ->where('created_at', '>', $since);
    }
}
