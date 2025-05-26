<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Libro;
use Model\Prestamo;
use Model\EstadoPrestamo;
use MVC\Router;

class LibrosController extends ActiveRecord
{
    public function renderizarPagina(Router $router)
    {
        $router->render('libros/index', []);
    }

 public static function guardarLibroAPI()
    {
        getHeadersApi();

        $_POST['titulo'] = htmlspecialchars($_POST['titulo']);
        $_POST['autor'] = htmlspecialchars($_POST['autor']);
        $_POST['descripcion'] = htmlspecialchars($_POST['descripcion']);

        if (strlen($_POST['titulo']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El título debe tener al menos 2 caracteres'
            ]);
            return;
        }

        if (strlen($_POST['autor']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El autor debe tener al menos 2 caracteres'
            ]);
            return;
        }

        try {
            $data = new Libro([
                'titulo' => $_POST['titulo'],
                'autor' => $_POST['autor'],
                'descripcion' => $_POST['descripcion'],
                'fecha_registro' => date('Y-m-d H:i:s'),
                'situacion' => 1
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El libro ha sido registrado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el libro',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarLibrosAPI()
    {
        try {
            $sql = "SELECT * FROM libros WHERE situacion = 1 ORDER BY fecha_registro DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Libros obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los libros',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

 public static function modificarLibroAPI()
    {
        getHeadersApi();

        $id = $_POST['id'];
        $_POST['titulo'] = htmlspecialchars($_POST['titulo']);
        $_POST['autor'] = htmlspecialchars($_POST['autor']);
        $_POST['descripcion'] = htmlspecialchars($_POST['descripcion']);

        if (strlen($_POST['titulo']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El título debe tener al menos 2 caracteres'
            ]);
            return;
        }

        if (strlen($_POST['autor']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El autor debe tener al menos 2 caracteres'
            ]);
            return;
        }

        try {
            $data = Libro::find($id);
            $data->sincronizar([
                'titulo' => $_POST['titulo'],
                'autor' => $_POST['autor'],
                'descripcion' => $_POST['descripcion']
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El libro ha sido modificado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el libro',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

public static function eliminarLibroAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $data = Libro::find($id);
            $data->sincronizar(['situacion' => 0]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El libro ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el libro',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}


