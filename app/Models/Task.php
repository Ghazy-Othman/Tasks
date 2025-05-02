<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    use HasFactory;

    //
    public $primaryKey = 'task_id';

    // 
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'priority',
        'status'
    ];

    //
    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::createFromDate($value)->format('m/d/y')
        );
    }

    //
    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::createFromDate($value)->format('m/d/y')
        );
    }
}
