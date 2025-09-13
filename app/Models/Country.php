<?php

namespace App\Models;

use App\Traits\HasMedia;
use Database\Factories\CountryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends BaseModel
{
    use HasFactory;
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];


    protected $casts = [
        'active' => 'boolean'
    ];
    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }

}
