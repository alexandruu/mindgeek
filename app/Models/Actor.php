<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use HasFactory;

    public const ID = 'id';
    public const ID_NUMERIC = 'id_numeric';
    public const CREATED_AT = 'created_at';

    public $incrementing = false;

    protected $fillable = ['name', 'license', 'link'];
    protected $with = ['thumbnails', 'thumbnails.urls'];

    public function thumbnails()
    {
        return $this->hasMany(Thumbnail::class);
    }
}
