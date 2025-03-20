<?php
	 
class indexControlador extends CControlador {

	/**
	 * Variables de entorno
	 */

	public function accionIndex() {

		$acceso = Sistema::app()->Acceso();

		if (!$acceso->hayUsuario()) {
			Sistema::app()->irAPagina(["index", "login"]);
            exit;
		}
		

		if ($acceso->puedePermiso(2)) {
			Sistema::app()->paginaError(502, "No tienes permiso para aceder aquí");
			exit;
		}

		$this->menu = $this->crearMenu($acceso);

		$this->dibujaVista("index", ["acceso" => $acceso],
		"Inicio - CH Manager");
	}


	/**
	 * Acción para la página principal
	 */
	public function accionLogin() {

		//Creamos el modelo
        $login = new Login();

        //Obtenemos el nombre del modelo (fijarNombre) sobre el que actuará el post
        $nombre = $login->getNombre();

        //Comprobamos que existe POST del nombre
        if(isset($_POST[$nombre])) {
            //Asigno los valores de registro segun lo introducido en el formulario
            $login->setValores($_POST[$nombre]);

            //Validamos 
            if ($login->validar())  {
                $acl = Sistema::app()->ACL();
                $acceso = Sistema::app()->Acceso();
                $cod_usuario = $acl->getCodUsuario($login->nick);
                $acceso->registrarUsuario($login->nick, $acl->getNombre($cod_usuario), $acl->getPermisos($cod_usuario));   
            }
        }

        if (Sistema::app()->Acceso()->hayUsuario()) {

			if ($acceso->puedePermiso(2)) {
				Sistema::app()->paginaError(502, "No tienes permiso para aceder aquí");
			}

            Sistema::app()->irAPagina(["index", "index"]);
            exit;
        }  

        $this->dibujaVista("login", array("modelo" => $login), "Login");
			
	}

	public function accionCerrarSesion () {

        Sistema::app()->Acceso()->quitarRegistroUsuario();

        Sistema::app()->irAPagina(["index", "login"]); 
		exit;

    }

	
	/**
	 * Función que genera los links del menú para el header
	 */
	public function crearMenu (CAcceso $acceso) : array {

		$arrayMenu = [];

		$perm3 = false;  $perm4 = false; $perm5 = false; $perm6 = false; $perm7 = false; 

		if ($acceso->puedePermiso(1)) {
			$perm3 = true;  $perm4 = true; $perm5 = true; $perm6 = true; $perm7 = true; 
		} else {
			$perm3 = $acceso->puedePermiso(3);
			$perm4 = $acceso->puedePermiso(4);
			$perm5 = $acceso->puedePermiso(5);
			$perm6 = $acceso->puedePermiso(6);
			$perm7 = $acceso->puedePermiso(7);
		}

		if ($perm3) array_push($arrayMenu, ["texto" => "Sitios", "enlace" => ["sitios"]]);
		if ($perm4) array_push($arrayMenu, ["texto" => "Sugerencias", "enlace" => ["sugerencias"]]);
		if ($perm5) array_push($arrayMenu, ["texto" => "Reportes", "enlace" => ["reportes"]]);
		if ($perm6) array_push($arrayMenu, ["texto" => "Reseñas", "enlace" => ["resenias"]]);
		if ($perm7) array_push($arrayMenu, ["texto" => "Administración", "enlace" => ["admin"]]);

		return $arrayMenu;
	}
	

}



