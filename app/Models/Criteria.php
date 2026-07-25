<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $fillable = ['code', 'name', 'type', 'weight'];

    public function alternativeValues()
    {
        return $this->hasMany(AlternativeValue::class);
    }
}
