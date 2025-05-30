<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Persona</title>
  <link rel="stylesheet" href="../../public/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
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
      <div id="respuesta" class="mt-3"></div>
    </div>
  </div>
</div>

<script src="../../public/assets/js/bootstrap.bundle.min.js"></script>
<script>
function copiarDatos(datos) {
  const texto = Object.entries(datos)
    .map(([key, value]) => `${key}: ${value || 'No especificado'}`)
    .join('\n');

  navigator.clipboard.writeText(texto)
    .then(() => {
      alert('Datos copiados al portapapeles');
    })
    .catch(err => {
      console.error('Error al copiar:', err);
      alert('Error al copiar los datos');
    });
}

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
    // Mostrar spinner mientras se procesa
    const mensajeDiv = document.getElementById('mensaje');
    const respuestaDiv = document.getElementById('respuesta');
    mensajeDiv.innerHTML = `
      <div class="d-flex justify-content-center">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
      </div>`;

    const response = await fetch('/Sistema-de-Gestion-para-el-Consejo-Comunal-Urbanizacion-Brasil-/public/personas', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    });

    const resultado = await response.json();
    const alertClass = response.ok ? 'alert-success' : 'alert-danger';
    
    // Mostrar mensaje de éxito/error
    mensajeDiv.innerHTML = `
      <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
        ${resultado.message || 'Error al procesar la solicitud'}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>`;
    
    // Si hay datos, mostrarlos en una tabla bonita
    if (response.ok && resultado.data) {
      respuestaDiv.innerHTML = `
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detalles de la Persona</h5>
            <button type="button" class="btn btn-outline-light btn-sm" onclick='copiarDatos(${JSON.stringify(resultado.data)})'>
              <i class="bi bi-clipboard"></i> Copiar Datos
            </button>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <th style="width: 200px">Cédula:</th>
                    <td>${resultado.data.cedula}</td>
                  </tr>
                  <tr>
                    <th>Nombres:</th>
                    <td>${resultado.data.nombres}</td>
                  </tr>
                  <tr>
                    <th>Apellidos:</th>
                    <td>${resultado.data.apellidos}</td>
                  </tr>
                  <tr>
                    <th>Fecha de Nacimiento:</th>
                    <td>${resultado.data.fecha_nacimiento || '<span class="text-muted">No especificado</span>'}</td>
                  </tr>
                  <tr>
                    <th>Sexo:</th>
                    <td>${resultado.data.sexo || '<span class="text-muted">No especificado</span>'}</td>
                  </tr>
                  <tr>
                    <th>Teléfono:</th>
                    <td>${resultado.data.telefono || '<span class="text-muted">No especificado</span>'}</td>
                  </tr>
                  <tr>
                    <th>Dirección:</th>
                    <td>${resultado.data.direccion || '<span class="text-muted">No especificado</span>'}</td>
                  </tr>
                  <tr>
                    <th>Correo:</th>
                    <td>${resultado.data.correo || '<span class="text-muted">No especificado</span>'}</td>
                  </tr>
                  <tr>
                    <th>Estado:</th>
                    <td>${resultado.data.estado || '<span class="text-muted">No especificado</span>'}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>`;

      // Limpiar el formulario si la creación fue exitosa
      document.getElementById('personaForm').reset();
    } else {
      respuestaDiv.innerHTML = '';
    }
    } catch (error) {
      console.error('Error en fetch:', error);
      const mensajeDiv = document.getElementById('mensaje');
      mensajeDiv.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          Error al procesar la solicitud: ${error.message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
    }
  });
</script>

</body>
</html>
