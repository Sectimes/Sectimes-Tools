<?php

namespace App\Listeners;

use App\Events\JobDoneEvent;
use App\Models\JobStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class JobDoneListener
{
    private static $counter = 1;

    private static $successCounter = 1;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\JobDoneEvent  $event
     * @return void
     */
    public function handle(JobDoneEvent $event)
    {
        // Parsel URL to get only the hostname and check if we can extract the hostname or not
        $parseUrl = parse_url($event->endpoint);
        if (isset($parseUrl['host'])) {
            $hostname = $parseUrl['host'] . "-" . self::$successCounter;
            self::$successCounter++;
        } else {
            $hostname = "default-hostname-" . self::$counter;
            self::$counter++;
        }
        $jobName = $hostname;
        

        // session(['jobDone' => true]);
        \Log::info('Job Done Event Handled Test');

        JobStatus::updateOrCreate(
            ['job_name' => $jobName],
            ['is_done' => true]
        );
    }
}
