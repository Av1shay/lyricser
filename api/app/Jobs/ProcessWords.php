<?php

namespace App\Jobs;

use App\Models\Song;
use App\Services\StringProcessor;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessWords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Song
     */
    protected $song;

    /**
     * Create a new job instance.
     *
     * @param Song $song
     */
    public function __construct(Song $song)
    {
        $this->song = $song;
    }

    /**
     * Execute the job.
     *
     * @param StringProcessor $processor
     * @return void
     * @throws Exception
     */
    public function handle(StringProcessor $processor)
    {
        $processor->processSongWords($this->song);
    }
}
