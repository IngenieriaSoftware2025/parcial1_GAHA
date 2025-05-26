<?php

namespace Model;

class Prestamo extends ActiveRecord {

    public static $tabla = 'prestamos';
    public static $columnasDB = [
        'id_libro',
        'id_estado',
        'fecha_prestamo',
        'fecha_devolucion',
        'observaciones',
        'situacion'
    ];

    public static $idTabla = 'id';
    public $id;
    public $id_libro;
    public $id_estado;
    public $fecha_prestamo;
    public $fecha_devolucion;
    public $observaciones;
    public $situacion;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->id_libro = $args['id_libro'] ?? null;
        $this->id_estado = $args['id_estado'] ?? null;
        $this->fecha_prestamo = $args['fecha_prestamo'] ?? '';
        $this->fecha_devolucion = $args['fecha_devolucion'] ?? '';
        $this->observaciones = $args['observaciones'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }
}