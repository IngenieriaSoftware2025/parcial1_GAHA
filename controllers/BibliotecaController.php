<?php

namespace Controllers;

use Exception;
use Model\Libros;
use Model\Personas;
use Model\Prestamo;
use Model\EstadoPrestamo;
use MVC\Router;

class LibrosController{

    public function renderizarPagina(Router $router)
    {
        $router->render('biblioteca/index', []);
    }

    public function guardarLibroAPI(){
        try {
            if(empty($_POST['titulo']) || empty($_POST['autor'])){
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'el titulo y el autor son obligatorios'
                ]);
                return;
            }

            $_POST['fecha_registro'] = date('Y-m-d H:i:s');
            $_POST['situacion'] = 1;

            $libro = new Libros($_POST);
            $resultado = $libro->crear();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'El libro se guardo correctamente'
                ]);
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al guardar el libro'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

}