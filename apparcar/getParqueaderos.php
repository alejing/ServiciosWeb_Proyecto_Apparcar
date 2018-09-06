<?php

header("Content-Type: text/html;charset=utf-8");

$servername = "localhost";
//$username   = "id3243163_alejing"; // Para 000webhost
$username   = "root";
//$password   = "jOsefr4nco"; // Para 000webhost
$password   = "";
//$dbname     = "id3243163_apparcar"; // Para 000webhost
$dbname     = "Apparcar";

// Crea una nueva conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica si se estableció la conexión
if ($conn->connect_error) {
  die("Falló la conexión: " . $conn->connect_error);
}
// Permite los acentos que vienen desde la base de datos
$acentos = $conn->query("SET NAMES 'utf8'");
//echo "Conectado satisfactoriamente";

// Se crea un array para codificar en formato JSON
$json = array();
// Selecciona los datos de la tabla Parqueadero
$sql = "SELECT * FROM Parqueadero";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Ítera según los resultados obtenidos en la consulta SQL
  while($row = $result->fetch_assoc()) {
    $bus = array(
      'idParqueadero' => $row['idParqueadero'],
      'descripcion'   => $row['descripcion'],
      'latitud'       => $row['latitud'],
      'longitud'      => $row['longitud'],
      'direccion'     => $row['direccion'],
      'calle'         => $row['calle'],
      'carrera'       => $row['carrera'],
      'barrio'        => $row['barrio'],
      'ciudad'        => $row['ciudad'],
      'esSobrecupo'   => $row['esSobrecupo'],
      'direccion'     => $row['direccion'],
      'cupos'         => $row['cupos'],
      'cuposDiscapacitados' => $row['cuposDiscapacitados'],
      'cuposDisponibles'    => $row['cuposDisponibles'],
      'esTarifaPlana'       => $row['esTarifaPlana'],
      'valorTarifaPlana'    => $row['valorTarifaPlana'],
      'valorTarifaCarro'    => $row['valorTarifaCarro'],
      'valorTarifaMoto'     => $row['valorTarifaMoto'],
      'valorTarifaBici'     => $row['valorTarifaBici']
    );
    array_push($json, $bus);
  }
} else {
  echo "0 resultados";
}
// Codifica en formato JSON
$jsonstring = json_encode($json, JSON_UNESCAPED_UNICODE);
echo $jsonstring;

// Cierra la conexión
$conn->close();
?>
