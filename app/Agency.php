<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use MediableSage;

    protected $guarded = [ 'id' ];
    protected $appends = [ 'avatar','short_description' ];

    /**
     * Users belonging to this agency
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(  )
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Freelancers belonging to this agency
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function freelancers()
    {
        return $this->belongsToMany( User::class );
    }

    /**
     * Jobs belonging to this agency
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs()
    {
        return $this->hasMany( Job::class );
    }

    /**
     * Reference jobs belonging to this agency
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function references()
    {
        return $this->hasMany( Reference::class );
    }

    public function views()
    {
        return $this->hasMany( View::class );
    }

    public function links()
    {
        return $this->hasMany( Link::class );
    }

    /**
     * The owner of this agency
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class,"owner_id");
    }

    public function getAvatarAttribute()
    {
        if ( $this->hasMedia( 'avatar' ) ) {
            return $this->firstMedia( 'avatar' )->getUrl();
        }

        return '/img/avatar.jpg';

    }

    public function getShortDescriptionAttribute()
    {
        $desc = str_limit( $this->description, 140 );
        $desc = strip_tags( $desc );
        $desc = html_entity_decode( $desc );
        return $desc;
    }

    public function filePath( $folder = "" )
    {
        $path = md5( "agency_" . $this->id );
        if ( !empty( $folder ) ) {
            $path .= "/" . $folder;
        }

        return $path;
    }

    /**
     * Helper function for returning nicely formatted media relations
     * @param bool $publicOnly
     * @param bool $featuredOnly
     * @return array
     */
    public function getFiles($publicOnly = false, $featuredOnly = false) {
        $mediaFiles = $this->getMedia( "files" );

        $files = [];
        foreach ($mediaFiles as $media) {
            if ($publicOnly && !$media->pivot->public == false) {
                continue;
            }

            if ($featuredOnly && !$media->pivot->featured == false) {
                continue;
            }

            $files[] = [
                'name'        => $media->pivot->name,
                'description' => $media->pivot->description,
                'public'      => $media->pivot->public,
                'featured'    => $media->pivot->featured,
                'id'          => $media->id,
                'path'        => $media->getDiskPath(),
                'url'         => $media->getUrl(),
                'is_image'    => $media->aggregate_type == "image"
            ];
        }

        return $files;
    }
}
