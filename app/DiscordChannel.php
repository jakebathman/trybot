<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DiscordChannel extends Model
{
    protected $fillable = ['guild_id','channel_id','channel_name'];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];

    public function scopeShouldBeDeleted($query)
    {
        $cutoff = Carbon::now()->subDay()->toDateTimeString();
        
        return $query->where('created_at', '<=', $cutoff)->where('is_deleted', '=', 0);
    }

    public function scopeShouldNotBeDeleted($query)
    {
        $cutoff = Carbon::now()->subDay()->toDateTimeString();
        
        return $query->where('created_at', '>', $cutoff)->where('is_deleted', '=', 0);
    }
}
