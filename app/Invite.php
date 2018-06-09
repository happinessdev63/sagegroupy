<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    public function client()
    {
        return $this->belongsTo( User::class, "client_id" );
    }

    public function freelancer()
    {
        return $this->belongsTo( User::class, "freelancer_id" );
    }

    public function agency()
    {
        return $this->belongsTo( Agency::class, "agency_id" );
    }
}
