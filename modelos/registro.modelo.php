<?php

require_once "conexion.php";


class ModeloRegistroFreeLancer{

    

    static public function mdlBuscarCodigo($clave, $nombre, $apellido, $tipo_documento, $identificacion, $dia_nacimiento, $mes_nacimiento, 
    $anio_nacimiento, $genero, $direccion, $ciudad, $telefono, $celular, $correo_electronico, $contrasena, $confirmar_contrasena, $accion, $tabla, $item){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE usu_documento = :identificacion");
        $stmt->bindParam(":identificacion", $identificacion);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo "Nombre: ".$nombre, " Apellido: ".$apellido, " Direccion: ".$direccion, " Ciudad: ".$ciudad, " Usuario: ".$identificacion, "Tipo de documento: ".$tipo_documento, " Password: ".$confirmar_contrasena, " Genero: ".$genero, " Telefono: ".$telefono, " Email: ".$correo_electronico, " Fecha de nacimiento: ".$dia_nacimiento, " Tabla: ".$tabla, " Clave: ".$clave;
        // die();
        if ($resultado && is_array($resultado)) {
            // Comparar la clave con la registrada en la base de datos
            if ($resultado['tokenGuest'] === $clave) {
                $registro = new ModeloRegistroFreeLancer();
                $response = $registro->mdlRegistrarFreeLancer($nombre, $apellido, $direccion, $ciudad, $identificacion, $confirmar_contrasena, $tipo_documento, $genero, $celular, $correo_electronico, $dia_nacimiento, $mes_nacimiento, $anio_nacimiento, $tabla, $clave);
            } else {
                $response = array('error' => 'numero de documento no asignado a este token', "statusCode" => 221);
                $jsonResponse = json_encode($response);
                echo $jsonResponse;
            }
        } else {
            $response = array('error' => 'No se encuentra el usuario');
            $jsonResponse = json_encode($response);
            echo $jsonResponse;
        }       
    }

    public function arrayToDate(array $array) {
        return DateTime::createFromFormat("d/m/Y", implode(" ", $array));
    }

    static public function mdlRegistrarFreeLancer($nombre, $apellido, $direccion, $ciudad, $usuario, $password, $tipodocumento, $genero, $telefono, $email, $dia_nacimiento, $mes_nacimiento, $anio_nacimiento, $tabla, $clave) {
            
        $id_rol = '19';
        $id_intermediario = '3';
        $usu_cargo = 'Freelance';
        $usu_estado = '1';
        $fecha_nacimiento = $anio_nacimiento . '-' . $mes_nacimiento . '-' . $dia_nacimiento;
        // Obtener la fecha actual en formato timestamp
        date_default_timezone_set("America/Bogota");
        $timestamp = date("U");
        $fecha = date("Y-m-d H:i:s", $timestamp);
        $encriptar_password = crypt($password, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

        //echo "Nombre: ".$nombre, "Apellido: ".$apellido, "Direccion: ".$direccion, "Ciudad: ".$ciudad, "Usuario: ".$usuario, "Password: ".$encriptar_password, "Genero: ".$genero, "Telefono: ".$telefono, "Email: ".$email, "Fecha de nacimiento: ".$fecha_nacimiento, "Tabla: ".$tabla, "Clave: ".$clave;   
    
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET usu_nombre = :nombre, usu_apellido = :apellido, usu_direccion = :usu_direccion, tipos_documentos_id	 = :tipodocumento, ciudades_id = :ciudad, usu_usuario = :usuario, usu_password = :password, usu_genero = :genero, usu_telefono = :telefono, usu_email = :email, usu_fch_creacion = :fch_creacion, usu_estado = :usu_estado, usu_cargo = :usu_cargo, id_rol = :id_rol, id_Intermediario = :id_intermediario, usu_fch_nac = DATE(:fecha_nacimiento) WHERE tokenGuest = :clave");
    
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':usu_direccion', $direccion);
        $stmt->bindParam(':ciudad', $ciudad);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':tipodocumento', $tipodocumento);
        $stmt->bindParam(':password', $encriptar_password);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':fch_creacion', $fecha);
        $stmt->bindParam(':usu_estado', $usu_estado);
        $stmt->bindParam(':usu_cargo', $usu_cargo);
        $stmt->bindParam(':id_rol', $id_rol);
        $stmt->bindParam(':id_intermediario', $id_intermediario);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':clave', $clave);
        
        if ($stmt->execute()) {
            $registro = new ModeloRegistroFreeLancer();
            $response = $registro->mdlEliminarToken($usuario, $tabla);
        } else {
            return "Error de conexión: " . $stmt->errorInfo()[2];
        }
    }
 
    static public function mdlEliminarToken($usuario, $tabla){

      
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET tokenGuest = NULL WHERE usu_usuario = :usuario");
		$stmt->bindParam(':usuario', $usuario);
        
        if($stmt->execute()){
        $response = array('success' => 'Registro exitoso');
        $jsonResponse = json_encode($response);
        echo $jsonResponse;
        }else{
            echo 'error eliminando tokenGuest';
        }

    }
    
}