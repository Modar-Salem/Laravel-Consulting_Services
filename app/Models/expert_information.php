<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expert_information extends Model
{
    use HasFactory;

    protected $table = 'expert_information' ;

    protected $fillable = [
        'expert_id' ,

        'consulting_type' ,

        'experience' ,

        'phone' ,

        'address' ,


        'fee' ,

        'days' ,

        'balance'

    ] ;
    public function User()
    {
        // belongsTo(RelatedModel, foreignKey = project_id, keyOnRelatedModel = id)
        return $this->belongsTo(User::class);
    }
}
