<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulting extends Model
{
    use HasFactory;

    protected $table = 'consultings' ;

    protected $fillable = [
        'user_id' ,
        'expert_id',

        'counseling' ,

        'response',

        'day',
        'time'
    ] ;



}
