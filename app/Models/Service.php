<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable=['name','description','image'];

    protected $hidden =['updated_at','created_at'];

    protected $appends = ['created_date'];


    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
