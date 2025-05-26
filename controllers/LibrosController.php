<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Libro;
use Model\Prestamo;
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

        $_POST['titulo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['titulo']))));

        $cantidad_titulo = strlen($_POST['titulo']);

        if ($cantidad_titulo < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de digitos que debe de contener el titulo debe de ser mayor a dos'
            ]);
            return;
        }

        $_POST['autor'] = ucwords(strtolower(trim(htmlspecialchars($_POST['autor']))));

        $cantidad_autor = strlen($_POST['autor']);

        if ($cantidad_autor < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de digitos que debe de contener el autor debe de ser mayor a dos'
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
                'mensaje' => 'Exito el libro ha sido registrado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarLibrosAPI()
    {
        try {
            $condiciones = ["situacion = 1"];

            $where = implode(" AND ", $condiciones);

            $sql = "SELECT * FROM libros WHERE $where";
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
        $_POST['titulo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['titulo']))));

        $cantidad_titulo = strlen($_POST['titulo']);

        if ($cantidad_titulo < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de digitos que debe de contener el titulo debe de ser mayor a dos'
            ]);
            return;
        }

        $_POST['autor'] = ucwords(strtolower(trim(htmlspecialchars($_POST['autor']))));

        $cantidad_autor = strlen($_POST['autor']);

        if ($cantidad_autor < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de digitos que debe de contener el autor debe de ser mayor a dos'
            ]);
            return;
        }

        try {
            $data = Libro::find($id);
            $data->sincronizar([
                'titulo' => $_POST['titulo'],
                'autor' => $_POST['autor'],
                'descripcion' => $_POST['descripcion'],
                'situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La informacion del libro ha sido modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
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
                'mensaje' => 'El registro ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Eliminar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function librosDisponiblesAPI()
    {
        try {
            $condiciones = ["situacion = 1"];

            $where = implode(" AND ", $condiciones);

            $sql = "SELECT * FROM libros WHERE $where";
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

        $_POST['observaciones'] = htmlspecialchars($_POST['observaciones']);

        try {
            $data = new Prestamo([
                'id_libro' => $_POST['id_libro'],
                'id_estado' => 1,
                'fecha_prestamo' => date('Y-m-d H:i:s'),
                'fecha_devolucion' => null,
                'observaciones' => $_POST['observaciones'],
                'situacion' => 1
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Exito el prestamo ha sido registrado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
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
                'mensaje' => 'Prestamos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los prestamos',
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
                'id_estado' => 2,
                'situacion' => 0
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El libro ha sido devuelto correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al devolver',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}