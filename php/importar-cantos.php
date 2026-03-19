<?php
require_once 'db.php';

set_time_limit(300); // 5 minutos para procesar todo

// Leer JSON final
$json = file_get_contents('../js/json/cantos-final.json');
$cantos = json_decode($json, true);

$insertados = 0;
$errores = 0;

try {
    // Preparar statement
    $stmt = $conexion->prepare("
        INSERT INTO cantos (categoria_id, numero, titulo, letra, bg_img, activo) 
        VALUES (?, ?, ?, ?, ?, 1)
    ");

    foreach ($cantos as $canto) {
        try {
            $stmt->execute([
                $canto['categoria_id'],
                $canto['numero'],
                $canto['titulo'],
                $canto['letra'],
                $canto['bg_img']
            ]);
            $insertados++;
        } catch (PDOException $e) {
            $errores++;
            echo "Error en canto #{$canto['numero']}: {$e->getMessage()}<br>";
        }
    }

    echo "<h2>✅ Importación completada</h2>";
    echo "📊 Cantos insertados: <strong>$insertados</strong><br>";
    echo "❌ Errores: <strong>$errores</strong><br>";

} catch (Exception $e) {
    echo "❌ Error fatal: " . $e->getMessage();
}
?>