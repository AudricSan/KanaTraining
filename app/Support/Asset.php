<?php

namespace App\Support;

class Asset
{
    /**
     * URL of a file of public/ with its modification time appended, so browsers
     * reload CSS / JS after a deployment instead of keeping a stale cached copy.
     */
    public static function versioned(string $path): string
    {
        $file = public_path($path);
        $version = is_file($file) ? filemtime($file) : time();

        return asset($path).'?v='.$version;
    }
}
