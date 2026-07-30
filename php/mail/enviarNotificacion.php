<?php
require_once __DIR__ . '/../../vendor/autoload.php'; // ajusta si tu carpeta vendor está en otra ruta
require_once __DIR__ . '/../config-mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarNotificacionPost($post, $conexion)
{
    // 1. Cargar plantilla
    $html = file_get_contents(__DIR__ . '/plantilla-post.php');

    // 2. Reemplazar variables
    $url = "https://casadedios.mx/blog-post.php?slug=" . urlencode($post['slug']);
    $imagen = $post['imagen_portada'] ?: 'https://casadedios.mx/assets/img/logos/logo-default.png';

    $html = str_replace(
        ['{{categoria}}', '{{titulo}}', '{{imagen}}', '{{extracto}}', '{{url}}'],
        [
            htmlspecialchars($post['categoria'] ?? ''),
            htmlspecialchars($post['titulo']),
            htmlspecialchars($imagen),
            htmlspecialchars($post['extracto'] ?? ''),
            htmlspecialchars($url)
        ],
        $html
    );

    // 3. Traer suscriptores confirmados
    $suscriptores = $conexion->query(
        "SELECT email FROM blog_suscriptores WHERE confirmado = 1"
    )->fetchAll();

    file_put_contents(__DIR__ . '/mail-debug.log', date('Y-m-d H:i:s') . " - Suscriptores encontrados: " . count($suscriptores) . "\n", FILE_APPEND);

    if (empty($suscriptores)) {
        return; // nadie a quien enviar
    }

    // 4. Enviar
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(SMTP_USER, 'Casa de Dios');
        $mail->isHTML(true);
        $mail->Subject = 'Nueva publicación: ' . $post['titulo'];
        $mail->Body = $html;

        foreach ($suscriptores as $s) {
            $mail->addBCC($s['email']); // BCC para no exponer correos entre suscriptores
        }

        $mail->send();
        file_put_contents(__DIR__ . '/mail-debug.log', date('Y-m-d H:i:s') . " - Enviado OK a " . count($suscriptores) . " suscriptores\n", FILE_APPEND);
    } catch (Exception $e) {
        error_log('Error enviando notificación: ' . $mail->ErrorInfo);
        file_put_contents(__DIR__ . '/mail-debug.log', date('Y-m-d H:i:s') . ' - ERROR: ' . $mail->ErrorInfo . "\n", FILE_APPEND);
    }
}