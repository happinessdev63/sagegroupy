<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;
use \App\View;

class Job extends Model
{
    use MediableSage;

    protected $guarded = ['id'];
    protected $casts = [
        'public' => 'boolean',
        'public_files' => 'boolean',
        'invite_client' => 'boolean',
        'completed' => 'boolean',
    ];
    protected $dates = ['created_at','completed_at','updated_at'];
    protected $appends = ['short_description', 'end_date','start_date', 'nice_payment_terms'];

    public function user()
    {
        return $this->belongsTo( User::class, "client_id" );
    }

    public function freelancer()
    {
        return $this->belongsTo( User::class, "freelancer_id" );
    }

    public function agency()
    {
        return $this->belongsTo( Agency::class );
    }

    public function client() {
        return $this->belongsTo( User::class, "client_id" );
    }

    public function references()
    {
        return $this->hasMany( Reference::class )->with('client');
    }

    public function views()
    {
        return $this->hasMany( View::class );
    }

    public function invites()
    {
        return $this->hasMany( Invite::class );
    }

    public function notifications()
    {
        return $this->hasMany( Notification::class );
    }

    /**
     * Check if the job has a freelancer attached.
     * @return bool|mixed
     */
    public function hasFreelancer()
    {
        return !empty( $this->freelancer_id ) ? $this->freelancer_id : false;
    }

    /**
     * Check if the job has any references/feedback
     * @return bool|mixed
     */
    public function hasReferences()
    {
        return $this->references()->count() > 0;
    }

    /**
     * Check if the job has a client attached.
     * @return bool|mixed
     */
    public function hasClient()
    {
        return !empty( $this->client_id ) ? $this->client_id : false;
    }

    /* Check if the job has an agency attached */
    public function hasAgency()
    {
        return !empty( $this->agency_id ) ? $this->agency_id : false;
    }

    public function getStartDateAttribute()
    {
        if ( $this->created_at ) {
            return $this->created_at->diffForHumans();
        } else {
            return '';
        }

    }

    public function getEndDateAttribute(  )
    {
        if ($this->completed_at) {
            return $this->completed_at->diffForHumans();
        } else {
            return '';
        }

    }

    public function getShortDescriptionAttribute(  )
    {
        $desc = str_limit($this->description, 140);
        $desc = strip_tags($desc);
        $desc = html_entity_decode($desc);
        return $desc;
    }

    /**
     * Convert payment terms into readable string.
     * @return string
     */
    public function getNicePaymentTermsAttribute(  )
    {
        switch ($this->payment_terms) {
            case "milestone":
                return "Milestone Based";
            case "tbd":
                return "To Be Determined";
            case "after_completion":
                return "Payment After Job Completion";
            case "hourly_weekly":
                return "Hourly Rate, Paid Weekly";
            case "hourly_biweekly":
                return "Hourly Rate, Paid Bi-Weekly";
            case "salary":
                return "Salary";
        }

        //Default
        return "To Be Determined";
    }


}
