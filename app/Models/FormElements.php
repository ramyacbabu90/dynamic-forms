<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormElements extends Model
{
    protected $table = 'form_elements';
    public $timestamps = true;

    public function elementOptions()
    {
        return $this->hasMany(OptionValues::class, 'element_id', 'id');
    }
}
