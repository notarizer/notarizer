<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class Document extends Model
{
    /* @var array Disable Mass Assignment protection */
    protected $guarded = [];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'sha256';
    }

    /**
     * Get the path to the document
     *
     * @return Response
     */
    public function path()
    {
        return route('doc.show', $this);
    }
}
