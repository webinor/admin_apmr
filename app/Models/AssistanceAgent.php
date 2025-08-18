<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistanceAgent extends Model
{
    use HasFactory;

    public function fullName(): string
    {
        return $this->first_name." ".$this->last_name;
    }
}
