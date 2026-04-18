<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
         'email', 
        'roll_no',
        'class_models_id',
        'division_id',
        'gender',
        'phone'
    ];

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_models_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
