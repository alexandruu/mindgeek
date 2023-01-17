<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'url_cache'];

    public function thumbnail()
    {
        return $this->belongsTo(Thumbnail::class);
    }
}
