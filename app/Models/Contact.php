<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable=['name','email','message','status','created_date'];

    public function getNameAttribute($value)
    {
       return ucwords($value);
    }

}
