<?php 
include('../../Config/Config.php');
$usuario = new usuario($Conexion);

$proceso = '';
if( isset($_GET['proceso']) && strlen($_GET['proceso'])>0 ){
	$proceso = $_GET['proceso'];
}
$usuario->$proceso( $_GET['usuario'] );
print_r(json_encode($usuario->respuesta));

class usuario{
    private $datos = array(), $db;
    public $respuesta = ['msg'=>'correcto'];
    
    public function __construct($db){
        $this->db=$db;
    }
    public function recibirDatos($usuario){
        $this->datos = json_decode($usuario, true);
        $this->validar_datos();
    }
    private function validar_datos(){
        if( empty($this->datos['nombre']) ){
            $this->respuesta['msg'] = 'por favor ingrese su Nombre';
        }
        if( empty($this->datos['apellido']) ){
            $this->respuesta['msg'] = 'por favor ingrese su Apellido';
        }
        if( empty($this->datos['correo']) ){
            $this->respuesta['msg'] = 'por favor ingrese el correo';
        }
        if( empty($this->datos['password'])){
            $this->respuesta['msg'] = 'por favor ingrese una contraseña ';
        }
       
        $this->almacenar_usuario();
    }
    private function almacenar_usuario(){
        if( $this->respuesta['msg']==='correcto' ){
            if( $this->datos['accion']==='nuevo' ){
                $this->db->consultas('
                    INSERT INTO usuario (Nombre,Apellido,Correo,password,Direccion) VALUES(
                        "'. $this->datos['Nombre'] .'",
                        "'. $this->datos['Apellido'] .'",
                        "'. $this->datos['Correo'] .'",
                        "'. $this->datos['password'] .'",
                        "'. $this->datos['Direccion'] .'"
                    )
                ');
                $this->respuesta['msg'] = 'Registro insertado correctamente';
            } else if( $this->datos['accion']==='modificar' ){
                $this->db->consultas('
                   UPDATE usuario SET
                        Nombre  = "'. $this->datos['Nombre'] .'",
                        Apellido  = "'. $this->datos['Apellido'] .'",
                        Correo  = "'. $this->datos['Correo'] .'",
                        password  = "'. $this->datos['password'] .'",
                        Direccion  = "'. $this->datos['Direccion'] .'"
                    WHERE idusuario = "'. $this->datos['idusuario'] .'"
                ');
                $this->respuesta['msg'] = 'Registro actualizado correctamente';
            }
        }
    }

    // public function eliminarDocente($idDoncente=''){
    
    //         $this->db->consultas( '
    //             delete docentes
    //             from docentes
    //             where docentes.idDocente = "'.$idDoncente.'"
    //         ');
           
    //             $this->respuesta['msg'] = 'Registro eliminado correctamente';
      
    // }
 
    
}
?>