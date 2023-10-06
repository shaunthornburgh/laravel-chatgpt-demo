<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class ChatGPTController extends Controller
{
    public function index()
    {

    }
    
    public function store(Request $request)
    {
        $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer' . env('OPEN_AI_API_KEY')
        ])
            ->post('http://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $request->input('content')
                    ]
                ],
                'temperature' => 0,
                'max_tokens' => 2048
            ])->body();

        return Redirect::route('tasks.index')->with('success', 'Task created.');
    }
}
