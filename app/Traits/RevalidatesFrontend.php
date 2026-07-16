<?php

namespace App\Traits;

use App\Jobs\RevalidateFrontendCache;

trait RevalidatesFrontend
{
    public static function bootRevalidatesFrontend()
    {
        static::saved(function ($model) {
            $model->revalidate();
        });

        static::deleted(function ($model) {
            $model->revalidate();
        });
    }

    public function revalidate()
    {
        $paths = $this->getRevalidatePaths();

        foreach ($paths as $path) {
            dispatch(new RevalidateFrontendCache($path));
        }
    }

    /**
     * Define the paths that should be revalidated when this model changes.
     * Override this method in the model.
     *
     * @return array
     */
    public function getRevalidatePaths()
    {
        return ['/'];
    }
}
