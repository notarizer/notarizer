<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class Document extends Model
{
    /* @var array Disable Mass Assignment protection */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'updated_at'];

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

    /**
     * Determine if the current session owns this document
     *
     * @return bool
     */
    public function isOwner()
    {
        return in_array($this->sha256, session('owners') ?? []);
    }

    /**
     * Retrieve the human-readable file size
     * Thanks to: https://stackoverflow.com/a/14919494
     *
     * @param bool $si Whether to use SI units
     * @return string The human readable file size
     */
    public function humanFileSize($si = true) {
        $bytes = $this->size;
        $thresh = $si ? 1000 : 1024;

        if(abs($bytes) < $thresh) {
            return $bytes . ' B';
        }

        $units = $si
            ? ['kB','MB','GB','TB','PB','EB','ZB','YB']
            : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];

        $u = -1;

        do {
            $bytes /= $thresh;
            ++$u;
        } while(abs($bytes) >= $thresh && $u < sizeof($units) - 1);

        return number_format($bytes, 2) . ' ' . $units[$u];
    }
}
