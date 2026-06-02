<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\text;

#[Signature('chat')]
#[Description('Receive and respond to AI chat messages')]
class ChatCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $prompt = text('What do you want to say to the AI?', required: true);
        $this->info("You said: {$prompt}");

        $apiKey = (string) config('services.gemini.key');

        if ($apiKey === '') {
            $this->error('Missing Gemini API key.');

            return self::FAILURE;
        }

        $response = Http::baseUrl('https://generativelanguage.googleapis.com/v1beta')
            ->acceptJson()
            ->withHeaders([
                'X-Goog-Api-Key' => $apiKey,
            ])
            ->connectTimeout(10)
            ->timeout(30)
            ->retry([200, 500, 1000])
            ->post('/models/gemini-3-flash-preview:generateContent', [
                'systemInstruction' => [
                    'parts' => [
                        ['text' => 'You are a helpful assistant.'],
                    ],
                ],
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ])
            ->throw()
            ->json();

        $reply = data_get($response, 'candidates.0.content.parts.0.text');

        if (! is_string($reply) || $reply === '') {
            $this->error('Gemini did not return any text.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->line($reply);

        return self::SUCCESS;
    }
}
