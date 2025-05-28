<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
    <h2>Iniciar sesión</h2>
    <?php
    session_start();
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>{$_SESSION['error']}</p>";
        unset($_SESSION['error']);
    }
    ?>
    <form method="POST" action="/login">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Entrar</button>
    </form>

</body>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/chart.min.js"></script>
</html>
