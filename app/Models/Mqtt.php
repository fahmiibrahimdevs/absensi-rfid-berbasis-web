<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mqtt extends Model
{
    protected $table = "mqtt";
    protected $guarded = [];

    public $timestamps = false;
}
