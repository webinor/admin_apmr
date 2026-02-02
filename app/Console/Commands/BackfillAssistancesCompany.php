<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// app/Console/Commands/BackfillAssistancesCompany.php

class BackfillAssistancesCompany extends Command
{
    protected $signature = 'assistances:backfill-company';
    protected $description = 'Remplit assistances.company_id depuis ground_agents';

    public function handle()
    {
        // DB::statement("
        //     UPDATE assistances a
        //     JOIN ground_agents ga ON ga.id = a.ground_agent_id
        //     SET a.company_id = ga.company_id
        // ");

        // $this->info('assistances.company_id rempli avec succès');


    DB::transaction(function () {
        DB::statement("
            UPDATE assistances a
            JOIN ground_agents ga ON ga.id = a.ground_agent_id
            SET a.company_id = ga.company_id
            WHERE a.company_id IS NULL
        ");
    });

    $this->info('assistances.company_id rempli avec succès');
    }
}
