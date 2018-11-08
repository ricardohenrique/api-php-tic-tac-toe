<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matches extends Model
{
    use SoftDeletes;

    protected $table = 'matches';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'next',
        'winner',
        'board'
     ];

    protected $hidden = [
         'created_at', 
         'updated_at', 
         'deleted_at'
    ];

    public function getBoardAttribute($value)
    {
        return json_decode($value);
    }

    public function setBoardAttribute($value)
    {
    	$this->attributes['board'] = json_encode($value);
    }
}
