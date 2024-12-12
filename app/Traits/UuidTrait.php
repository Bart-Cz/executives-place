<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait UuidTrait
{
    /**
     * @return void
     */
    protected static function bootUuidTrait(): void
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * @param string $uuid
     * @return Model|null
     */
    public static function findByUuid(string $uuid): ?Model
    {
        return static::where('uuid', '=', $uuid)->first();
    }
}
