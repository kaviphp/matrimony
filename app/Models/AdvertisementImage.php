<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Image;

class AdvertisementImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'url'
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
