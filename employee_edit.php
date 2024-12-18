<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$empid = $_POST['id'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$address = $_POST['address'];
		$birthdate = $_POST['birthdate'];
		$contact = $_POST['contact'];
		$gender = $_POST['gender'];
		$shift = $_POST['shift'];
		$salary = $_POST['salary'];
		$position = $_POST['position'];
		$type_document = $_POST['type_document'];
		$document_number = $_POST['document_number'];
		$document_expiration_date = $_POST['document_expiration_date'];
		$bank = $_POST['bank'];
		$account_number = $_POST['account_number'];
		$inter_bank_code = $_POST['inter_bank_code'];
		$pension_plan = $_POST['pension_plan'];
		$health_insurance = $_POST['health_insurance'];
		
		$sql = "UPDATE employees SET firstname = '$firstname', lastname = '$lastname', address = '$address', birthdate = '$birthdate', contact_info = '$contact', gender = '$gender', salary = '$salary', shift = '$shift', type_document = '$type_document', document_number = '$document_number', document_expiration_date = '$document_expiration_date', bank = '$bank', account_number = '$account_number', inter_bank_code = '$inter_bank_code', pension_plan = '$pension_plan', health_insurance = '$health_insurance', position_id = '$position' WHERE id = '$empid'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Empleado actualizado con éxito';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Seleccionar empleado para editar primero';
	}

	header('location: employee.php');
?>