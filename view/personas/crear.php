<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Persona</title>
  <link rel="stylesheet" href="../../public/assets/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4>Registro de Persona</h4>
    </div>
    <div class="card-body">
      <form id="personaForm">
        <div class="mb-3">
          <label for="cedula" class="form-label">Cédula</label>
          <input type="text" class="form-control" id="cedula" required>
        </div>
        <div class="mb-3">
          <label for="nombres" class="form-label">Nombres</label>
          <input type="text" class="form-control" id="nombres" required>
        </div>
        <div class="mb-3">
          <label for="apellidos" class="form-label">Apellidos</label>
          <input type="text" class="form-control" id="apellidos" required>
        </div>
        <div class="mb-3">
          <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
          <input type="date" class="form-control" id="fecha_nacimiento">
        </div>
        <div class="mb-3">
          <label for="sexo" class="form-label">Sexo</label>
          <select class="form-select" id="sexo">
            <option value="">Seleccione</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="telefono" class="form-label">Teléfono</label>
          <input type="text" class="form-control" id="telefono">
        </div>
        <div class="mb-3">
          <label for="direccion" class="form-label">Dirección</label>
          <textarea class="form-control" id="direccion"></textarea>
        </div>
        <div class="mb-3">
          <label for="correo" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="correo">
        </div>
        <div class="mb-3">
          <label for="estado" class="form-label">Estado</label>
          <input type="text" class="form-control" id="estado">
        </div>
        <button type="submit" class="btn btn-success">Crear Persona</button>
      </form>
      <div id="mensaje" class="mt-3"></div>
    </div>
  </div>
</div>

<script src="../../public/assets/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('personaForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const data = {
      cedula: document.getElementById('cedula').value,
      nombres: document.getElementById('nombres').value,
      apellidos: document.getElementById('apellidos').value,
      fecha_nacimiento: document.getElementById('fecha_nacimiento').value,
      sexo: document.getElementById('sexo').value,
      telefono: document.getElementById('telefono').value,
      direccion: document.getElementById('direccion').value,
      correo: document.getElementById('correo').value,
      estado: document.getElementById('estado').value
    };

    try {
      const response = await fetch('/personas', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });


      const resultado = await response.json();
      const mensajeDiv = document.getElementById('mensaje');
      mensajeDiv.innerHTML = `
        <div class="alert alert-${resultado.statusCode === 201 ? 'success' : 'danger'}">
          ${resultado.message}
        </div>`;
    } catch (error) {
      console.error("Error en fetch:", error);
    }
  });
</script>

</body>
</html>
