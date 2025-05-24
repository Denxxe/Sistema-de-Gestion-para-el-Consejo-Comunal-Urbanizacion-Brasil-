<?php

$servername = "localhost";
$username = "root"; // Reemplaza con tu nombre de usuario de la base de datos
$password_db = ""; // Reemplaza con tu contraseña de la base de datos
$dbname = "consejo_comunal";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Función para hashear la contraseña
    function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Insertar Administrador Principal
    $stmt = $conn->prepare("INSERT INTO usuarios (rol, cedula, nombre, apellido, email, password) VALUES (:rol, :cedula, :nombre, :apellido, :email, :password)");
    $stmt->bindParam(':rol', $rol_admin);
    $stmt->bindParam(':cedula', $cedula_admin);
    $stmt->bindParam(':nombre', $nombre_admin);
    $stmt->bindParam(':apellido', $apellido_admin);
    $stmt->bindParam(':email', $email_admin);
    $stmt->bindParam(':password', $password_admin_hash);

    $rol_admin = 'admin_principal';
    $cedula_admin = '12345678';
    $nombre_admin = 'Admin';
    $apellido_admin = 'Principal';
    $email_admin = 'admin@example.com';
    $password_admin = 'admin123';
    $password_admin_hash = hashPassword($password_admin);
    $stmt->execute();
    echo "Administrador Principal insertado.<br>";

    // Insertar Sub-Administrador
    $stmt = $conn->prepare("INSERT INTO usuarios (rol, cedula, nombre, apellido, email, password) VALUES (:rol, :cedula, :nombre, :apellido, :email, :password)");
    $stmt->bindParam(':rol', $rol_subadmin);
    $stmt->bindParam(':cedula', $cedula_subadmin);
    $stmt->bindParam(':nombre', $nombre_subadmin);
    $stmt->bindParam(':apellido', $apellido_subadmin);
    $stmt->bindParam(':email', $email_subadmin);
    $stmt->bindParam(':password', $password_subadmin_hash);

    $rol_subadmin = 'sub_admin';
    $cedula_subadmin = '87654321';
    $nombre_subadmin = 'Sub';
    $apellido_subadmin = 'Admin';
    $email_subadmin = 'subadmin@example.com';
    $password_subadmin = 'subadmin123';
    $password_subadmin_hash = hashPassword($password_subadmin);
    $stmt->execute();
    echo "Sub-Administrador insertado.<br>";

    // Insertar Usuario Común
    $stmt = $conn->prepare("INSERT INTO usuarios (rol, cedula, nombre, apellido, email, password) VALUES (:rol, :cedula, :nombre, :apellido, :email, :password)");
    $stmt->bindParam(':rol', $rol_usuario);
    $stmt->bindParam(':cedula', $cedula_usuario);
    $stmt->bindParam(':nombre', $nombre_usuario);
    $stmt->bindParam(':apellido', $apellido_usuario);
    $stmt->bindParam(':email', $email_usuario);
    $stmt->bindParam(':password', $password_usuario_hash);

    $rol_usuario = 'usuario';
    $cedula_usuario = '11223344';
    $nombre_usuario = 'Usuario';
    $apellido_usuario = 'Comun';
    $email_usuario = 'usuario@example.com';
    $password_usuario = 'usuario123';
    $password_usuario_hash = hashPassword($password_usuario);
    $stmt->execute();
    echo "Usuario Común insertado.<br>";

    echo "Usuarios de prueba insertados correctamente.";

} catch(PDOException $e) {
    echo "Error al insertar usuarios: " . $e->getMessage();
}

$conn = null;
?>