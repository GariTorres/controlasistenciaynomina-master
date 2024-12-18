<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Agregar Nuevo Empleado</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="employees.php">Empleados</a></li>
        <li class="active">Agregar Nuevo</li>
      </ol>
    </section>
    
    <section class="content">
      <div class="box">
        <div class="box-body">
          <form method="POST" action="employee_add.php" enctype="multipart/form-data">
            <fieldset>
              <legend>Información Personal</legend>
              <div class="form-group" style="margin-bottom:55px;">
                <label for="firstname" class="col-sm-3 control-label">Nombre</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="firstname" name="firstname" required>
                </div>
              </div>
              <div class="form-group">
                <label for="lastname" class="col-sm-3 control-label">Apellido</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="lastname" name="lastname" required>
                </div>
              </div>
              <div class="form-group">
                <label for="address" class="col-sm-3 control-label">Dirección</label>
                <div class="col-sm-9">
                  <textarea class="form-control" name="address" id="address"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label for="contact" class="col-sm-3 control-label">Información de Contacto</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="contact" name="contact">
                </div>
              </div>
              <div class="form-group">
                <label for="datepicker_add" class="col-sm-3 control-label">Fecha de Nacimiento</label>
                <div class="col-sm-9"> 
                  <div class="date">
                    <input type="text" class="form-control" id="datepicker_add" name="birthdate">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="type_document" class="col-sm-3 control-label">Tipo de documento</label>
                <div class="col-sm-9"> 
                  <select class="form-control" name="type_document" id="type_document">
                    <option value="" selected>- Seleccionar -</option>
                    <option value="DNI">DNI</option>
                    <option value="Cedula">Cédula</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="document_number" class="col-sm-3 control-label">Documento de identidad</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="document_number" name="document_number">
                </div>
              </div>
              <div class="form-group">
                <label for="document_expiration_date" class="col-sm-3 control-label">Fecha de Expiración</label>
                <div class="col-sm-9"> 
                  <div class="date">
                    <input type="text" class="form-control" id="datepicker_add_1" name="document_expiration_date">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="email" class="col-sm-3 control-label">Email</label>
                <div class="col-sm-9">
                  <input type="email" class="form-control" id="email" name="email">
                </div>
              </div>                
              <div class="form-group">
                <label for="gender" class="col-sm-3 control-label">Género</label>
                <div class="col-sm-9"> 
                  <select class="form-control" name="gender" id="gender" required>
                    <option value="" selected>- Seleccionar -</option>
                    <option value="Male">Hombre</option>
                    <option value="Female">Mujer</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="position" class="col-sm-3 control-label">Cargo</label>
                <div class="col-sm-9">
                  <select class="form-control" name="position" id="position" required>
                    <option value="" selected>- Seleccionar -</option>
                    <?php
                      $sql = "SELECT * FROM position";
                      $query = $conn->query($sql);
                      while($prow = $query->fetch_assoc()){
                        echo "
                          <option value='".$prow['id']."'>".$prow['description']."</option>
                        ";
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="shift" class="col-sm-3 control-label">Turno</label>
                <div class="col-sm-9">
                  <select class="form-control" name="shift" id="shift" required>
                    <option value="" selected>- Seleccionar -</option>
                    <option value="Diurno">Diurno</option>
                    <option value="Nocturno">Nocturno</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="photo" class="col-sm-3 control-label">Foto</label>
                <div class="col-sm-9">
                  <input type="file" name="photo" id="photo">
                </div>
              </div>
            </fieldset>

            <fieldset>
              <legend>Información bancaria</legend>
              <div class="form-group">
                <label for="bank" class="col-sm-3 control-label">Banco</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="bank" name="bank">
                </div>
              </div>
              <div class="form-group">
                <label for="account_number" class="col-sm-3 control-label">Número de cuenta</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="account_number" name="account_number">
                </div>
              </div>
              <div class="form-group">
                <label for="inter_bank_code" class="col-sm-3 control-label">Código interbancario</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="inter_bank_code" name="inter_bank_code">
                </div>
              </div>
              <div class="form-group">
                <label for="pension_plan" class="col-sm-3 control-label">Plan de pensiones</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="pension_plan" name="pension_plan">
                </div>
              </div>
              <div class="form-group">
                <label for="health_insurance" class="col-sm-3 control-label">Seguro de salud</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="health_insurance" name="health_insurance">
                </div>
              </div>
            </fieldset>

            <fieldset>
              <legend>Información de trabajo</legend>
              <div class="form-group">
                <label for="salary" class="col-sm-3 control-label">Sueldo</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="salary" name="salary">
                </div>
              </div>
            </fieldset>

            <div class="modal-footer">
              <a href="employees.php" class="btn btn-default btn-flat pull-left"><i class="fa fa-close"></i> Cerrar</a>
              <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>
  
  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
