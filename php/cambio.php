<?php
require_once "db.php"; // tu archivo de conexión PDO

try {

    // 1️⃣ Traer todos los registros viejos
    $stmt = $pdo->query("SELECT * FROM cantosTest");
    $cantosViejos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cantosViejos as $canto) {

        // =========================
        // LIMPIAR TÍTULO
        // =========================

        // Quitar etiquetas HTML
        $tituloLimpio = strip_tags($canto['title']);

        // Quitar número inicial (porque ya tenemos id_interno)
        $numero = $canto['id_interno'];
        $tituloLimpio = preg_replace('/^' . $numero . '\s+/', '', $tituloLimpio);
        $tituloLimpio = trim($tituloLimpio);


        // =========================
        // LIMPIAR LETRA
        // =========================

        $letra = $canto['estrofas'];

        // Convertir <br> a salto real
        $letra = str_replace(["<br>", "<br/>", "<br />"], "\n", $letra);

        // Quitar etiquetas restantes
        $letra = strip_tags($letra);

        // Limpiar espacios dobles
        $letra = preg_replace("/\n{3,}/", "\n\n", $letra);
        $letra = trim($letra);


        // =========================
        // INSERTAR EN NUEVA TABLA
        // =========================

        $insert = $pdo->prepare("
            INSERT INTO cantos 
            (categoria_id, numero, titulo, letra, bg_img)
            VALUES (?, ?, ?, ?, ?)
        ");

        $insert->execute([
            $canto['categoria_id'],
            $numero,
            $tituloLimpio,
            $letra,
            $canto['bg_img']
        ]);
    }

    echo "✅ Migración completada correctamente.";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
