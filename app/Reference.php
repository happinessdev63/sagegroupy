<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $guarded = [ 'id' ];

    public function job()
    {
        return $this->belongsTo( Job::class );
    }

    public function agency()
    {
        return $this->belongsTo( Agency::class );
    }

    public function freelancer()
    {
        return $this->belongsTo( User::class, "freelancer_id" );
    }

    public function client()
    {
        return $this->belongsTo( User::class, "client_id" );
    }
}
