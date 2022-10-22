<?php

namespace App\Jobs;

use App\Services\ProcessTestFileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportTestFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function handle(ProcessTestFileService $processTestFileService)
    {
        $processTestFileService->process($this->path);
    }
}
