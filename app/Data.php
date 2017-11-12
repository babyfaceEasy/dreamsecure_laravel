<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;

class Data extends Model
{
    //
    protected $guarded = [];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}