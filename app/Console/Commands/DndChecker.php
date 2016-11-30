<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DndJobRange;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\DndCheck;

use Auth;
class DndChecker extends Command 
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dnd:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks Lead DND Status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $job = null;

       $job = DB::select( DB::raw("SELECT * FROM jobs WHERE payload LIKE '%DndCheck%'"));
        if(!$job)
        {
            $dndJobRange = DndJobRange::get()->first();
            if(!$dndJobRange)
            {
                $dndJobRange = new DndJobRange();
                $dndJobRange->start_limit  = 0;
            }
            else if( $dndJobRange->last_step >= $dndJobRange->total)
            {
                    $dndJobRange->last_step = 0;
        
            }
            $dndJobRange->save();
            //$this->handle();
            $this->dispatch(new DndCheck(Auth::id()));
        }
    }
}


