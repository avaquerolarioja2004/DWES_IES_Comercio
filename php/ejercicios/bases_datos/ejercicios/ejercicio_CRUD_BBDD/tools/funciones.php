<?php
require_once '../objetos/usuario.php';
require_once '../objetos/habitacion.php';
require_once '../objetos/reservas.php';

function crearBBDD($nombre = 'ejercicio_crud', $pdo)
{
    try {
        $sql = "CREATE DATABASE IF NOT EXISTS $nombre";
        $pdo->exec($sql);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function conectar($host = 'localhost')
{
    try {
        $dsn = "mysql:host=$host;charset=utf8";
        $pdo = new PDO($dsn, 'root', 'mysql');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function conectarBD($baseDatos, $usuario, $contrasena)
{
    try {
        $dsn = "mysql:host=localhost;dbname=$baseDatos;charset=utf8";
        $pdo = new PDO($dsn, $usuario, $contrasena);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function crearTablaUsuario($pdo)
{
    try {
        $smt = $pdo->prepare("CREATE TABLE usuarios (
                            id INT PRIMARY KEY AUTO_INCREMENT,
                            nombre VARCHAR(50),
                            email VARCHAR(100) UNIQUE,
                            password VARCHAR(255),
                            rol ENUM('admin', 'usuario') DEFAULT 'usuario')");
        $smt->execute();
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function crearTablaHabitaciones($pdo)
{
    try {
        $smt = $pdo->prepare("CREATE TABLE habitaciones (
                            id INT PRIMARY KEY AUTO_INCREMENT,
                            nombre VARCHAR(50),
                            descripcion TEXT,
                            precio DECIMAL(10, 2),
                            imagen VARCHAR(255))");
        $smt->execute();
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function crearTablaReservas($pdo)
{
    try {
        $smt = $pdo->prepare("CREATE TABLE reservas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    habitacion_id INT,
    fecha_inicio DATE,
    fecha_fin DATE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (habitacion_id) REFERENCES habitaciones(id),
    CONSTRAINT unique_reserva UNIQUE (habitacion_id, fecha_inicio, fecha_fin)
)");
        $smt->execute();
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function registrarUsuario($nombre, $email, $password, $rol = 'usuario')
{
    try {
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        $smt->execute([$nombre, $email, password_hash($password, PASSWORD_DEFAULT), $rol]);
        return getUsuario($pdo->lastInsertId());
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function getUsuario($id)
{
    try {
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("SELECT id, nombre, email, password, rol FROM usuarios WHERE id = ?");
        $smt->execute([$id]);
        $usuario = $smt->fetch(PDO::FETCH_ASSOC);
        return $usuario;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function getUsuarioPorEmail($email)
{
    try {
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $smt->execute([$email]);
        $smt->setFetchMode(PDO::FETCH_ASSOC);
        $usuario = $smt->fetch();
        return $usuario;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function getAllUsuarios()
{
    try {
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("SELECT id, nombre, email, password, rol FROM usuarios");
        $smt->execute();
        $usuarios = $smt->fetchAll(PDO::FETCH_ASSOC);
        return $usuarios;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function borrarUsuarioPorId($id)
{
    try {
        $user = getUsuario($id);
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $smt->execute([$id]);
        return $user;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function borrarUsuarioPorEmail($email)
{
    try {
        $user = getUsuarioPorEmail($email);
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("DELETE FROM usuarios WHERE email = ?");
        $smt->execute([$email]);
        return $user;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function getAllHabitaciones()
{
    try {
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("SELECT id, nombre, descripcion, precio, imagen FROM habitaciones");
        $smt->execute();
        $habitaciones = $smt->fetchAll(PDO::FETCH_ASSOC);

        return $habitaciones;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function getHabitacion($id)
{
    try {
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("SELECT id, nombre, descripcion, precio, imagen FROM habitaciones WHERE id = ?");
        $smt->execute([$id]);
        $habitacion = $smt->fetch(PDO::FETCH_ASSOC);

        return $habitacion;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function registrarHabitacion($nombre, $descripcion, $precio, $imagen)
{
    try {
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("INSERT INTO habitaciones (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)");
        $smt->execute([$nombre, $descripcion, $precio, $imagen]);
        return getHabitacion($pdo->lastInsertId());
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function borrarHabitacion($id){
    try {
        $habitacion = getHabitacion($id);
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("DELETE FROM habitaciones WHERE id = ?");
        $smt->execute([$id]);
        return $habitacion;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function reservar($usuarioId, $habitacionId, $fechaInicio, $fechaFin)
{
    try {
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("INSERT INTO reservas (usuario_id, habitacion_id, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
        $smt->execute([$usuarioId, $habitacionId, $fechaInicio, $fechaFin]);
        return getReserva($pdo->lastInsertId());
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function getReserva($id)
{
    try {
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("SELECT id, usuario_id, habitacion_id, fecha_inicio, fecha_fin FROM reservas WHERE id = ?");
        $smt->execute([$id]);
        $reserva = $smt->fetch(PDO::FETCH_ASSOC);
        return $reserva;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function getReservasPorUsuario($id)
{
    try{
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("SELECT id, usuario_id, habitacion_id, fecha_inicio, fecha_fin FROM reservas WHERE usuario_id = ?");
        $smt->execute([$id]);
        $reservas = $smt->fetchAll(PDO::FETCH_ASSOC);
        return $reservas;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function cancelarReserva($id)
{
    try {
        $reserva = getReserva($id);
        $pdo = BBDD::getBBDD();
        $smt = $pdo->prepare("DELETE FROM reservas WHERE id = ?");
        $smt->execute([$id]);
        return $reserva;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}
