<?php

namespace Model;

class Libros extends ActiveRecord { 
    
    public static $tabla = 'libros';
    public static $columnasDB = [
        'titulo',
        'autor',
        'descripcion',
        'fecha_registro',
        'situacion'
    ];

    public static $idTabla = 'id';
    public $id;
    public $titulo;
    public $autor;
    public $descripcion;
    public $fecha_registro;
    public $situacion;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->autor = $args['autor'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->fecha_registro = $args['fecha_registro'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }
}


