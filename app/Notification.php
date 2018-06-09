<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['label','icon','nice_message','nice_title','nice_date','sender_type'];

    public function sender()
    {
        return $this->belongsTo( User::class, "from_user_id" );
    }

    public function receiver()
    {
        return $this->belongsTo( User::class, "to_user_id" );
    }

    public function fromAgency()
    {
        return $this->belongsTo( Agency::class, "from_agency_id" );
    }

    public function agency()
    {
        return $this->belongsTo( Agency::class );
    }

    public function job()
    {
        return $this->belongsTo( Job::class );
    }

    public function getNiceDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getNiceTitleAttribute()
    {
        if (!empty($this->title)) {
            return $this->title;
        }

        return "New Notification";
    }

    public function getNiceMessageAttribute()
    {
        if ( !empty( $this->message ) ) {
            return $this->message;
        }

        return "";
    }

    public function getLabelAttribute()
    {
        if (!empty($this->from_agency_id)) {
            return "Agency";
        }

        if (!empty($this->job_id)) {
            return "Job";
        }

        return "User";
    }

    public function getIconAttribute() {
        switch ($this->type) {
            case "user-message":
                return "chat";
                break;
            case "agency-invite":
            case "agency-invite-request":
                return "card_membership";
                break;
            case "job-invite":
            case "job-invite-request":
                return "card_membership";
                break;
            default:
                return "chat";
                break;
        }
    }

    public function getSenderTypeAttribute(  )
    {


        if ( !empty( $this->from_agency_id ) ) {
            return 'agency';
        }

        if ( !empty( $this->from_user_id ) && $this->sender->isAdmin() ) {
            return 'admin';
        }

        if ( !empty( $this->job_id ) && $this->to_user_id === $this->from_user_id) {
            return 'job';
        }

        if ( !empty( $this->from_user_id ) ) {
            return 'user';
        }



    }

    public function getRealSenderAttribute() {
        if (!empty($this->from_agency_id)) {
            return $this->agency;
        }

        if (!empty($this->from_user_id)) {
            return $this->sender;
        }
    }
}
