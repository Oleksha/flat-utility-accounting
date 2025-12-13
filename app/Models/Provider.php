<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'site_url',
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }
}
