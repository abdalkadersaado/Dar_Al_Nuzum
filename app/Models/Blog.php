<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $fillable=['title_en','title_ar','description_en','description_ar','image','user_id'];

    protected $hidden=['updated_at','image'];

    protected $appends=['img'];

    public function getCreatedAtAttribute($value){

        $created = new Carbon($value);
        return $created->diffForHumans();
    }

    public function getImgAttribute(){

        return asset('') . $this->image;
    }
    ######### Relation

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function scopeSelection($query){

        return $query->select(
             'id',
             'title_' . app()->getLocale() . " as title" ,
             'description_'. app()->getLocale() . " as description" ,
             'image',
             'created_at');
    }
}
