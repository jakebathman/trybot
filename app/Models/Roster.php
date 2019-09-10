<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *      ESPN Fantasy Football roster
 */
class Roster extends Model
{
    protected $connection = 'fantasyfootball';

    protected $table = 'rosters';

    protected $fillable = ['leagueId', 'teamId', 'slotId', 'playerId', 'lockStatus'];

    protected $primaryKey = 'primary';
    public $incrementing  = true;

    public $timestamps = false;

    public function player()
    {
        return $this->hasOne('\App\Models\EspnAllPlayers', 'playerId', 'playerId');
    }

    public function team()
    {
        return $this->belongsTo('\App\Models\Team', 'teamId', 'teamId');
    }

    public static function boot()
    {
        parent::boot();
    }
}
