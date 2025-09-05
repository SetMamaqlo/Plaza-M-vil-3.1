<?php
require_once '../controller/medidas_controller.php'; ?>
<form action="../controller/medidas_controller.php" method="POST">
    <input type="hidden" name="accion" value="crear">
    <input type="text" name="nombre" placeholder="Nombre de la medida" required>
    <button type="submit">Guardar</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($medidas as $medida): ?>
    <tr>
        <td><?= $medida['id_unidad'] ?></td>
        <td><?= $medida['nombre'] ?></td>
        <td>
            <!-- Editar -->
            <form action="../controller/medidas_controller.php" method="POST" style="display:inline;">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id_unidad" value="<?= $medida['id_unidad'] ?>">
                <input type="text" name="nombre" value="<?= $medida['nombre'] ?>" required>
                <button type="submit">Editar</button>
            </form>

            <!-- Eliminar -->
            <form action="../controller/medidas_controller.php" method="POST" style="display:inline;">
                <input type="hidden" name="accion" value="eliminar">
                <input type="hidden" name="id_unidad" value="<?= $medida['id_unidad'] ?>">
                <button type="submit" onclick="return confirm('¿Seguro que deseas eliminar esta medida?')">Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
