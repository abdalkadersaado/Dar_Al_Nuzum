<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable=['name','image','profession'];
    public $timestamps = false;
    protected $hidden=['image','created_at','updated_at'];
    protected $appends=['img'];

    public function getImgAttribute()
    {
        return asset('') . $this->image;
    }
}
