<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// LEER EL JSON UNA SOLA VEZ AQUÍ
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? $data['action'] ?? '';

require_once 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    $db = $conexion;
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            handleGet($db, $action);
            break;

        case 'POST':
            handlePost($db, $action);
            break;

        case 'DELETE':
            handleDelete($db, $action);
            break;

        default:
            echo json_encode(['error' => 'Método no permitido']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

function handleGet($db, $action)
{
    switch ($action) {
        case 'getAll':
            $stmt = $db->query("SELECT * FROM cantos ORDER BY id DESC");
            $cantos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($cantos);
            break;

        case 'getOne':
            $id = $_GET['id'] ?? 0;
            $stmt = $db->prepare("SELECT * FROM cantos WHERE id = ?");
            $stmt->execute([$id]);
            $canto = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($canto ?: ['error' => 'Canto no encontrado']);
            break;

        case 'getCategorias':
            // ANTES (sin slug):
            $stmt = $db->query("SELECT id, nombre FROM categorias WHERE activa = 1 ORDER BY orden");

            // DESPUÉS (con slug):
            $stmt = $db->query("SELECT id, nombre, slug FROM categorias WHERE activa = 1 ORDER BY orden");

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'buscarCantos':

            $q = $_GET['q'] ?? '';

            if (strlen($q) < 3) {
                echo json_encode([]);
                exit;
            }

            $stmt = $db->prepare("
                SELECT c.id, c.titulo, c.numero, cat.slug as categoria
                FROM cantos c
                    JOIN categorias cat ON c.categoria_id = cat.id
                    WHERE MATCH(c.titulo) AGAINST(:q1 IN BOOLEAN MODE)
                LIMIT 10
                ");

            $stmt->execute([
                ':q1' => $q . '*' // 🔥 clave para predictivo
            ]);

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'getCantosPorCategoria':
            $slug = $_GET['slug'] ?? '';
            $stmt = $db->prepare("
                SELECT c.* FROM cantos c 
                JOIN categorias cat ON c.categoria_id = cat.id 
                WHERE cat.slug = ? AND c.activo = 1 
                ORDER BY c.numero
             ");
            $stmt->execute([$slug]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'getAllCategorias':
            $stmt = $db->query("SELECT * FROM categorias ORDER BY orden ASC");
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($categorias);
            break;

        case 'getOneCategoria':
            $id = $_GET['id'] ?? 0;
            $stmt = $db->prepare("SELECT * FROM categorias WHERE id = ?");
            $stmt->execute([$id]);
            $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($categoria ?: ['error' => 'Categoría no encontrada']);
            break;

        //Código agregado 6mar26, para añadir imagenes en la sección principal de bienvenida en la página
        case 'getAllHeroConfig':
            $stmt = $db->query("SELECT * FROM hero_config ORDER BY id DESC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'getOneHeroConfig':
            $id = $_GET['id'] ?? 0;
            $stmt = $db->prepare("SELECT * FROM hero_config WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC) ?: ['error' => 'Configuración no encontrada']);
            break;

        case 'getActiveHeroConfig':
            $stmt = $db->query("SELECT * FROM hero_config WHERE activa = 1 LIMIT 1");
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC) ?: ['error' => 'No hay configuración activa']);
            break;

        default:
            echo json_encode(['error' => 'Acción no válida']);

        // FIN Código agregado 6mar26, para añadir imagenes en la sección principal de bienvenida en la página
    }
}

function handlePost($db, $action)
{
    global $data;

    switch ($action) {
        case 'create':
            $stmt = $db->prepare("INSERT INTO cantos (numero, categoria_id, titulo, letra, bg_img) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['numero'],
                $data['categoria_id'],
                $data['titulo'],
                $data['letra'],
                $data['bg_img'] ?? null
            ]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
            break;

        case 'update':
            $stmt = $db->prepare("UPDATE cantos SET numero = ?, categoria_id = ?, titulo = ?, letra = ?, bg_img = ? WHERE id = ?");
            $stmt->execute([
                $data['numero'],
                $data['categoria_id'],
                $data['titulo'],
                $data['letra'],
                $data['bg_img'] ?? null,
                $data['id']
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'createCategoria':
            $stmt = $db->prepare("INSERT INTO categorias (nombre, slug, orden, activa) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $data['nombre'],
                $data['slug'],
                $data['orden'],
                $data['activa'] ?? 1
            ]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
            break;

        case 'updateCategoria':
            $stmt = $db->prepare("UPDATE categorias SET nombre = ?, slug = ?, orden = ?, activa = ? WHERE id = ?");
            $stmt->execute([
                $data['nombre'],
                $data['slug'],
                $data['orden'],
                $data['activa'] ?? 1,
                $data['id']
            ]);
            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['error' => 'Acción no válida']);





        //Código agregado 6mar26, para añadir imagenes en la sección principal de bienvenida en la página

        case 'createHeroConfig':
            $stmt = $db->prepare("INSERT INTO hero_config (nombre, titulo, subtitulo_h2, subtitulo_h4, referencia_h5, icono_titulo, icono_referencia, imagen_bg, activa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['nombre'],
                $data['titulo'],
                $data['subtitulo_h2'] ?? null,
                $data['subtitulo_h4'] ?? null,
                $data['referencia_h5'] ?? null,
                $data['icono_titulo'] ?? null,
                $data['icono_referencia'] ?? null,
                $data['imagen_bg'],
                $data['activa'] ?? 0
            ]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
            break;

        case 'updateHeroConfig':
            $stmt = $db->prepare("UPDATE hero_config SET nombre = ?, titulo = ?, subtitulo_h2 = ?, subtitulo_h4 = ?, referencia_h5 = ?, icono_titulo = ?, icono_referencia = ?, imagen_bg = ?, activa = ? WHERE id = ?");
            $stmt->execute([
                $data['nombre'],
                $data['titulo'],
                $data['subtitulo_h2'] ?? null,
                $data['subtitulo_h4'] ?? null,
                $data['referencia_h5'] ?? null,
                $data['icono_titulo'] ?? null,
                $data['icono_referencia'] ?? null,
                $data['imagen_bg'],
                $data['activa'] ?? 0,
                $data['id']
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'activateHeroConfig':
            // Desactivar todas
            $db->exec("UPDATE hero_config SET activa = 0");
            // Activar la seleccionada
            $stmt = $db->prepare("UPDATE hero_config SET activa = 1 WHERE id = ?");
            $stmt->execute([$data['id']]);
            echo json_encode(['success' => true]);
            break;


        // FIN Código agregado 6mar26, para añadir imagenes en la sección principal de bienvenida en la página



    }
}

function handleDelete($db, $action)
{
    switch ($action) {
        case 'delete':
            $id = $_GET['id'] ?? 0;
            $stmt = $db->prepare("DELETE FROM cantos WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        case 'deleteCategoria':
            $id = $_GET['id'] ?? 0;
            $stmt = $db->prepare("DELETE FROM categorias WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['error' => 'Acción no válida']);





        //Código agregado 6mar26, para añadir imagenes en la sección principal de bienvenida en la página
        case 'deleteHeroConfig':
            $id = $_GET['id'] ?? 0;
            $stmt = $db->prepare("DELETE FROM hero_config WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;
        //FIN Código agregado 6mar26, para añadir imagenes en la sección principal de bienvenida en la página


        case 'getActiveHeroConfig':
            $stmt = $db->query("SELECT * FROM hero_config WHERE activa = 1 LIMIT 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($result ?: null);
            break;


    }
}
