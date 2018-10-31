<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['question', 'image', 'modified', 'scope', 'algorithm', 'type', 'answer'];
}
