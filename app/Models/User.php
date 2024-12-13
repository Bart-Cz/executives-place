<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, UuidTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    protected $fillable = [
        'name',
        'email',
        'currency',
        'hourly_rate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    protected $hidden = [
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            // works both ways -> displays but also saves in db with 2 decimal places,
            // should be good enough for hourly rates but consider if more decimal places needed
            'hourly_rate' => 'decimal:2',
        ];
    }
}
