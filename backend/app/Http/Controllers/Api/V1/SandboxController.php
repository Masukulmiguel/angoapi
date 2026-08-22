<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SandboxController extends Controller
{
    public function validateBi(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bi_number' => ['required', 'string', 'max:20'],
        ]);

        $bi = strtoupper(trim($validated["bi_number"]));

        if (!preg_match('/^\d{9}[A-Z]{2}\d{3}$/', $bi)) {
            return response()->json([
                "valid" => false,
                "error" => "BI invalido. Formato: 9 digitos + 2 letras + 3 digitos (ex: 001234567LA042).",
                "environment" => "sandbox",
            ], 422);
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders(["User-Agent" => "AngoAPI/1.0"])
                ->get("http://consulta.edgarsingui.ao/consultar/" . $bi);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data["name"]) && empty($data["error"])) {
                    return response()->json([
                        "valid" => true,
                        "data" => [
                            "bi_number" => $bi,
                            "full_name" => $data["name"] ?? null,
                            "date_of_birth" => $data["data_de_nascimento"] ?? null,
                            "address" => $data["endereco"] ?? null,
                            "gender" => null,
                            "nationality" => "Angolana",
                        ],
                        "source" => "Edgar Singui API",
                        "environment" => "sandbox",
                    ]);
                }
            }
        } catch (\Exception $e) {
        }

        $letters = substr($bi, 9, 2);
        return response()->json([
            "valid" => true,
            "data" => [
                "bi_number" => $bi,
                "full_name" => "Cidadao de Teste " . $letters,
                "date_of_birth" => "1990-01-15",
                "gender" => "M",
                "nationality" => "Angolana",
                "province_of_birth" => "Luanda",
            ],
            "source" => "sandbox_mock",
            "environment" => "sandbox",
        ]);
    }

    public function validateNif(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nif' => ['required', 'string', 'size:9'],
        ]);
        $nif = $validated['nif'];

        if (!ctype_digit($nif)) {
            return response()->json([
                "valid" => false,
                "error" => "NIF invalido. Deve conter exatamente 9 digitos.",
                "environment" => "sandbox",
            ], 422);
        }

        try {
            $portalUrl = 'https://portaldocontribuinte.minfin.gov.ao';

            $ch = curl_init($portalUrl . '/consultar-nif-do-contribuinte');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ]);
            $html = curl_exec($ch);
            curl_close($ch);

            if ($html && preg_match('/name=["\']javax\.faces\.ViewState["\'][^>]*value=["\']([^"\']+)["\']/', $html, $m)) {
                $viewState = $m[1];

                $params = http_build_query([
                    'javax.faces.partial.ajax' => 'true',
                    'javax.faces.source' => 'j_id_2x:j_id_34',
                    'javax.faces.partial.execute' => '@all',
                    'javax.faces.partial.render' => 'showpanelNIF',
                    'j_id_2x_SUBMIT' => '1',
                    'j_id_2x:txtNIFNumber' => $nif,
                    'j_id_2x:j_id_34' => 'j_id_2x:j_id_34',
                    'javax.faces.ViewState' => $viewState,
                ]);

                $ch2 = curl_init($portalUrl . '/consultar-headNifId-do-contribuinte');
                curl_setopt_array($ch2, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $params,
                    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                        'Faces-Request: partial/ajax',
                        'X-Requested-With: XMLHttpRequest',
                    ],
                ]);
                $xml = curl_exec($ch2);
                curl_close($ch2);

                if ($xml) {
                    $startMarker = '<update id="showpanelNIF"><![CDATA[';
                    $si = strpos($xml, $startMarker);
                    if ($si !== false) {
                        $htmlContent = substr($xml, $si + strlen($startMarker), strpos($xml, ']]></update>', $si) - $si - strlen($startMarker));
                        if (str_contains($htmlContent, 'panel-default-header')) {
                            $extractField = function ($label) use ($htmlContent) {
                                $escaped = preg_quote($label);
                                if (preg_match('/' . $escaped . '\s*<\/label>\s*<div[^>]*>\s*<label[^>]*>([^<]+)<\/label>/s', $htmlContent, $mm)) {
                                    return trim($mm[1]);
                                }
                                return null;
                            };
                            $nome = $extractField('Nome:');
                            if ($nome) {
                                return response()->json([
                                    "valid" => true,
                                    "data" => [
                                        "nif" => $extractField('NIF:'),
                                        "full_name" => $nome,
                                        "tipo" => $extractField('Tipo:'),
                                        "estado" => $extractField('Estado:'),
                                    ],
                                    "source" => "Portal AGT",
                                    "environment" => "sandbox",
                                ]);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
        }

        return response()->json([
            "valid" => true,
            "data" => [
                "nif" => $nif,
                "full_name" => "Empresa de Teste, Lda.",
                "tipo" => "Juridica",
                "estado" => "Activo",
                "actividade" => "Comercio Geral",
                "endereco" => "Rua da Missao, 123, Luanda",
            ],
            "source" => "sandbox_mock",
            "environment" => "sandbox",
        ]);
    }

    public function validatePhone(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
        ]);
        $phone = preg_replace('/\D/', '', $validated['phone']);

        if (!preg_match('/^9\d{8}$/', $phone)) {
            return response()->json([
                "valid" => false,
                "error" => "Numero invalido. Formato: 9XXXXXXXX (9 digitos).",
                "environment" => "sandbox",
            ], 422);
        }

        $carrier = (str_starts_with($phone, '94') || str_starts_with($phone, '95')) ? 'Movicel' : 'Unitel';

        return response()->json([
            "valid" => true,
            "data" => [
                "phone" => $phone,
                "carrier" => $carrier,
                "country" => "Angola",
                "country_code" => "+244",
                "type" => "mobile",
            ],
            "environment" => "sandbox",
        ]);
    }

    public function generateOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'length' => ['sometimes', 'integer', 'min:4', 'max:8'],
        ]);
        $length = $validated['length'] ?? 6;
        $code = str_pad((string) random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
        $token = Str::random(32);
        Cache::put("sandbox_otp:{$validated['phone']}:{$token}", $code, 300);

        return response()->json([
            "success" => true,
            "data" => [
                "token" => $token,
                "phone" => $validated['phone'],
                "expires_in" => 300,
                "code" => $code,
            ],
            "environment" => "sandbox",
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'token' => ['required', 'string'],
            'code' => ['required', 'string'],
        ]);
        $cacheKey = "sandbox_otp:{$validated['phone']}:{$validated['token']}";
        $storedCode = Cache::get($cacheKey);

        if (!$storedCode) {
            return response()->json(["valid" => false, "error" => "OTP expirado.", "environment" => "sandbox"], 422);
        }
        if ($storedCode !== $validated['code']) {
            return response()->json(["valid" => false, "error" => "Codigo OTP invalido.", "environment" => "sandbox"], 422);
        }

        Cache::forget($cacheKey);
        return response()->json([
            "valid" => true,
            "data" => ["phone" => $validated['phone'], "verified" => true],
            "environment" => "sandbox",
        ]);
    }

    public function sendSms(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => ['required', 'string'],
            'message' => ['required', 'string', 'max:1600'],
        ]);

        return response()->json([
            "success" => true,
            "data" => [
                "message_id" => "sms_" . Str::random(12),
                "to" => $validated['to'],
                "message" => $validated['message'],
                "sender_id" => "AngoAPI",
                "status" => "sent",
                "cost" => 0,
            ],
            "environment" => "sandbox",
        ]);
    }

    public function sendEmail(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => ['required', 'email'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        return response()->json([
            "success" => true,
            "data" => [
                "message_id" => "email_" . Str::random(12),
                "to" => $validated['to'],
                "from" => "noreply@angoapi.co.ao",
                "subject" => $validated['subject'],
                "status" => "sent",
            ],
            "environment" => "sandbox",
        ]);
    }

    public function generateQr(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => ['required', 'string', 'max:2048'],
            'size' => ['sometimes', 'integer', 'min:100', 'max:1000'],
        ]);
        $size = $validated['size'] ?? 300;
        $mc = 21;
        $ms = $size / $mc;
        $modules = [];

        for ($r = 0; $r < $mc; $r++) {
            for ($c = 0; $c < $mc; $c++) {
                $fp = ($r < 7 && $c < 7) || ($r < 7 && $c >= $mc - 7) || ($r >= $mc - 7 && $c < 7);
                if ($fp) {
                    $ib = $r === 0 || $r === 6 || $c === 0 || $c === 6 || $r === $mc - 7 || $r === $mc - 1 || $c === $mc - 7 || $c === $mc - 1;
                    $ii = ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4) || ($r >= 2 && $r <= 4 && $c >= $mc - 5 && $c <= $mc - 3) || ($r >= $mc - 5 && $r <= $mc - 3 && $c >= 2 && $c <= 4);
                    $f = $ib || $ii;
                } else {
                    $f = (crc32($validated['data'] . $r . $c) % 3) === 0;
                }
                if ($f) {
                    $modules[] = '<rect x="' . ($c * $ms) . '" y="' . ($r * $ms) . '" width="' . $ms . '" height="' . $ms . '" fill="#000"/>';
                }
            }
        }

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '">'
            . '<rect width="' . $size . '" height="' . $size . '" fill="#fff"/>'
            . implode('', $modules)
            . '</svg>';

        return response()->json([
            "success" => true,
            "data" => [
                "qr_code" => "data:image/svg+xml;base64," . base64_encode($svg),
                "format" => "svg",
                "size" => $size,
                "data" => $validated['data'],
            ],
            "environment" => "sandbox",
        ]);
    }
}
