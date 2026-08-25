<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'conversation_id' => ['sometimes', 'string'],
        ]);

        $apiKey = config('services.openai.api_key');

        if (!$apiKey) {
            return response()->json([
                'reply' => $this->getFallbackResponse($validated['message']),
                'model' => 'fallback',
                'conversation_id' => $validated['conversation_id'] ?? uniqid('chat_'),
            ]);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->getSystemPrompt(),
                    ],
                    [
                        'role' => 'user',
                        'content' => $validated['message'],
                    ],
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'Desculpe, não consegui processar a sua mensagem.';

                return response()->json([
                    'reply' => $reply,
                    'model' => 'gpt-4o-mini',
                    'conversation_id' => $validated['conversation_id'] ?? uniqid('chat_'),
                ]);
            }

            return response()->json([
                'reply' => $this->getFallbackResponse($validated['message']),
                'model' => 'fallback',
                'conversation_id' => $validated['conversation_id'] ?? uniqid('chat_'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'reply' => $this->getFallbackResponse($validated['message']),
                'model' => 'fallback',
                'conversation_id' => $validated['conversation_id'] ?? uniqid('chat_'),
            ]);
        }
    }

    private function getSystemPrompt(): string
    {
        return <<<'PROMPT'
Você é o assistente virtual da AngoAPI, uma plataforma de APIs para Angola. 
Responda em português angolano. Seja breve, educado e útil.

Sobre a AngoAPI:
- Plataforma de APIs para validação de documentos angolanos (BI, NIF)
- Planos disponíveis: BI (3.000 AOA/mês), NIF Empresa (5.000 AOA/mês), BI + NIF (10.000 AOA/mês)
- APIs disponíveis: Validação de NIF, Validação de BI, Verificação de Identidade, Localização, Telefone, OTP, SMS, Email
- Autenticação via API Key Bearer token
- Suporte: suporte@angoapi.com
- Endpoint base: https://api.angoapi.com/api/v1

Responda apenas sobre assuntos relacionados à AngoAPI e seus serviços.
PROMPT;
    }

    private function getFallbackResponse(string $message): string
    {
        $message = strtolower($message);

        if (str_contains($message, 'plano') || str_contains($message, 'preço') || str_contains($message, 'valor')) {
            return "Temos 3 planos:\n• BI: 3.000 AOA/mês (10.000 requisições)\n• NIF Empresa: 5.000 AOA/mês (20.000 requisições)\n• BI + NIF: 10.000 AOA/mês (50.000 requisições)\n\nVisite angoapi.com para mais detalhes.";
        }

        if (str_contains($message, 'api') || str_contains($message, 'endpoint') || str_contains($message, 'como usar')) {
            return "Para usar nossas APIs:\n1. Registe-se em angoapi.com\n2. Escolha um plano\n3. Gere sua API Key\n4. Use o endpoint: https://api.angoapi.com/api/v1/gateway/{serviço}\n5. Envie o header Authorization: Bearer {sua_api_key}\n\nConsulte nossa documentação completa em docs.angoapi.com.";
        }

        if (str_contains($message, 'suporte') || str_contains($message, 'ajuda') || str_contains($message, 'contato')) {
            return "Para suporte:\n• Email: suporte@angoapi.com\n• Horário: Seg-Sex, 8h-17h\n• Dashboard: angoapi.com/dashboard";
        }

        return "Obrigado pelo contacto! Para mais informações sobre a AngoAPI, visite angoapi.com ou contacte suporte@angoapi.com. Posso ajudar com informações sobre planos, APIs ou como começar a usar a plataforma.";
    }
}
