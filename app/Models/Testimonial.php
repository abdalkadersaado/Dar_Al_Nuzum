<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable=['description','user_id'];

    protected $hidden=['created_at','updated_at','user_id'];

    protected $appends=['created_date'];

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function user(){

        return $this->belongsTo(User::class);
    }

}
