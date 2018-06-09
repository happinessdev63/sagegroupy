<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\User;

class View extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo( User::class);
    }

    public function job()
    {
        return $this->belongsTo( Job::class);
    }

    public function agency()
    {
        return $this->belongsTo( Agency::class );
    }

    public function viewer()
    {
        return $this->belongsTo( User::class, "viewer_id" );
    }
}
