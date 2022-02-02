<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallary extends Model
{
    use HasFactory;

    protected $fillable= ['name','image','status','id'];

    protected $hidden=['created_at','updated_at'];

    protected $appends=['created_date'];

    public function getCreatedDateAttribute(){

        return $this->created_at->diffForHumans();
    }
}
