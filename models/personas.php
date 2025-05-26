<?php

namespace Model;

class Personas extends ActiveRecord {

    public static $tabla = 'personas';
    public static $columnasDB = [
        'nombre',
        'apellido',
        'fecha_registro',
        'situacion'
    ];

    public static $idTabla = 'id';
    public $id;
    public $nombre;
    public $apellido;
    public $fecha_registro;
    public $situacion;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->fecha_registro = $args['fecha_registro'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }
}