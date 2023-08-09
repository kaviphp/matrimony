<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(AdvertisementImage::class);
    }

    public function advertisementPages()
    {
        return $this->belongsToMany(
            AdvertisementPage::class,
            'advertisement_advertisement_page'
            )
            ->using(AdvertisementAdvertisementPage::class);
    }
}
