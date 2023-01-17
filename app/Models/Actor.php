<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use HasFactory;

    public const ID_COLUMN = 'id';

    protected $fillable = ['name', 'license', 'link'];

    public function thumbnails()
    {
        return $this->hasMany(Thumbnail::class);
    }
}
