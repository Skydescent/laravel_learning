<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ModelsCountReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $reportable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //новости, статьи, комментарии, теги, пользователей
        $reportable = config('reports.reportable');
        if( in_array($this->class, $reportable)) {
            echo "";
        }
    }
}
