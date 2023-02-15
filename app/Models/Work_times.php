<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_times extends Model
{
    use HasFactory;
    protected $table = 'work_times' ;

    protected $fillable = [
        'expert_id' ,
        'sunday' , 'monday' , 'tuesday' , 'wednesday' , 'thursday' , 'friday', 'saturday' ,
        'begin_time1' , "end_time1" ,
        'begin_time2' ,"end_time2" ,
        'begin_time3' , 'end_time3' ,
        'begin_time4' , 'end_time4' ,
        'begin_time5' , 'end_time5' ,
        'begin_time6' , 'end_time6' ,
        'begin_time7' , 'end_time7'
    ] ;
}
