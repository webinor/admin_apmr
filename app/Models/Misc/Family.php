<?php

namespace App\Models\Misc;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Family extends Model
{
    use HasFactory;

    public function get_name(){

        return Str::upper($this->name);
    }
}
