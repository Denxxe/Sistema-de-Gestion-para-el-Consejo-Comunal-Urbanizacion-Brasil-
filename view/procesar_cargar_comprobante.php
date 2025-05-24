<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_rol"] !== 'usuario') {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Reemplaza
$password_db = ""; // Reemplaza
$dbname = "consejo_comunal";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pago_id = $_POST["pago_id"];
        $metodo_pago = trim($_POST["metodo_pago"]);
        $referencia = trim($_POST["referencia"]);

        // Validar que se haya subido un archivo
        if (!isset($_FILES['comprobante']) || $_FILES['comprobante']['error'] !== UPLOAD_ERR_OK) {
            header("Location: usuario_cargar_comprobante.php?pago_id=" . $pago_id . "&mensaje=Por favor, sube un comprobante de pago.&tipo=warning");
            exit();
        }

        $comprobante = $_FILES['comprobante'];
        $nombre_archivo = $comprobante['name'];
        $tipo_archivo = $comprobante['type'];
        $tamano_archivo = $comprobante['size'];
        $ruta_temporal = $comprobante['tmp_name'];

        // Validar tipo de archivo (solo imágenes)
        $tipos_permitidos = ['image/jpeg', 'image/png'];
        if (!in_array($tipo_archivo, $tipos_permitidos)) {
            header("Location: usuario_cargar_comprobante.php?pago_id=" . $pago_id . "&mensaje=Solo se permiten archivos de imagen (JPEG o PNG).&tipo=warning");
            exit();
        }

        // Validar tamaño del archivo (ejemplo: máximo 2MB)
        $tamano_maximo = 2 * 1024 * 1024; // 2MB
        if ($tamano_archivo > $tamano_maximo) {
            header("Location: usuario_cargar_comprobante.php?pago_id=" . $pago_id . "&mensaje=El archivo es demasiado grande. El tamaño máximo permitido es 2MB.&tipo=warning");
            exit();
        }

        // Crear un nombre de archivo único
        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        $nombre_unico = 'comprobante_' . $pago_id . '_' . time() . '.' . $extension;
        $ruta_destino = 'uploads/' . $nombre_unico; // Crear una carpeta 'uploads' en tu proyecto

        // Mover el archivo subido a la ubicación de destino
        if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
            // Actualizar la base de datos con la información del pago y la ruta del comprobante
            $stmt = $conn->prepare("UPDATE pagos_beneficios SET fecha_pago = NOW(), metodo_pago = :metodo_pago, referencia = :referencia, comprobante_path = :comprobante_path, estado_pago = 'pendiente' WHERE id = :pago_id AND usuario_id = :usuario_id");
          $stmt->bindParam(':metodo_pago', $metodo_pago);
        $stmt->bindParam(':referencia', $referencia);
        $stmt->bindParam(':comprobante_path', $ruta_destino);
        $stmt->bindParam(':pago_id', $pago_id);
         $stmt->bindParam(':usuario_id', $_SESSION["user_id"]);

            if ($stmt->execute()) {
                header("Location: usuario_dashboard.php?mensaje=Comprobante subido exitosamente. Pendiente de verificación.&tipo=success");
                exit();
            } else {
                // Si falla la actualización de la BD, podríamos eliminar el archivo subido
                if (file_exists($ruta_destino)) {
                    unlink($ruta_destino);
                }
                header("Location: usuario_cargar_comprobante.php?pago_id=" . $pago_id . "&mensaje=Error al registrar el pago. Inténtalo de nuevo.&tipo=danger");
                exit();
            }
        } else {
            header("Location: usuario_cargar_comprobante.php?pago_id=" . $pago_id . "&mensaje=Error al subir el archivo del comprobante.&tipo=danger");
            exit();
        }

    } else {
        header("Location: usuario_dashboard.php");
        exit();
    }

} catch(PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

$conn = null;
?>