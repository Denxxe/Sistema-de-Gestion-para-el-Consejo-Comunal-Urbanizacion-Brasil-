<?php
require"../models/user.php";

$option=$_REQUEST['option'];

switch($option){

    case 'crear':
        date_default_timezone_set('America/Caracas');
        $usuario=new UserModel();
        $nombre_usuario=$_REQUEST['username'];
        $contrasena=$_REQUEST['password'];
        $nombre=$_REQUEST['nombre'];
        $apellido=$_REQUEST['apellido'];
        $cedula=$_REQUEST['cedula'];
        $tipo_usuario=$_REQUEST['tipo_usuario'];
        $id_persona = $_REQUEST['id_persona'];

        
        $usuario->setName(  name: $nombre_usuario);

        $usuario->setPassword($contrasena);
        $usuario->setCreationAcount(date(    format: "Y-m-d"));
        $usuario->setTipo($tipo_usuario);


    
        $resultado=$usuario->guardar_usuario();
        
    break;


    case 'obtener':
        $usuario=new usuario();

        if(isset($_REQUEST['nombre_usuario'])){
            $usuario->set_nombre_usuario($_REQUEST['nombre_usuario']);
        }
        if(isset($_REQUEST['id_usuario'])){
            $usuario->set_id_usuario(($_REQUEST['id_usuario']));
        }
        if(isset($_REQUEST['cedula'])){
            $usuario->setCedula(($_REQUEST['cedula']));
        }

        if(isset($_REQUEST['extraer_todos'])){
            if($_REQUEST['extraer_todos']=='true'){
               $todos=true; 
            }
            else{
               $todos=false;
            }
        }else{
            $todos=false;
        }

        //-----------------paginacion
        if((isset($_REQUEST['pagina']))&&(isset($_REQUEST['num_resultados']))){
            $pagina=$_REQUEST['pagina'];//Pagina actual en la paginacion
            $num_resultados=$_REQUEST['num_resultados'];
        }else{
            $pagina=false;
            $num_resultados=false;
        }
        //-----------------paginacion

        $resultado=$usuario->getUsuarios($pagina,$num_resultados,$todos);
        $resultado=json_encode($resultado);
        echo $resultado;
    break;

    case 'eliminar':
        $id_usuario=$_REQUEST['id_usuario'];
        $usuario=new UserModel();
        $usuario->set_id_usuario($id_usuario);
        $resultado=$usuario->eliminar_usuario();
        if($resultado){
            $resultado=json_encode($resultado);
            echo $resultado;
        }
    break;

    case 'modificar':
        $usuario=new UserModel();
        $id_usuario=$_REQUEST['id_usuario'];
        $tipo_usuario=$_REQUEST['tipo_usuario'];
        $nombre_personal=$_REQUEST['nombre_personal'];
        $apellido_personal=$_REQUEST['apellido_personal'];
        $cedula=$_REQUEST['cedula'];
        $departamento_usuario=$_REQUEST['departamento'];
        $usuario->set_tipoUsuario($tipo_usuario);
        $usuario->set_id_usuario($id_usuario);
        $usuario->set_departamento_usuario($departamento_usuario);
        $usuario->setNombrePersona(strtoupper($nombre_personal));
        $usuario->setApellidoPersona(strtoupper($apellido_personal));
        $usuario->setCedula($cedula);
        $resultado=$usuario->modificar_usuario();
        if($resultado){
            header('location:../View/usuarios-administrar.php');
            exit();
        }
    break;

    case 'cambiar_clave':
        
        $usuario=new UserModel();
        $id_usuario=$_REQUEST['id_usuario'];
        $contrasena=$_REQUEST['password'];
        $usuario->set_contrasena($contrasena);
        $usuario->set_id_usuario($id_usuario);
        $resultado=$usuario->cambiar_clave();
        if($resultado){
            header('location:../View/usuarios-administrar.php');
            exit();
        }

    break;

    case 'contarRegistros':

        $usuario=new UserModel();

        if(isset($_REQUEST['nombre_usuario'])){
            $usuario->set_nombre_usuario($_REQUEST['nombre_usuario']);
        }
        if(isset($_REQUEST['id_usuario'])){
            $usuario->set_id_usuario(($_REQUEST['id_usuario']));
        }
        if(isset($_REQUEST['tipo_usuario'])){
            $usuario->set_tipoUsuario(($_REQUEST['tipo_usuario']));
        }
        if(isset($_REQUEST['cedula'])){
            $usuario->setCedula(($_REQUEST['cedula']));
        }
        if(isset($_REQUEST['extraer_todos'])){
            if($_REQUEST['extraer_todos']=='true'){
               $todos=true; 
            }
            else{
               $todos=false;
            }
        }
        else{
            $todos=false;
        }
        
        $resultado=$usuario->contarNumRegistros($todos);

        if($resultado){
            $resultado=json_encode($resultado);
            echo $resultado;
        }else{
            echo $resultado;
        }

    break;
    
    case 'login':
        $usuario=new UserModel();
        $db = DataBase::getInstance();  
        if ($db==null){
            echo "Prueba numero 2, no hay coneccion";
            header('location:../View/login.php');
            exit();
        }else{    
        $nombre_usuario=$_REQUEST['username'];
        $contrasena=$_REQUEST['password'];
        $resultado=$usuario->login($nombre_usuario,$contrasena);
        }
    break;
    
    case 'cerrarSesion':
        $usuario=new UserModel();
        $resultado=$usuario->cerrarSesion();
    break;

    case 'exportarExcel':
        $usuario=new UserModel();
        if(isset($_REQUEST['todos_registros'])){
            $usuario->exportarEXCEL(true);
        }else{
            $usuario->exportarEXCEL();
        }
    break;

}

?>