<?php
/**
 * @desc Extends the Plank\Mediable\Mediable trait to add descriptions and names to files.
 * @package
 */

namespace App;

use Plank\Mediable\Mediable;

trait MediableSage
{
    use Mediable {
        media as mediaOld;
        attachMedia as attachMediaOld;
    }

    public function media()
    {
        return $this->morphToMany( config( 'mediable.model' ), 'mediable' )
            ->withPivot( 'tag', 'order', 'name', 'description','public','featured' )
            ->orderBy( 'order' );
    }

    /**
     * Replace the existing media collection for the specified tag(s).
     * Also include extra pivot data ['description','name']
     * @param mixed $media
     * @param string|array $tags
     * @return void
     */
    public function syncMediaExtra( $media, $tags, $extra = [] )
    {
        $this->detachMediaTags( $tags );
        $this->attachMediaExtra( $media, $tags, $extra );
    }

    /**
     * Attach a media entity to the model with one or more tags.
     * @param mixed $media Either a string or numeric id, an array of ids, an instance of `Media` or an instance of `\Illuminate\Database\Eloquent\Collection`
     * @param string|array $tags One or more tags to define the relation
     * @return void
     */
    public function attachMediaExtra( $media, $tags, $extra = [] )
    {
        $tags = (array)$tags;
        $increments = $this->getOrderValueForTags( $tags );
        $ids = $this->extractIds( $media );

        foreach ($tags as $tag) {
            $attach = [];
            foreach ($ids as $id) {
                $attach[$id] = [
                    'tag'   => $tag,
                    'order' => ++$increments[$tag],
                    'description' => isset($extra[$id]['description']) ? $extra[$id]['description'] : 'No description set.',
                    'name' => isset($extra[$id]['name']) ? $extra[$id]['name'] : 'No name set.',
                    'public' => isset($extra[$id]['public']) ? $extra[$id]['public'] : false,
                    'featured' => isset($extra[$id]['featured']) ? $extra[$id]['featured'] : false,
                ];
            }
            $this->media()->attach( $attach );
        }

        $this->markMediaDirty( $tags );
    }
}
