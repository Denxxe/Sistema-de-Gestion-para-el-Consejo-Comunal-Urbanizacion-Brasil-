<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_rol"] !== 'admin_principal') {
    header(header: "Location: login.php");
    exit();
}

$mensaje = "";
if (isset($_GET['mensaje'])) {
    $mensaje = '<div class="alert alert-' . ($_GET['tipo'] ?? 'info') . ' mt-3" role="alert">' . $_GET['mensaje'] . '</div>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activar Pago de Beneficio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Activar Pago de Beneficio</h2>
        <p><a href="admin_dashboard.php" class="btn btn-secondary mb-3">Volver al Dashboard</a></p>

        <?php echo $mensaje; ?>

        <div class="card p-4 shadow">
            <form action="procesar_activar_pago.php" method="POST">
                <div class="form-group">
                    <label for="beneficio">Beneficio:</label>
                    <input type="text" class="form-control" id="beneficio" name="beneficio" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="mes">Mes:</label>
                        <select class="form-control" id="mes" name="mes" required>
                            <?php
                            $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                            for ($i = 1; $i <= 12; $i++) {
                                echo '<option value="' . $i . '">' . $meses[$i - 1] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="anio">Año:</label>
                        <select class="form-control" id="anio" name="anio" required>
                            <?php
                            $anio_actual = date("Y");
                            for ($i = $anio_actual; $i <= $anio_actual + 5; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="monto">Monto a Pagar:</label>
                    <input type="number" class="form-control" id="monto" name="monto" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="detalles_pago">Detalles del Pago (Banco, Cuenta, etc.):</label>
                    <textarea class="form-control" id="detalles_pago" name="detalles_pago" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="fecha_limite">Fecha Límite de Pago:</label>
                    <input type="date" class="form-control" id="fecha_limite" name="fecha_limite">
                </div>
                <button type="submit" class="btn btn-primary">Activar Pago</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>