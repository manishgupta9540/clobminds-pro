<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IpLogData extends Model
{
    protected $fillable = ['user_id','ip', 'user_agent', 'url', 'method','controller_action','parameters']; 
}
