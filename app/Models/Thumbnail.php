<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['height', 'width', 'type'];

    public function actor()
    {
        return $this->belongsTo(Actor::class);
    }

    public function urls()
    {
        return $this->hasMany(Url::class);
    }
}
