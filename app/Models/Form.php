<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = 'forms';
    public $timestamps = true;

    public function formElements()
    {
      return $this->hasMany(FormElements::class, 'form_id');
    }
}
