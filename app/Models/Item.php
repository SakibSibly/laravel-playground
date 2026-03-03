<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'description',
        'price',
        'is_available',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
