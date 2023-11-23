<?php
//DECLARAMOS VARIABLES CON LOS PARAMETROS PARA CONEXIÓN A LA BASE DE DATOS

//Hoy 23-11-2023 se hace una prueba de programar en el iPad
$host_db = "localhost";
$usuario_db = "root";
$password_db = "";
$nombre_db = "api_web_db";

//CREAMOS UN NUEVO OBJETO DE CONEXION CON LA BASE DE DATOS CON LOS PARAMETROS ANTERIORES
$conexion = new mysqli($host_db, $usuario_db, $password_db, $nombre_db);

//COMPROBAMOS QUE LA CONEXION ESTE FUNCIONANDO CORRECTAMENTE
if ($conexion -> connect_error) { //SI CONEXIÓN VIENE CON UN ERROR ENTONCES MUESTRE EL ERROR
    die ("Conexión no establecida" . $conexion->connect_error);
}


header("Content-Type: application/json"); //GENERAMOS EL FORMATO DE DOCUMENTO DE RESPUESTA DE LA API EN ESTE CASO JSON
$metodo = $_SERVER['REQUEST_METHOD']; //NOS MUESTRA QUE METODOS SE ESTAN UTILIZANDO EN ESTE MOMENTO

//OBTENEMOS EL ID POR MEDIO DE LA RUTA O URL
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO']:'/'; //PREGUNTAMOS SI VIENE ALGO EN EL PATH_INFO Y SI VIENE LO ASIGNAMOS A LA VARIABLE PATH Y LE AGREGAMOS UN / AL FINAL
$buscar_id = explode('/', $path); //BUSCAMOS Y EXTRAEMOS EL ID QUE VIENE EN LA URL
$id = ($path!=='/') ? end($buscar_id):null; //ASIGNAMOS EL ID QUE VIENE EN LA URL A LA VARIABLE ID

switch ($metodo) { //POR MEDIO DEL CASE IDENTIFICAMOS EL TIPO DE PETICIÓN (GET, POST, PUT, DELETE O DEFAULT PARA LAS DEMÁS)
    case 'GET': //SELECT
        consultar($conexion, $id); //INVOCAMOS LA FUNCIÓN CONSULTAR
        break;
    case 'POST': //INSERT 
        insertar($conexion); //INVOCAMOS LA FUNCIÓN INSERTAR
        break;
    case 'PUT': //UPDATE 
        actualizar($conexion, $id); //INVOCAMOS LA FUNCIÓN ACTUALIZAR
        break;
    case 'DELETE': //DELETE 
        borrar($conexion, $id); //INVOCAMOS LA FUNCIÓN BORRAR
        break;
    default: //SI NO ES NINGUNO DE LOS METODOS ANTERIORES ENMASCARA TODOS LOS DEMAS EN UN ERROR
        echo 'Método no permitido';
        break;
}

//FUNCIÓN PARA CONSULTAR A LA BASE DE DATOS
function consultar($conexion, $id){
    //FORMA LARGA
    // if (isset($id)) {
    //     $sql = "SELECT * FROM usuario WHERE id_usuario = " . $id;
    // }else {
    //     $sql = "SELECT * FROM usuario";
    // }

    //FORMA CORTA
    $sql = ($id===null) ? "SELECT * FROM usuario" : "SELECT * FROM usuario WHERE id_usuario = " . $id;
    
    $resultado = $conexion -> query($sql);

    if ($resultado) {
        $datos = array();
        while ($fila = $resultado -> fetch_assoc()) {
            $datos[] = $fila; 
        }

        echo json_encode($datos);
    }

}

//FUNCIÓN PARA INSERTAR REGISTROS A LA BASE DE DATOS
function insertar($conexion){
    $datos = json_decode(file_get_contents('php://input'),true);
    $nombre = $datos['nombre_usuario'];
    
    $sql = "INSERT INTO usuario(nombre_usuario) VALUES('$nombre')";
    $resultado = $conexion -> query($sql);

    if ($resultado) {
        $datos['id'] = $conexion -> insert_id;
        echo json_encode($datos);
    }else {
        echo json_encode(array('error'=>'Error al crear usuario'));
    }
}

//FUNCIÓN PARA BORRAR REGISTROS DE LA BASE DE DATOS
function borrar($conexion, $id){
    $sql = "DELETE FROM usuario WHERE id_usuario = " . $id;
    $resultado = $conexion -> query($sql);

    if ($resultado) {
        echo json_encode(array('mensaje' => 'Usuario eliminado. Se ha borrado el usuario con el ID '.$id ));
    }else {
        echo json_encode(array('error' => 'Error al borrar el usuario'));
    }
}

//FUNCIÓN PARA ACTUALIZAR REGISTROS DE LA BASE DE DATOS
function actualizar($conexion, $id){
    $datos = json_decode(file_get_contents('php://input'),true);
    $nombre = $datos['nombre_usuario'];
    
    $sql = "UPDATE usuario SET nombre_usuario = '$nombre' WHERE id_usuario = $id";
    $resultado = $conexion -> query($sql);

    if ($resultado) {
        echo json_encode(array('mensaje' => 'Usuario editado. Se ha editado el usuario con el ID ' . $id . ' y el nombre nuevo es: ' . $nombre));
    }else {
        echo json_encode(array('error' => 'Error al editar el usuario'));
    }
}


?>