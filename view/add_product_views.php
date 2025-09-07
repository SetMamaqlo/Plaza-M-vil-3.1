<?php
session_start();
if (!isset($_SESSION['user_id_usuario']) || $_SESSION['user_id_rol'] !== 3) {
    header("Location: ../view/login.php");
    exit;
}
include '../config/conexion.php';
include '../controller/medidas_controller.php';
include '../controller/gestion_categorias.php';
include '../controller/productcontroller.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<body class="d-flex align-items-center justify-content-center vh-100">

    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Añadir Nuevo Producto</h2>
                <form action="../controller/productcontroller.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="precio" name="precio" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="precio" name="precio" required>
                    </div>
                     <div class="mb-3">
						<label for="id_unidad" class="form-label">Unidad de medida</label>
						<select class="form-control" id="id_unidad" name="id_unidad" required>
							<option value="">-- Selecciona una unidad --</option>
							<?php var_dump($medidas); ?>
							<?php foreach ($medidas as $medida): ?>
								<option value="<?= $medida['id_unidad'] ?>"><?= htmlspecialchars($medida['nombre']) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3">
						<label for="id_categoria" class="form-label">Categoría</label>
						<select class="form-control" id="id_categoria" name="id_categoria" required>
							<option value="">-- Selecciona una categoría --</option>
							<?php var_dump($categorias); ?>
							<?php foreach ($categorias as $cat): ?>
								<option value="<?= htmlspecialchars($cat['id_categoria']) ?>">
									<?= htmlspecialchars($cat['nombre']) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                        </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen del Producto</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Añadir Producto</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>