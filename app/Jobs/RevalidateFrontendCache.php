<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RevalidateFrontendCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function handle()
    {
        $url = env('FRONTEND_URL') . '/api/revalidate';
        $secret = env('REVALIDATE_SECRET');

        if (!env('FRONTEND_URL') || !$secret) {
            return;
        }

        try {
            $response = Http::timeout(5)->post($url, [
                'secret' => $secret,
                'path' => $this->path
            ]);

            if (!$response->successful()) {
                Log::warning("Failed to revalidate Next.js cache for {$this->path}: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Error revalidating Next.js cache for {$this->path}: " . $e->getMessage());
        }
    }
}
