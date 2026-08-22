<?php

namespace App\Http\Controllers\Api\V1\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => ['required', 'string', 'max:2048'],
            'size' => ['sometimes', 'integer', 'min:100', 'max:1000'],
            'format' => ['sometimes', 'string', 'in:svg,png'],
        ]);

        $size = $validated['size'] ?? 300;
        $format = $validated['format'] ?? 'svg';

        if ($format === 'svg') {
            $qrSvg = $this->generateSimpleSvg($validated['data'], $size);
            $dataUrl = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

            return response()->json([
                'success' => true,
                'qr_code' => $dataUrl,
                'format' => 'svg',
                'size' => $size,
                'data' => $validated['data'],
                'environment' => 'sandbox',
            ]);
        }

        $dataUrl = 'data:text/plain;base64,' . base64_encode($validated['data']);

        return response()->json([
            'success' => true,
            'qr_code' => $dataUrl,
            'format' => 'text',
            'size' => $size,
            'data' => $validated['data'],
            'environment' => 'sandbox',
        ]);
    }

    private function generateSimpleSvg(string $data, int $size): string
    {
        $moduleCount = 21;
        $moduleSize = $size / $moduleCount;

        $modules = [];
        for ($row = 0; $row < $moduleCount; $row++) {
            for ($col = 0; $col < $moduleCount; $col++) {
                $isFinderPattern = ($row < 7 && $col < 7) ||
                    ($row < 7 && $col >= $moduleCount - 7) ||
                    ($row >= $moduleCount - 7 && $col < 7);

                if ($isFinderPattern) {
                    $isBorder = $row === 0 || $row === 6 || $col === 0 || $col === 6 ||
                        $row === $moduleCount - 7 || $row === $moduleCount - 1 ||
                        $col === $moduleCount - 7 || $col === $moduleCount - 1;
                    $isInner = ($row >= 2 && $row <= 4 && $col >= 2 && $col <= 4) ||
                        ($row >= 2 && $row <= 4 && $col >= $moduleCount - 5 && $col <= $moduleCount - 3) ||
                        ($row >= $moduleCount - 5 && $row <= $moduleCount - 3 && $col >= 2 && $col <= 4);

                    $filled = $isBorder || $isInner;
                } else {
                    $hash = crc32($data . $row . $col);
                    $filled = ($hash % 3) === 0;
                }

                if ($filled) {
                    $x = $col * $moduleSize;
                    $y = $row * $moduleSize;
                    $modules[] = "<rect x=\"{$x}\" y=\"{$y}\" width=\"{$moduleSize}\" height=\"{$moduleSize}\" fill=\"#000\"/>";
                }
            }
        }

        $rects = implode("\n    ", $modules);

        return <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" width="{$size}" height="{$size}" viewBox="0 0 {$size} {$size}">
            <rect width="{$size}" height="{$size}" fill="#fff"/>
            {$rects}
        </svg>
        SVG;
    }
}
