<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{

    protected $table = "mail_logs";
    protected $guarded = [ 'id' ];
    protected $appends = [ 'nice_type' ];

    public function sender()
    {
        return $this->belongsTo( User::class, "from_user_id" );
    }

    public function receiver()
    {
        return $this->belongsTo( User::class, "to_user_id" );
    }

    public function agency()
    {
        return $this->belongsTo( Agency::class );
    }

    public function job()
    {
        return $this->belongsTo( Job::class );
    }

    public function getNiceTypeAttribute() {
        return ucwords(str_replace("-"," ", $this->type));
    }
}
