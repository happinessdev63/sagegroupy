<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;
use \App\View;

class Link extends Model
{
    use MediableSage;

    protected $guarded = [ 'id' ];
    protected $dates = [ 'created_at', 'updated_at' ];
    protected $appends = [ 'short_description', 'start_date','screenshot','files','has_screenshot' ];

    public function user()
    {
        return $this->belongsTo( User::class, "client_id" );
    }
    public function agency()
    {
        return $this->belongsTo( Agency::class );
    }

    /* Check if the link has an agency attached */
    public function hasAgency()
    {
        return !empty( $this->agency_id ) ? $this->agency_id : false;
    }

    public function getHasScreenshotAttribute(  )
    {
        if (!$this->hasMedia('files')) {
            return false;
        }

       $file = $this->getScreenshotAttribute();
        if ($file['is_image']) {
            return true;
        }

        return false;
    }

    public function getScreenshotAttribute()
    {
        $media = $this->firstMedia('files');

        if (!$media) {
            return [];
        }

        $file = [
                'name'        => $media->pivot->name,
                'description' => $media->pivot->description,
                'id'          => $media->id,
                'path'        => $media->getDiskPath(),
                'url'         => $media->getUrl(),
                'is_image'    => $media->aggregate_type == "image"
            ];

        return $file;

    }

    public function getFilesAttribute()
    {
        $mediaItems = $this->getMedia( "files" );

        $files = [];
        foreach ($mediaItems as $media) {
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

    public function getStartDateAttribute()
    {
        if ( $this->created_at ) {
            return $this->created_at->diffForHumans();
        } else {
            return '';
        }

    }

    public function getShortDescriptionAttribute()
    {
        $desc = str_limit( $this->description, 140 );
        $desc = strip_tags( $desc );
        $desc = html_entity_decode( $desc );
        return $desc;
    }


}
