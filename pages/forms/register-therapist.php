<!-- pages/forms/register-therapists.html -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Terapeutas</title>
  <!-- Incluye estilos de AdminLTE -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Contenido principal -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <h1 class="m-0">Registro de Terapeutas</h1>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <form id="therapistForm">
          <div class="form-group">
            <label for="firstname">Nombre:</label>
            <input type="text" class="form-control" id="firstname" required>
          </div>
          <div class="form-group">
            <label for="lastname">Apellido:</label>
            <input type="text" class="form-control" id="lastname" required>
          </div>
          <div class="form-group">
            <label for="profession">Profesión:</label>
            <input type="text" class="form-control" id="profession" required>
          </div>
          <div class="form-group">
            <label for="phone">Teléfono:</label>
            <input type="tel" class="form-control" id="phone" required>
          </div>
          <div class="form-group">
            <label for="area">Área:</label>
            <select class="form-control" id="area"></select>
          </div>
          <div class="form-group">
            <label for="branch">Sucursal:</label>
            <select class="form-control" id="branch"></select>
          </div>
          <button type="submit" class="btn btn-primary">Registrar Terapeuta</button>
        </form>
      </div>
    </section>
  </div>
</div>

<!-- JS -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>

<!-- Lógica de envío -->
<script>
$(document).ready(function () {
  // Cargar áreas y sucursales
  $.get("/api/backend/helpers/get.areas.php", function(data) {
    data.forEach(function(item) {
      $('#area').append(`<option value="${item.id_area}">${item.name_area}</option>`);
    });
  });

  $.get("/api/backend/helpers/get.branches.php", function(data) {
    data.forEach(function(item) {
      $('#branch').append(`<option value="${item.id_branch}">${item.name_branch}</option>`);
    });
  });

  // Envío del formulario
  $('#therapistForm').submit(function (e) {
    e.preventDefault();
    const data = {
      firstname_employee: $('#firstname').val(),
      lastname_employee: $('#lastname').val(),
      profession_employee: $('#profession').val(),
      phone_employee: $('#phone').val(),
      id_area_employee: $('#area').val(),
      id_branch_employee: $('#branch').val()
    };

    $.ajax({
      url: "/api/backend/helpers/post.therapists.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(data),
      success: function (response) {
        alert("Terapeuta registrado correctamente.");
        $('#therapistForm')[0].reset();
      },
      error: function (err) {
        alert("Error al registrar terapeuta.");
        console.error(err);
      }
    });
  });
});
</script>
</body>
</html>
