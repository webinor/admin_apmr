<?php

namespace App\Models\HumanResource;

use App\Models\HumanResource\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    /**
     * Get all of the employees for the Role
     *
     * @return HasMany 
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class,);
    }
}
