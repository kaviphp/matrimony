<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function advertisements()
    {
        return $this->belongsToMany(
            Advertisement::class,
            'advertisement_advertisement_page'
            )
            ->using(AdvertisementAdvertisementPage::class);
    }
}
