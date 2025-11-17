<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\EduspotConfig;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'context' => 'nullable|array',
        ]);

        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'error' => 'Gemini API key is missing. Contact administrator.',
            ], 500);
        }

        $userMessage = $request->input('message');
        $history = $request->input('context', []);

        // Build conversation history for Gemini format
        $contents = [];
        
        // System prompt as first user message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => EduspotConfig::systemPrompt()]]
        ];
        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => 'Baik, saya siap membantu sebagai Eduspot!']]
        ];
        
        // Few-shot examples
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => 'Apa jurusan di SMKN 4 Bogor?']]
        ];
        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => 'Ada empat: Teknik Otomotif (TO), Teknik Pengelasan & Fabrikasi Logam (TPL), Teknik Jaringan Komputer & Telekomunikasi (TJKT), dan Pengembangan Perangkat Lunak & Gim (PPLG). Mau bahas salah satunya?']]
        ];
        
        // Add conversation history
        foreach ($history as $turn) {
            if (isset($turn['role'], $turn['content'])) {
                $role = $turn['role'] === 'assistant' ? 'model' : 'user';
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => (string) $turn['content']]]
                ];
            }
        }
        
        // Add current user message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]]
        ];

        try {
            // Gemini API endpoint
            $model = config('services.gemini.model', 'gemini-2.5-flash');
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            $payload = [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 512,
                    'topP' => 0.95,
                ],
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($endpoint, $payload);

            if (!$response->successful()) {
                $status = $response->status();
                $body = $response->body();
                Log::warning('Gemini API error', [
                    'status' => $status,
                    'body' => $body,
                    'model' => $model,
                ]);
                // Pesan error yang lebih ramah untuk kasus umum
                if ($status === 400) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Kunci API Gemini tidak valid atau tidak diterima. Periksa GEMINI_API_KEY di .env.',
                    ], 502);
                }
                if ($status === 403) {
                    return response()->json([
                        'success' => false,
                        'error' => 'API key tidak memiliki akses. Pastikan Gemini API sudah diaktifkan di Google Cloud Console.',
                    ], 502);
                }
                return response()->json([
                    'success' => false,
                    'error' => 'Eduspot lagi sibuk/terputus koneksi. Coba lagi sebentar ya.',
                ], 502);
            }

            $data = $response->json();
            $answer = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if (!$answer) {
                Log::warning('Gemini empty response', ['data' => $data]);
                return response()->json([
                    'success' => false,
                    'error' => 'Respon AI kosong. Coba lagi.',
                ], 502);
            }

            return response()->json([
                'success' => true,
                'answer' => $answer,
            ]);
        } catch (\Throwable $e) {
            Log::error('Chatbot ask error', [ 'error' => $e->getMessage() ]);
            return response()->json([
                'success' => false,
                'error' => 'Eduspot lagi ada kendala. Coba beberapa saat lagi.',
            ], 500);
        }
    }
}


