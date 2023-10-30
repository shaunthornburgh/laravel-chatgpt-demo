<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatGPTController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): StreamedResponse
    {
        $userMessage = $request->query('userMessage');
        return response()->stream(function () use ($userMessage) {
            $stream = OpenAI::completions()->createStreamed([
                'model' => 'text-davinci-003',
                'prompt' => $userMessage,
                'max_tokens' => 1024,
            ]);

            foreach ($stream as $response) {
                Log::info($response->choices[0]->text);
                $text = $response->choices[0]->text;
                if (connection_aborted()) {
                    break;
                }

                echo "event: update\n";
                echo 'data: ' . $text;
                echo "\n\n";
                ob_flush();
                flush();
            }

            echo "event: update\n";
            echo 'data: <END_STREAM>';
            echo "\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
    }

//        $userMessage = $request->query('userMessage');
//
//        return response()->stream(function () use ($userMessage) {
//            $stream = Http::withHeaders([
//                'Content-Type' => 'application/json',
//                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY')
//            ])
//                ->withOptions(['stream' => true])
//                ->post('https://api.openai.com/v1/chat/completions', [
//                    'model' => 'gpt-3.5-turbo',
//                    'messages' => [
//                        [
//                            'role' => 'user',
//                            'content' => $userMessage
//                        ]
//                    ],
//                    'temperature' => 0,
//                    'max_tokens' => 2048
//                ])
//                ->toPsrResponse()
//                ->getBody();
//
//            Log::info($stream);
//
//            foreach ($stream as $response) {
//                $text = $response->choices[0]->text;
//                if (connection_aborted()) {
//                    break;
//                }
//
//                echo "event: update\n";
//                echo 'data: ' . $text;
//                echo "\n\n";
//                ob_flush();
//                flush();
//            }
//            echo "event: update\n";
//            echo 'data: <END_STREAMING_SSE>';
//            echo "\n\n";
//            ob_flush();
//            flush();
//        }, 200, [
//            'Cache-Control' => 'no-cache',
//            'X-Accel-Buffering' => 'no',
//            'Content-Type' => 'text/event-stream',
//        ]);
//    }
}
