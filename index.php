<?php
//Declaramos variables con los parametros para conexión a la base de datos
$host_db = "localhost";
$usuario_db = "root";
$password_db = "";
$nombre_db = "api_web_db";

//CREAMOS UN NUEVO OBJETO DE CONEXION CON LA BASE DE DATOS CON LOS PARAMETROS ANTERIORES
$conexion = new mysqli($host_db, $usuario_db, $password_db, $nombre_db);

//COMPROBAMOS QUE LA CONEXION ESTE FUNCIONANDO CORRECTAMENTE
if ($conexion -> connect_error) {
    die ("Conexión no establecida" . $conexion->connect_error);
}


header("Content-Type: application/json"); //GENERAMOS EL FORMATO DE DOCUMENTO DE RESPUESTA DE LA API
$metodo = $_SERVER['REQUEST_METHOD']; //NOS MUESTRA QUE METODOS SE ESTAN UTILIZANDO EN ESTE MOMENTO

switch ($metodo) {
    case 'GET': //SELECT
        consulta_select($conexion);
        break;
    case 'POST': //INSERT 
        echo 'Consulta de registros - POST';
        break;
    case 'PUT': //UPDATE 
        echo 'Consulta de registros - PUT';
        break;
    case 'DELETE': //DELETE 
        echo 'Consulta de registros - DELETE';
        break;
    default: //SI NO ES NINGUNO DE LOS METODOS ANTERIORES ENMASCARA TODOS LOS DEMAS EN UN ERROR
        echo 'Método no permitido';
        break;
}

function consulta_select($conexion){
    $sql = "SELECT * FROM usuario";
    $resultado = $conexion -> query($sql);

    if ($resultado) {
        $datos = array();
        while ($fila = $resultado -> fetch_assoc()) {
            $datos[] = $fila; 
        }

        echo json_encode($datos);
    }
}




?>