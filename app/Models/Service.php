<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }
}
