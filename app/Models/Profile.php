<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable=['image','description','country','user_id','phone'];

    protected $hidden=['created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
