<?php
$mapeo = [
    'himnos' => 1,
    'adoracion' => 2,
    'textos' => 3,
    'alabanza' => 4,
    'coritos' => 9,
    'cabañas' => 10,
    'yedid' => 8
];

$json = file_get_contents('../js/json/cantos-limpio.json');
$data = json_decode($json, true);

$resultado = [];
foreach ($data as $categoria => $cantos) {
    $categoria_id = $mapeo[$categoria] ?? null;

    if (!$categoria_id) {
        echo "ADVERTENCIA: Categoría '$categoria' no mapeada<br>";
        continue;
    }

    foreach ($cantos as $canto) {
        // Manejar tanto 'numero' como 'id'
        $numero = $canto['numero'] ?? $canto['id'] ?? null;

        $nuevo = [
            'categoria_id' => $categoria_id,
            'numero' => $numero,
            'titulo' => $canto['titulo'],
            'letra' => $canto['letra'],
            'bg_img' => $canto['bg_img']
        ];

        $resultado[] = $nuevo;
    }
}

file_put_contents('../js/json/cantos-final.json', json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "✅ JSON final creado<br>";
echo "📊 Total: " . count($resultado);
?>