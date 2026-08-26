<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AngoAPI — Conectamos dados, serviços e aplicações</title>
    <meta name="description" content="Plataforma de APIs para o Ecossistema Digital Angolano. Integre serviços de validação e dados através de APIs simples e seguras.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @php
        $manifest = [];
        $manifestPath = public_path('build/manifest.json');
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
        }
    @endphp
    @if(isset($manifest['resources/js/app.js']))
        @php
            $entry = $manifest['resources/js/app.js'];
        @endphp
        @foreach($entry['css'] ?? [] as $css)
            <link rel="stylesheet" href="/build/{{ $css }}">
        @endforeach
    @endif
</head>
<body>
    <div id="app"></div>
    @if(isset($manifest['resources/js/app.js']))
        <script type="module" src="/build/{{ $manifest['resources/js/app.js']['file'] }}"></script>
    @endif
</body>
</html>
