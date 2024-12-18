<?php
require('../tcpdf/tcpdf.php'); // Cambia a la ruta correcta
include 'includes/session.php';

if (isset($_POST['employee_id'])) {
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    
    $sql = "SELECT *, employees.id AS empid FROM employees WHERE employees.id = '$employee_id'";
    $query = $conn->query($sql);
    
    if (!$query) {
        die("Error en la consulta: " . $conn->error);
    }
    
    $employee = $query->fetch_assoc();
    
    if (!$employee) {
        die("Empleado no encontrado.");
    }

    $empresa = [
        "RUC" => "12345678901",
        "EMPLEADOR" => "EMPRESA S.A.C.",
        "PERIODO" => "12/2024",
        "PLAME" => "PLAME"
    ];

    function calcularQuintaCategoria($numero) {
        if ($numero*12 > 36050) {
            $numero = (((($numero*14) - 36050)*8)/100)/12;
            return $numero; // Calcula el 10% del número
        } else {
            return 0; // Retorna el número sin cambios
        }
    }

    function calcularBonificacionExtraordinaria($numero) {
        return $numero*0.09;
    }

    function calcularAportacionObligatoria($numero) {
        return $numero*0.1;
    }

    function calcularPrimaDeSeguro($numero) {
        return $numero*0.017;
    }

    function calcularAporteEssalud($numero) {
        return $numero*0.09;
    }

    $neto = $employee['salary'] + $employee['salary'] + calcularBonificacionExtraordinaria($employee['salary']) - calcularQuintaCategoria($employee['salary']) - calcularAportacionObligatoria($employee['salary']) - calcularPrimaDeSeguro($employee['salary']);

    // Crear el PDF
    $pdf = new TCPDF();
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    
    // Establecer color de fondo rojo
    $pdf->SetFillColor(187, 205, 228);
    
    // Tabla de datos de la empresa sin espaciado innecesario
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(0, 0, 0); // Cambiar texto a blanco para mejor contraste
    
    // Agregar título de la sección
    $pdf->Cell(0, 8, "DATOS DE LA EMPRESA", 0, 1, 'C');
    $pdf->Ln(1); // Espaciado reducido entre título y tabla
    
    // Filas, cada una en una nueva línea con fondo rojo
    $pdf->Cell(10, 6, "RUC:", 'TL', 0, 'L', true); // Agregar `true` para que tenga fondo rojo
    $pdf->Cell(180, 6, $empresa["RUC"], 'TR', 1, 'L', true); // Agregar `true` para fondo rojo
    
    $pdf->Cell(21, 6, "Empleador:", 'L', 0, 'L', true);
    $pdf->Cell(169, 6, $empresa["EMPLEADOR"], 'R', 1, 'L', true); // Agregar `true` para fondo rojo
    
    $pdf->Cell(15, 6, "Periodo:", 'L', 0, 'L', true);
    $pdf->Cell(175, 6, $empresa["PERIODO"], 'R', 1, 'L', true); // Agregar `true` para fondo rojo
    
    $pdf->Cell(190, 6, "PDT Planilla Electrónica - PLAME", 'BLR', 1, 'L', true); // Agregar `true` para fondo rojo
    
    
    $pdf->SetFont('helvetica', '', 9);

    $pdf->SetFillColor(187, 205, 228); 
    $pdf->SetTextColor(0, 0, 0); 
    
    $pdf->Ln(10);
    
    // Cabecera de la tabla
    $pdf->Cell(60, 6, "Documento de identidad", 'RTL', 0, 'C', true);  
    // No se repiten las subcolumnas en "Nombres y apellidos" y "Situación"
    $pdf->Cell(80, 6, "Nombres y apellidos", 'LRT', 0, 'C', true);  
    $pdf->Cell(50, 6, "Situación", 'LTR', 0, 'C', true);
    $pdf->Ln(); // Salto de línea para las subcolumnas
    
    // Subcolumnas debajo de "Documento de identidad", solo para esa columna
    $pdf->Cell(30, 6, "Tipo", 'LBTR', 0, 'C', true);  // Subcolumna Tipo de DNI
    $pdf->Cell(30, 6, "Número de DNI", 'TBRL', 0, 'C', true);  // Subcolumna Número de DNI
    
    // Las otras dos columnas ("Nombres y apellidos" y "Situación") deben ocupar su espacio completo
    $pdf->Cell(80, 6, "", 'LBR', 0, 'C', true); 
    $pdf->Cell(50, 6, "", 'LBR', 1, 'C', true);
    
    // Fila de datos
    $pdf->Cell(30, 6, $employee['type_document'], 'RLB', 0, 'C', false);  // Tipo de DNI (dato de ejemplo)
    $pdf->Cell(30, 6, $employee['document_number'], 'LBR', 0, 'C', false); // Número de DNI (dato de ejemplo)
    
    // Datos para las otras columnas
    $pdf->Cell(80, 6, strtoupper($employee['firstname'] . ' ' . $employee['lastname']), 'BR', 0, 'C', false);  // Nombres y apellidos
    $pdf->Cell(50, 6, "ACTIVO O SUBSIDIADO", 'LBR', 1, 'C', false); // Situación

    $pdf->Cell(60, 6, "Fecha de ingreso", 'BRTL', 0, 'C', true);  
    $pdf->Cell(40, 6, "Tipo de Trabajador", 'BLRT', 0, 'C', true);  
    $pdf->Cell(40, 6, "Regimen Pensionario", 'BLTR', 0, 'C', true);
    $pdf->Cell(50, 6, "CUSPP", 'BLTR', 0, 'C', true);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(60, 6, "03/01/2023", 'BRTL', 0, 'C', false);  
    $pdf->Cell(40, 6, "EMPLEADO", 'BLRT', 0, 'C', false);  
    $pdf->Cell(40, 6, "SPP INTEGRA", 'BLTR', 0, 'C', false);
    $pdf->Cell(50, 6, "", 'BLTR', 0, 'C', false);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(30, 6, "Días", 'RTL', 0, 'C', true);
    $pdf->Cell(30, 6, "Días No", 'RTL', 0, 'C', true);   
    $pdf->Cell(20, 6, "Días", 'LRT', 0, 'C', true);  
    $pdf->Cell(20, 6, "Condición", 'LTR', 0, 'C', true);
    $pdf->Cell(40, 6, "Jornada Ordinaria", 'LTRB', 0, 'C', true);
    $pdf->Cell(50, 6, "Sobretiempo", 'LTRB', 0, 'C', true);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(30, 6, "Laborados", 'RLB', 0, 'C', true);
    $pdf->Cell(30, 6, "Laborados", 'RLB', 0, 'C', true);   
    $pdf->Cell(20, 6, "Subsidiados", 'LRB', 0, 'C', true);  
    $pdf->Cell(20, 6, "", 'LRB', 0, 'C', true);
    $pdf->Cell(20, 6, "Total Horas", 'LRTB', 0, 'C', true);
    $pdf->Cell(20, 6, "Minutos", 'LRTB', 0, 'C', true);
    $pdf->Cell(25, 6, "Total Horas", 'LRTB', 0, 'C', true);
    $pdf->Cell(25, 6, "Minutos", 'LRTB', 0, 'C', true);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(30, 6, "30", 'RLB', 0, 'C', false);
    $pdf->Cell(30, 6, "0", 'RLB', 0, 'C', false);   
    $pdf->Cell(20, 6, "", 'LRB', 0, 'C', false);  
    $pdf->Cell(20, 6, "Domiciliado", 'LRB', 0, 'C', false);
    $pdf->Cell(20, 6, "240", 'LRTB', 0, 'C', false);
    $pdf->Cell(20, 6, "", 'LRTB', 0, 'C', false);
    $pdf->Cell(25, 6, "", 'LRTB', 0, 'C', false);
    $pdf->Cell(25, 6, "", 'LRTB', 0, 'C', false);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(140, 6, "Motivo de Suspensión de Labores", 'TRL', 0, 'C', true);
    $pdf->Cell(50, 6, "Otros empleadores por", 'TRL', 0, 'C', true);   
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(20, 6, "Tipo", 'BTRL', 0, 'C', true);
    $pdf->Cell(100, 6, "Motivo", 'BTRL', 0, 'C', true);
    $pdf->Cell(20, 6, "N°. días", 'BTRL', 0, 'C', true); 
    $pdf->Cell(50, 6, "Rentas de 5° categoría", 'BRL', 0, 'C', true);   
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(20, 6, "", 'BRL', 0, 'C', false);
    $pdf->Cell(100, 6, "", 'BRL', 0, 'C', false);
    $pdf->Cell(20, 6, "", 'BRL', 0, 'C', false); 
    $pdf->Cell(50, 6, "No tiene", 'BRL', 0, 'C', false);   
    $pdf->Ln(15); // Salto de línea para las subcolumnas
    
    $pdf->Cell(20, 6, "Código", 'BTRL', 0, 'C', true);
    $pdf->Cell(95, 6, "Conceptos", 'BTRL', 0, 'C', true);
    $pdf->Cell(25, 6, "Ingresos S/.", 'BTRL', 0, 'C', true); 
    $pdf->Cell(25, 6, "Descuentos S/", 'BTRL', 0, 'C', true);
    $pdf->Cell(25, 6, "Neto S/.", 'BTRL', 0, 'C', true);
    $pdf->Ln(); // Salto de línea para las subcolumnas
    
    $pdf->Cell(190, 6, "Ingresos", 'TRL', 0, 'L', true);
    $pdf->Ln(); // Salto de línea para las subcolumnas
    
    $pdf->Cell(20, 6, "0121", 'L', 0, 'L', false);
    $pdf->Cell(95, 6, "REMUNERACIÓN O JORNAL BÁSICO", 0, 0, 'L', false);
    $pdf->Cell(25, 6, number_format($employee['salary'], 2), 0, 0, 'R', false);
    $pdf->Cell(25, 6, "", 0, 0, 'R', false);
    $pdf->Cell(25, 6, "", 'R', 0, 'R', false);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(20, 6, "0313", 'L', 0, 'L', false);
    $pdf->Cell(95, 6, "BONIF. EXTRAORD. PROPORC. LEY 29351 y 30334", 0, 0, 'L', false);
    $pdf->Cell(25, 6, number_format(calcularBonificacionExtraordinaria($employee['salary']), 2), 0, 0, 'R', false);
    $pdf->Cell(25, 6, "", 0, 0, 'R', false);
    $pdf->Cell(25, 6, "", 'R', 0, 'R', false);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(20, 6, "0406", 'L', 0, 'L', false);
    $pdf->Cell(95, 6, "GRATIF. F.PATRIAS NAVIDAD LEY 29351 Y 30334", 0, 0, 'L', false);
    $pdf->Cell(25, 6, number_format($employee['salary'], 2), 0, 0, 'R', false);
    $pdf->Cell(25, 6, "", 0, 0, 'R', false);
    $pdf->Cell(25, 6, "", 'R', 0, 'R', false);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(190, 6, "Descuentos", 'RL', 0, 'L', true);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(190, 6, "Aportes del Trabajador", 'RL', 0, 'L', true);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(20, 6, "0601", 'L', 0, 'L', false);
    $pdf->Cell(95, 6, "COMISIÓN AFP PORCENTUAL", 0, 0, 'L', false);
    $pdf->Cell(25, 6, "", 0, 0, 'R', false);
    $pdf->Cell(25, 6, "0.00", 0, 0, 'R', false);
    $pdf->Cell(25, 6, "", 'R', 0, 'R', false);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(20, 6, "0605", 'L', 0, 'L', false);
    $pdf->Cell(95, 6, "RENTA QUINTA CATEGORÍA RETENCIONES", 0, 0, 'L', false);
    $pdf->Cell(25, 6, "", 0, 0, 'R', false);
    $pdf->Cell(25, 6, number_format(calcularQuintaCategoria($employee['salary']), 2), 0, 0, 'R', false);
    $pdf->Cell(25, 6, "", 'R', 0, 'R', false);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(20, 6, "0606", 'L', 0, 'L', false);
    $pdf->Cell(95, 6, "PRIMA DE SEGURO AFP", 0, 0, 'L', false);
    $pdf->Cell(25, 6, "", 0, 0, 'R', false);
    $pdf->Cell(25, 6, number_format(calcularPrimaDeSeguro($employee['salary']), 2), 0, 0, 'R', false);
    $pdf->Cell(25, 6, "", 'R', 0, 'R', false);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(20, 6, "0608", 'L', 0, 'L', false);
    $pdf->Cell(95, 6, "SPP - APORTACIÓN OBLIGATORIA", 0, 0, 'L', false);
    $pdf->Cell(25, 6, "", 0, 0, 'R', false);
    $pdf->Cell(25, 6, number_format(calcularAportacionObligatoria($employee['salary']), 2), 0, 0, 'R', false);
    $pdf->Cell(25, 6, "", 'R', 0, 'R', false);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(165, 6, "Neto a pagar", 'LB', 0, 'L', true);
    $pdf->Cell(25, 6, number_format($neto, 2), 'RB', 0, 'R', true);
    $pdf->Ln(15); // Salto de línea para las subcolumnas

    $pdf->Cell(190, 6, "Aportes del empleador", 'TLBR', 0, 'L', true);
    $pdf->Ln(); // Salto de línea para las subcolumnas

    $pdf->Cell(20, 6, "0804", 'BL', 0, 'L', false);
    $pdf->Cell(95, 6, "ESSALUD(REGULAR CBSSP AGRAR/AC)TRAB", 'B', 0, 'L', false);
    $pdf->Cell(25, 6, "", 'B', 0, 'R', false);
    $pdf->Cell(25, 6, "", 'B', 0, 'R', false);
    $pdf->Cell(25, 6, number_format(calcularAporteEssalud($employee['salary']), 2), 'BR', 0, 'R', false);
    $pdf->Ln(60); // Salto de línea para las subcolumnas

    $pdf->Cell(190, 10, "Generado por el PDT Planilla Electrónica PLAME. Página 1 /1", 'TLBR', 0, 'C', true);
    $pdf->Ln(); // Salto de línea para las subcolumnas


    $pdf->Output('nomina_' . $employee['employee_id'] . '.pdf', 'I');
}
?>
