<?php

namespace App\Models\Misc;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Search extends Model
{
    use HasFactory;

    /**
     * Get all of the search_results for the Search
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function search_results(): HasMany
    {
        return $this->hasMany(SearchResult::class);
    }

    /**
     * Get the invoice_line that owns the Search
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice_line(): BelongsTo
    {
        return $this->belongsTo(InvoiceLine::class);
    }

    /**
     * Get the user that owns the Search
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, );
    }


    public function get_results()
    {

        $search_results = $this->search_results->count();
        if ($search_results == 0 ) {
            return ["color"=>"danger" , "text"=>$search_results];
        }
        else{

            
            return ["color"=>"success" , "text"=>$search_results];
    
           
        }


        
        
    }
}
