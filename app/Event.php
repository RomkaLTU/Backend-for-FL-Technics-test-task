<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = ['id'];

    /**
     * Change date format
     * @param $value
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = date('Y-m-d H:i:s', strtotime($value));
    }
}
