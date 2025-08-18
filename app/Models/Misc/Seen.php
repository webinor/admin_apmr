<?php

namespace App\Models\Misc;

use App\Models\Operations\Folder;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seen extends Model
{
    use HasFactory;


    /**
     * Get the parent seenable model (user or post).
     */
    public function seenable()
    {
        return $this->morphTo();
    }
    /**
     * Get the folder that owns the Seen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function folder_old(): BelongsTo
    {
        return $this->belongsTo(Folder::class,);
    }

     /**
     * Get the user that owns the Seen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,);
    }

     /**
     * Get the user that owns the Seen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class,);
    }

    
}
