<?php

namespace App\Models;

use App\Services\PornhubImages;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $fillable = ['url'];

    public function thumbnail()
    {
        return $this->belongsTo(Thumbnail::class);
    }
}
