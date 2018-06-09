<?php

namespace App;

class Media extends \Plank\Mediable\Media
{
    /**
     * Retrieve all associated models of given class.
     * @param  string $class FQCN
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function models( $class )
    {
        return $this->morphedByMany( $class, 'mediable' )->withPivot( 'tag', 'order', 'description', 'name' );
    }
}
