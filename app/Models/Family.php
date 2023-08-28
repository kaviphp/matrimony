<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class)->withTrashed();
    }

    public function family_status()
    {
        return $this->belongsTo(FamilyStatus::class, 'family_status_id')->withTrashed();
    }

    public function family_value()
    {
        return $this->belongsTo(FamilyValue::class, 'family_value_id')->withTrashed();
    }
}
