<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class EspnAllPlayersLog extends Model
{
    protected $connection = 'fantasyfootball';

    protected $table = 'espnAllPlayersLog';

    protected $fillable = ['hash', 'playerIdD', 'proTeamIdD', 'percentOwnedD', 'percentStartedD', 'latestNewsTenWordsD', 'totalPointsD', 'currentPeriodProjectedPointsD', 'currentPeriodRealPointsD', 'positionRankD', 'positionD', 'eligibleSlotCategoryIdsD', 'rosterStatusD', 'healthStatusD', 'defaultPositionIdD', 'pvoRankD', 'droppable'];

    public $timestamps = false;
    protected $primaryKey = 'primary';
    public $incrementing  = true;

    public function nflTeam()
    {
        return $this->hasOne('\App\Models\NflTeam', 'proTeamId', 'proTeamId');
    }

    public static function boot()
    {
        parent::boot();
    }
}
