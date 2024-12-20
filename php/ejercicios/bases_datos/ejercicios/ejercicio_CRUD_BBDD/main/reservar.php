<?php
require_once '../tools/funciones.php';
require_once '../objetos/bbdd.php';
session_start();
BBDD::startBBDD();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$habitaciones = getAllHabitaciones();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['reservar'])) {
        $reserva = reservar($_POST['usuario_id'], $_POST['habitacion_id'], $_POST['fecha_entrada'], $_POST['fecha_salida']);
        if ($reserva) {
            header('Location: ver_reservas.php');
        } else {
            $error = "No se ha podido realizar la reserva en esa fecha";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<body>
    <h1>Reservar</h1>
    <a href="ver_reservas.php">Ver tus reservas</a>
    <form action="reservar.php" method="post">
        <label for="fecha_entrada">Fecha de entrada</label>
        <input type="date" name="fecha_entrada" id="fecha_entrada" required>
        <label for="fecha_salida">Fecha de salida</label>
        <input type="date" name="fecha_salida" id="fecha_salida" required>
        <select name="habitacion_id" id="habitacion_id">
            <?php foreach ($habitaciones as $habitacion) : ?>
                <option value="<?= htmlspecialchars($habitacion['id']) ?>"><?= htmlspecialchars($habitacion['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($_SESSION['id']) ?>">
        <input type="submit" name="reservar" value="Reservar">
    </form>
    <?php if (isset($error)) : ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <a href="cerrar_sesion.php">Cerrar Sesion</a>
</body>
</html>