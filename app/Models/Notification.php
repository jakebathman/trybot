<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *      Tracking notifications sent for fantasy football events/actions
 */
class Notification extends Model
{
    protected $connection = 'fantasyfootball';

    protected $table = 'notifications';

    protected $fillable = ['leagueId', 'type', 'hash', 'isProcessed'];

    protected $primaryKey = 'primary';
    public $incrementing  = true;

    public $timestamps = false;


    public static function boot()
    {
        parent::boot();
    }
}
