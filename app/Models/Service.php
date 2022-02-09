<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;


    protected $fillable = ['name_en', 'name_ar', 'description_en', 'description_ar', 'image'];

    protected $hidden = ['updated_at', 'image'];

    protected $appends = ['img'];

    ##### Accessories
    public function getCreatedAtAttribute($value)
    {
        $carbonDate = new Carbon($value);
        return $carbonDate->diffForHumans();
    }

    public function getImgAttribute()
    {
        return asset('') . $this->image;
    }
    #############

    ############ Scope
    public function scopeSelection($query)
    {
        return $query->select('id', 'name_' . app()->getLocale() . ' as name', 'description_' . app()->getLocale() . ' as description', 'created_at', 'image');
    }

    ###############
}
