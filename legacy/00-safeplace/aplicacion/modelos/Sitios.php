<?php
class Sitios extends CActiveRecord {

    protected function fijarNombre(): string {
        return 'sitios';
    }

    protected function fijarTabla():string {
        return "vista_sitios";
    }
    
    protected function fijarId():string {
        return "cod_sitio";
    }

    protected function fijarAtributos(): array {
        return array(
            "cod_sitio", "coor_x", "coor_y", "nombre_sitio", 
            "direccion", "poblacion", "cp", "provincia", "pais",
            "descripcion", "contacto", "foto", "alta", "alta_por",
            "borrado", "fecha_borrado", "borrado_por", 
            "puntuacion", "nombre_alta", "nombre_baja"
        );
    }

    protected function fijarDescripciones(): array {
        return array(
            "cod_sitio" => "Código Sitio", 
            "coor_x" => "Longitud (X)", 
            "coor_y" => "Latitud (Y)", 
            "nombre_sitio" => "Nombre del sitio",
            "direccion" => "Dirección", 
            "poblacion" => "Población", 
            "cp" => "Código Postal", 
            "provincia" => "Provincia/Región", 
            "pais" => "País/Estado",
            "descripcion" => "Descripción del sitio", 
            "contacto" => "Teléfono y/o mail de contacto", 
            "foto" => "Foto", 
            "alta" => "Fecha del alta", 
            "alta_por" => "Cod alta por",
            "borrado" => "¿Borrado?", 
            "fecha_borrado" => "Fecha del borrado", 
            "borrado_por" => "Cod Borrado por",
            "puntuacion" => "Puntuación", 
            "nombre_alta" => "Dado de alta por", 
            "nombre_baja" => "Borrado por"
        );
    }

    protected function fijarRestricciones(): array {
        return
            array(
                array(
                    "ATRI" => "coor_x,coor_y,nombre_sitio,direccion",
                    "TIPO" => "REQUERIDO"
                ),
                array(
                    "ATRI" => "cod_sitio", "TIPO" => "ENTERO", "MIN" => 0
                ),
                array(
                    "ATRI" => "coor_x", "TIPO" => "REAL"
                ),
                array(
                    "ATRI" => "coor_y", "TIPO" => "REAL"
                ),
                array(
                    "ATRI" => "nombre_sitio", "TIPO" => "CADENA", "TAMANIO" => 120
                ),
                array(
                    "ATRI" => "direccion", "TIPO" => "CADENA", "TAMANIO" => 255
                ),
                array(
                    "ATRI" => "poblacion", "TIPO" => "CADENA", "TAMANIO" => 60
                ),
                array(
                    "ATRI" => "cp", "TIPO" => "CADENA", "TAMANIO" => 6
                ),
                array(
                    "ATRI" => "provincia", "TIPO" => "CADENA", "TAMANIO" => 60
                ),
                array(
                    "ATRI" => "pais", "TIPO" => "CADENA", "TAMANIO" => 60
                ),
                array(
                    "ATRI" => "descripcion", "TIPO" => "CADENA", "TAMANIO" => 480
                ),
                array(
                    "ATRI" => "contacto", "TIPO" => "CADENA", "TAMANIO" => 255
                ),
                array(
                    "ATRI" => "foto", "TIPO" => "CADENA", "TAMANIO" => 255
                ),
                array( 
                    "ATRI" => "alta", "TIPO" => "FECHA", "DEFECTO" => date("d/m/Y H:i:s")
                ),
                array("ATRI" => "alta", "TIPO" => "FUNCION",
                    "FUNCION" => "validaFechaAlta"
                ),
                array(
                    "ATRI" => "alta_por", "TIPO" => "ENTERO", "MIN" => 0
                ),
                array(
                    "ATRI" => "borrado", "TIPO" => "ENTERO",
                    "RANGO" => [0, 1], "DEFECTO" => 0
                ),
                array( 
                    "ATRI" => "fecha_borrado", "TIPO" => "FECHA"
                ),
                array(
                    "ATRI" => "borrado_por", "TIPO" => "ENTERO", "MIN" => 0,
                )
            );
    }

   

    /**
     * Undocumented function
     *
     * @param integer|null $cod_sit
     * @return mixed
     */
    public static function dameSitios(?int $cod_sit = null) : mixed {

        $sentencia = "SELECT * from sitios;";

        $consulta=Sistema::App()->BD()->crearConsulta($sentencia);
    
        $filas=$consulta->filas();

        if (is_null($filas))
            return false;

        $sitios = [];

        foreach ($filas as $fila) {
            $sitios[intval($fila["cod_sitio"])] = $fila;
        }

        if ($cod_sit === null)
            return $sitios;
        else {
            if (isset($sitios[$cod_sit]))
                return $sitios[$cod_sit];
            else
                return false;
        }
    }

    /**
     * Undocumented function
     *
     * @param integer|null $cod_sit
     * @return mixed
     */
    public static function dameSitiosPorNombre(string $nombre) : mixed {

        $sentencia = "SELECT * from vista_sitios ".
        "where upper(nombre_sitio) like upper('%$nombre%')";

        $consulta=Sistema::App()->BD()->crearConsulta($sentencia);
    
        $filas=$consulta->filas();

        if (is_null($filas))
            return false;

        $sitios = [];

        if (count($filas) == 1) {
            foreach ($filas as $fila) {
                $sitios = $fila;
            }
    
            $id = intval($sitios["cod_sitio"]);
            $sitios["caracteristicas"] = Caracteristicas::dameCaracteristicasDelSitio($id);
            $sitios["categorias"] = Categorias::dameCategoriasDelSitio($id);
            $sitios["comunidades"] = Comunidades::dameComunidadesDelSitio($id);
    
        } else {
            foreach ($filas as $fila) {
                $sitios[intval($fila["cod_sitio"])] = $fila;
            }
        }

        return $sitios;
    }

     /**
     * Undocumented function
     *
     * @param integer|null $cod_sit
     * @return mixed
     */
    public static function dameSitioPorCoordenadas(string $coor_x, string $coor_y) : mixed {

        $sentencia = "SELECT * from vista_sitios where coor_x = $coor_x and coor_y =$coor_y;";

        $consulta=Sistema::App()->BD()->crearConsulta($sentencia);
    
        $filas=$consulta->filas();

        if (!$filas) return false;

        foreach ($filas as $fila) {
            $sitio = $fila;
        }

        $id = intval($sitio["cod_sitio"]);

        $sitio["caracteristicas"] = Caracteristicas::dameCaracteristicasDelSitio($id);
        $sitio["categorias"] = Categorias::dameCategoriasDelSitio($id);
        $sitio["comunidades"] = Comunidades::dameComunidadesDelSitio($id);

        return $sitio;

    }

    /**
     * Función para borrar un sitio. Le asigna fecha de borrado y el código de quien lo ha hecho
     */
    public static function borrarSitio(int $cod_sit, int $cod_borrador) : bool {

        $sentencia = "UPDATE sitios ".
            "SET borrado = 1, ".
            "fecha_borrado = CURRENT_TIMESTAMP, ".
            "borrado_por = $cod_borrador ".
            "WHERE cod_sitio = $cod_sit;";

            $consulta=Sistema::App()->BD()->crearConsulta($sentencia);

            if ($consulta->error())
                return false;

            return true;
    }

    /**
     * Función para recuperar un Sitio. Le asigna una nueva fecha de alta y el código de quien lo ha hecho,
     * y borra los datos de quien lo borró
     */
    public static function recuperarSitio(int $cod_sit, int $cod_recuperador) : bool {

        $sentencia = "UPDATE sitios ".
            "SET alta = CURRENT_TIMESTAMP, ".
            "alta_por = $cod_recuperador, ".
            "borrado = 0, ".
            "fecha_borrado = NULL, ".
            "borrado_por = 0 ".
            "WHERE cod_sitio = $cod_sit;";

            $consulta=Sistema::App()->BD()->crearConsulta($sentencia);

            if ($consulta->error())
                return false;

            return true;
    }

    protected function afterCreate(): void {

        $this->cod_usuario = 0;
        $this->coor_x = 1;
        $this->coor_y = 1;
        $this->nombre_sitio = "";
        $this->direccion = "";
        $this->poblacion = "";
        $this->cp = "";
        $this->provincia = "";
        $this->pais = "";
        $this->descripcion = "";
        $this->contacto = "";
        $this->foto = "fotoSitioPorDefecto.png";
        $this->alta = date("d/m/Y H:i:s");
        $this->alta_por = 0;
        $this->borrado = 0;
        $this->fecha_borrado = date("d/m/Y H:i:s");
        $this->borrado_por = 0;
    }

    protected function afterBuscar(): void {

        $fecha = $this->alta;
        $fecha = CGeneral::fechahoraMysqlANormal($fecha);
        $this->alta = $fecha;

        if ($this->borrado == 1) {
            $fecha = $this->fecha_borrado;
            $fecha = CGeneral::fechahoraMysqlANormal($fecha);
            $this->fecha_borrado = $fecha;
        }

    }

    function fijarSentenciaInsert(): string {

        $coor_x = floatval($this->coor_x);
        $coor_y = floatval($this->coor_x);
        $nombre_sitio = CGeneral::addSlashes($this->nombre_sitio);
        $direccion = CGeneral::addSlashes($this->direccion);
        $poblacion = CGeneral::addSlashes($this->poblacion);
        $cp = CGeneral::addSlashes($this->cp);
        $provincia = CGeneral::addSlashes($this->provincia);
        $pais = CGeneral::addSlashes($this->pais);
        $descripcion = CGeneral::addSlashes($this->descripcion);
        $contacto = CGeneral::addSlashes($this->contacto);
        $foto = CGeneral::addSlashes($this->foto);
        $alta_por = intval($this->alta_por);
        

        $sentencia = "INSERT INTO sitios ". 
            "(coor_x, coor_y, nombre_sitio, direccion, poblacion, cp, provincia, pais, ".
            "descripcion, contacto, foto, alta, alta_por, borrado, fecha_borrado, borrado_por)". 
            " VALUES ('$coor_x', '$coor_y','$nombre_sitio', '$direccion', ".
            "'$poblacion', '$cp', '$provincia', '$pais'. '$descripcion', '$contacto', '$foto', ".
            "CURRENT_TIMESTAMP, $alta_por, 0, NULL, 0); ";

        return $sentencia;
    }

    function fijarSentenciaUpdate(): string {

        $cod_sitio = intval($this->cod_sitio);
        $coor_x = floatval($this->coor_x);
        $coor_y = floatval($this->coor_x);
        $nombre_sitio = CGeneral::addSlashes($this->nombre_sitio);
        $direccion = CGeneral::addSlashes($this->direccion);
        $poblacion = CGeneral::addSlashes($this->poblacion);
        $cp = CGeneral::addSlashes($this->cp);
        $provincia = CGeneral::addSlashes($this->provincia);
        $pais = CGeneral::addSlashes($this->pais);
        $descripcion = CGeneral::addSlashes($this->descripcion);
        $contacto = CGeneral::addSlashes($this->contacto);
        $foto = CGeneral::addSlashes($this->foto);

        $sentencia = "UPDATE sitios ".
            "SET coor_x = '$coor_x', coor_y = '$coor_y', ".
            "nombre_sitio = '$nombre_sitio', ".
            "direccion = '$direccion', poblacion = '$poblacion', ".
            "cp = '$cp', provincia = '$provincia', pais = '$pais',".
            "descripcion = '$descripcion', contacto = '$contacto', ".
            "foto = '$foto' ".
            "WHERE cod_sitio = $cod_sitio;";
     
        return $sentencia;
    }
}