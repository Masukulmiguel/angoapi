<?php

namespace App\Http\Controllers\Api\V1\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function province(): JsonResponse
    {
        $provinces = [
            ['code' => 'BGO', 'name' => 'Bengo'],
            ['code' => 'BGU', 'name' => 'Benguela'],
            ['code' => 'BIE', 'name' => 'Bié'],
            ['code' => 'CAB', 'name' => 'Cabinda'],
            ['code' => 'CCU', 'name' => 'Cuando-Cubango'],
            ['code' => 'CNO', 'name' => 'Cuanza Norte'],
            ['code' => 'CUS', 'name' => 'Cuanza Sul'],
            ['code' => 'CNN', 'name' => 'Cunene'],
            ['code' => 'HUA', 'name' => 'Huambo'],
            ['code' => 'HUI', 'name' => 'Huíla'],
            ['code' => 'IGE', 'name' => 'Icolo e Bengo'],
            ['code' => 'LUA', 'name' => 'Luanda'],
            ['code' => 'LNO', 'name' => 'Lunda Norte'],
            ['code' => 'LSU', 'name' => 'Lunda Sul'],
            ['code' => 'MAL', 'name' => 'Malanje'],
            ['code' => 'MOX', 'name' => 'Moxico'],
            ['code' => 'NAM', 'name' => 'Namibe'],
            ['code' => 'UIG', 'name' => 'Uíge'],
            ['code' => 'ZAI', 'name' => 'Zaire'],
        ];

        return response()->json(['provinces' => $provinces]);
    }

    public function municipality(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'province_code' => ['required', 'string', 'size:3'],
        ]);

        $municipalities = [
            'BGO' => [
                ['code' => 'AIM', 'name' => 'Ambriz'],
                ['code' => 'DAN', 'name' => 'Dande'],
                ['code' => 'IBR', 'name' => 'Ibria'],
                ['code' => 'NCR', 'name' => 'N\'calândia'],
            ],
            'BGU' => [
                ['code' => 'BMD', 'name' => 'Benguela'],
                ['code' => 'CAL', 'name' => 'Catumbela'],
                ['code' => 'LOB', 'name' => 'Lobito'],
                ['code' => 'BAI', 'name' => 'Baía Farta'],
            ],
            'LUA' => [
                ['code' => 'ALU', 'name' => 'Alvalade'],
                ['code' => 'BEL', 'name' => 'Belas'],
                ['code' => 'CAC', 'name' => 'Cacuaco'],
                ['code' => 'CIT', 'name' => 'Cazenga'],
                ['code' => 'EMA', 'name' => 'Icolo e Bengo'],
                ['code' => 'KIL', 'name' => 'Kilamba Kiaxi'],
                ['code' => 'MRA', 'name' => 'Maianga'],
                ['code' => 'RAN', 'name' => 'Rangel'],
                ['code' => 'SML', 'name' => 'Samba'],
            ],
        ];

        $code = strtoupper($validated['province_code']);
        $result = $municipalities[$code] ?? [];

        return response()->json(['municipalities' => $result]);
    }
}
