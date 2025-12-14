<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Receipt extends Model
{
    protected $fillable = [
        'apartment_id',
        'period',
        'file_path',
        'original_name',
    ];

    protected $casts = [
        'period' => 'date',
    ];

    public function charges(): BelongsToMany
    {
        return $this->belongsToMany(Charge::class);
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }
}
