<?php

namespace App\Http\Models\Slack;

use Illuminate\Database\Eloquent\Model;

/**
 *  Model for Slack emoji
 */
class Emoji extends Model
{
    public $incrementing = false;

    public $timestamps = true;

    protected $connection = 'slack';

    protected $table = 'emoji';

    protected $fillable = ['name', 'url', 'aliasFor', 'isActive'];

    protected $primaryKey = 'name';

    protected $keyType = 'string';

    public static function boot()
    {
        parent::boot();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setInactive()
    {
        $this->isActive = false;
    }

    public function setActive()
    {
        $this->isActive = true;
    }

    public function isActive()
    {
        return $this->isActive == true;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setAlias($aliasFor)
    {
        $this->aliasFor = $aliasFor;
    }
}
