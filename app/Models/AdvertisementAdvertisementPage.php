<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AdvertisementAdvertisementPage extends Pivot
{
    use HasFactory;
    protected $table = 'advertisement_advertisement_page';
    public $timestamps = false;

    public static function fromRawAttributes($parent, $attributes, $table, $exists = false)
    {
        $model = new static;

        $model->setRawAttributes((array) $attributes, $exists);

        $model->setTable($table);

        $model->setRelation('parent', $parent);

        return $model;
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function advertisementPage()
    {
        return $this->belongsTo(AdvertisementPage::class);
    }
}
