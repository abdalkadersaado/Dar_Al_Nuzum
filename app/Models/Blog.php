<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable=['title','description','image','user_id'];

    protected $hidden=['created_at','updated_at'];

    protected $appends=['created_date'];

    public function getCreatedDateAttribute(){

        return $this->created_at->diffForHumans();
    }

    ######### Relation

    public function user(){

        return $this->belongsTo(User::class);
    }
}
