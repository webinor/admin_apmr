<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UMenu extends Model
{
    use HasFactory;


      /**
     * Get all of the submenus for the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submenus(): HasMany
    {
        return $this->hasMany(UMenu::class);
    }

    /**
     * Get the menu that owns the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(UMenu::class);
    }
}
