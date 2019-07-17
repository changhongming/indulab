<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';
    const UPDATED_AT = null;
    const CREATED_AT = null;

    // public function db() {
    //     return $this->belongsTo('App\QuizDb', 'qd_id');
    // }
    // public function choices() {
    //     return $this->hasMany('App\QuizChoice', 'qq_id');
    // }
}
