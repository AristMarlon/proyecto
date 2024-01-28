<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pdf_path'])) {
        $pdf_path = $_POST['pdf_path'];

        // Forzar la descarga del archivo
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($pdf_path) . '"');
        readfile($pdf_path);
        exit;
    }
}

echo 'Error al intentar descargar el PDF.';
?>
