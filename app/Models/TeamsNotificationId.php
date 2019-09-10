<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *      ESPN Fantasy Football schedule item (i.e. matchup)
 */
class TeamsNotificationId extends Model
{
    protected $connection = 'fantasyfootball';

    protected $table = 'teamsNotificationIds';

    protected $fillable = ['leagueId', 'teamId', 'slackUserId'];

    protected $primaryKey = 'primary';

    public $timestamps = true;

    public function getTeamId()
    {
        return $this->teamId;
    }

    public static function boot()
    {
        parent::boot();
    }
}
