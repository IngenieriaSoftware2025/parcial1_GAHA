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
public static function librosDisponiblesAPI()
    {
        try {
            $sql = "SELECT * FROM libros WHERE situacion = 1 ORDER BY titulo";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Libros disponibles obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los libros disponibles',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function guardarPrestamoAPI()
    {
        getHeadersApi();

        $_POST['id_libro'] = filter_var($_POST['id_libro'], FILTER_VALIDATE_INT);

        if ($_POST['id_libro'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un libro válido'
            ]);
            return;
        }

        $_POST['nombre_persona'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre_persona']))));

        if (strlen($_POST['nombre_persona']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la persona debe tener al menos 2 caracteres'
            ]);
            return;
        }

        $_POST['observaciones'] = htmlspecialchars($_POST['observaciones']);

        try {
            $data = new Prestamo([
                'id_libro' => $_POST['id_libro'],
                'id_estado' => 1, // Estado prestado
                'fecha_prestamo' => date('Y-m-d H:i:s'),
                'fecha_devolucion' => null,
                'observaciones' => $_POST['observaciones'],
                'situacion' => 1
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El préstamo ha sido registrado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el préstamo',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarPrestamosAPI()
    {
        try {
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

            $condiciones = ["p.situacion >= 0"];

            if ($fecha_inicio) {
                $condiciones[] = "p.fecha_prestamo >= '{$fecha_inicio} 00:00:00'";
            }

            if ($fecha_fin) {
                $condiciones[] = "p.fecha_prestamo <= '{$fecha_fin} 23:59:59'";
            }

            $where = implode(" AND ", $condiciones);

            $sql = "SELECT p.*, l.titulo, l.autor, e.nombre as estado_nombre 
                    FROM prestamos p 
                    INNER JOIN libros l ON p.id_libro = l.id 
                    INNER JOIN estados_prestamo e ON p.id_estado = e.id
                    WHERE $where 
                    ORDER BY p.fecha_prestamo DESC";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Préstamos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los préstamos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function marcarDevueltoAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $data = Prestamo::find($id);
            $data->sincronizar([
                'fecha_devolucion' => date('Y-m-d H:i:s'),
                'id_estado' => 2, // Estado devuelto
                'situacion' => 0
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El libro ha sido marcado como devuelto correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al marcar como devuelto',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}


