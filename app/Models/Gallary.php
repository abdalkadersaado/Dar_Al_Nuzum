<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallary extends Model
{
    use HasFactory;

    protected $fillable= ['name','image','status','id'];

    protected $hidden=['updated_at','image'];

    protected $appends = ['img'];

    public function getCreatedAtAttribute($value)
    {
        $carbonDate = new Carbon($value);
        return $carbonDate->diffForHumans();
    }

    public function getImgAttribute()
    {
        return asset('') . $this->image;
    }
}
