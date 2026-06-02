<?php

use Illuminate\Support\Facades\Http;

test('chat command sends the prompt to gemini and prints the reply', function () {
    config()->set('services.gemini.key', 'test-gemini-key');

    Http::preventStrayRequests();

    Http::fake([
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent' => Http::response([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => 'Gemini says hello back.'],
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    $this->artisan('chat')
        ->expectsQuestion('What do you want to say to the AI?', 'Hello')
        ->expectsOutputToContain('You said: Hello')
        ->expectsOutputToContain('Gemini says hello back.')
        ->assertExitCode(0);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent'
            && $request->hasHeader('X-Goog-Api-Key', 'test-gemini-key')
            && $request->data() === [
                'systemInstruction' => [
                    'parts' => [
                        ['text' => 'You are a helpful assistant.'],
                    ],
                ],
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Hello'],
                        ],
                    ],
                ],
            ];
    });
});
