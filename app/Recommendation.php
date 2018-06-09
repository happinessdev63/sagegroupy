<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $guarded = ['id'];

    public function owner(  )
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function freelancer()
    {
        return $this->belongsTo( User::class, 'to_user_id' );
    }
}
