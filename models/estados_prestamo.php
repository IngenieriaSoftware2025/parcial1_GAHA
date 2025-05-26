<?php

namespace Model;

class EstadoPrestamo extends ActiveRecord {

    public static $tabla = 'estados_prestamo';
    public static $columnasDB = [
        'nombre',
        'descripcion',
        'situacion'
    ];

    public static $idTabla = 'id';
    public $id;
    public $nombre;
    public $descripcion;
    public $situacion;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }
}