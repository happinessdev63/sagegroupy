<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;
use \App\View;

class GeneralReference extends Model
{
    use MediableSage;

    protected $guarded = [ 'id' ];
    protected $dates = [ 'created_at', 'updated_at' ];
    protected $appends = [ 'short_description','files','has_client','has_review','start_date' ];

    public function user()
    {
        return $this->belongsTo( User::class, "user_id" );
    }

    public function client()
    {
        return $this->belongsTo( User::class, "client_id" );
    }

    public function getStartDateAttribute()
    {
        if ( $this->created_at ) {
            return $this->created_at->diffForHumans();
        } else {
            return '';
        }

    }

    /**
     * Check if the reference has a client attached.
     * @return bool|mixed
     */
    public function getHasClientAttribute()
    {
        return !empty( $this->client_id ) ? $this->client_id : false;
    }

    /**
     * Check if the reference has a client reference review.
     * @return bool|mixed
     */
    public function getHasReviewAttribute()
    {
        return $this->getHasClientAttribute() && !empty($this->review);
    }

    public function getShortDescriptionAttribute()
    {
        $desc = str_limit( $this->description, 140 );
        $desc = strip_tags( $desc );
        $desc = html_entity_decode( $desc );
        return $desc;
    }

    public function getFilesAttribute(  )
    {
        $generalReferenceMedia = $this->getMedia( "files" );
        $files = [];
        foreach ($generalReferenceMedia as $media) {
            $files[] = [
                'name'        => $media->pivot->name,
                'description' => $media->pivot->description,
                'id'          => $media->id,
                'path'        => $media->getDiskPath(),
                'url'         => $media->getUrl(),
                'is_image'    => $media->aggregate_type == "image"
            ];
        }

        return $files;
    }

}
