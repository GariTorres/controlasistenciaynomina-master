<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
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
		$filename = $_FILES['photo']['name'];
		if(!empty($filename)){
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);	
		}
		//creating employeeid
		$letters = '';
		$numbers = '';
		foreach (range('A', 'Z') as $char) {
		    $letters .= $char;
		}
		for($i = 0; $i < 10; $i++){
			$numbers .= $i;
		}
		$employee_id = substr(str_shuffle($letters), 0, 3).substr(str_shuffle($numbers), 0, 9);
		//
		$sql = "INSERT INTO employees (employee_id, firstname, lastname, address, birthdate, contact_info, gender, shift, position_id, type_document, document_number, document_expiration_date, salary, bank, account_number, inter_bank_code, pension_plan, health_insurance, photo, created_on) VALUES ('$employee_id', '$firstname', '$lastname', '$address', '$birthdate', '$contact', '$gender', '$shift', '$position','$type_document', '$document_number', '$document_expiration_date', '$salary', '$bank', '$account_number', '$inter_bank_code', '$pension_plan', '$health_insurance', '$filename', NOW())";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Empleado añadido satisfactoriamente';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: employee.php');
?>