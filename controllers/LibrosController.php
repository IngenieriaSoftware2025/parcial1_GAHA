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

        try {
            if (empty($_POST['titulo']) || empty($_POST['autor'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El titulo del libro y el nombre del autor no puede ir vacio'
                ]);
                return;
            }

            $_POST['descripcion'] = trim(htmlspecialchars($_POST['descripcion'] ?? ''));

            $data = new Libro([
                'titulo' => $_POST['titulo'],
                'autor' => $_POST['autor'],
                'descripcion' => $_POST['descripcion'],
                'fecha_registro' => date('Y-m-d H:i:s'),
                'situacion' => 1
            ]);

            $crear = $data->crear();

            if ($crear['resultado']) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'El libro se guardo correctamente.',
                    'id' => $crear['id']
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Hubo error al guardar el libro'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ocurrio un error',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarLibrosAPI()
    {
        try {
            $sql = "SELECT * FROM libros WHERE situacion = 1";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los libros'
            ]);
        }
    }

    public static function modificarLibroAPI()
    {
        getHeadersApi();

        try {
            if (empty($_POST['id']) || empty($_POST['titulo']) || empty($_POST['autor'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Faltan datos para actualizar el libro'
                ]);
                return;
            }
            $libro = Libro::find($_POST['id']);
            if (!$libro) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontró ese libro'
                ]);
                return;
            }

            $_POST['descripcion'] = trim(htmlspecialchars($_POST['descripcion'] ?? ''));

            $libro->sincronizar($_POST);
            $resultado = $libro->actualizar();

            if ($resultado['resultado']) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'El libro se modifico correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se puede modificar el libro'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ocurrió un error',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarLibroAPI()
    {
        try {
            if (empty($_GET['id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se sabe que libro eliminar'
                ]);
                return;
            }

            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $libro = Libro::find($id);
            if (!$libro) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ese libro no existe'
                ]);
                return;
            }
            $libro->sincronizar(['situacion' => 0]);
            $resultado = $libro->actualizar();

            if ($resultado['resultado']) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'El libro se eliminó correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo eliminar el libro'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ocurrió un error al eliminar',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function librosDisponiblesAPI()
    {
        try {
            $sql = "SELECT id, titulo, autor FROM libros WHERE situacion = 1 ORDER BY titulo";
            $todosLosLibros = self::fetchArray($sql);

            $sqlPrestados = "SELECT DISTINCT id_libro FROM prestamos WHERE id_estado = 1 AND situacion = 1";
            $librosPrestados = self::fetchArray($sqlPrestados);

            $idsPrestados = [];
            foreach ($librosPrestados as $prestado) {
                $idsPrestados[] = $prestado['id_libro'];
            }

            $librosDisponibles = [];
            foreach ($todosLosLibros as $libro) {

                if (!in_array($libro['id'], $idsPrestados)) {
                    $librosDisponibles[] = $libro;
                }
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Libros disponibles obtenidos correctamente',
                'data' => $librosDisponibles
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los libros disponibles',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function guardarPrestamoAPI()
    {
        getHeadersApi();

        try {
            if (empty($_POST['id_libro']) || empty($_POST['observaciones'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Necesitamos saber qué libro y a quién se lo prestás'
                ]);
                return;
            }

            $_POST['id_libro'] = filter_var($_POST['id_libro'], FILTER_VALIDATE_INT);
            if ($_POST['id_libro'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un libro válido'
                ]);
                return;
            }

            $_POST['observaciones'] = trim(htmlspecialchars($_POST['observaciones']));

            $sql = "SELECT id FROM prestamos WHERE id_libro = {$_POST['id_libro']} AND id_estado = 1 AND situacion = 1";
            $prestadoActual = self::fetchArray($sql);

            if (!empty($prestadoActual)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ese libro ya está prestado a alguien más'
                ]);
                return;
            }

            $prestamo = new Prestamo([
                'id_libro' => $_POST['id_libro'],
                'id_estado' => 1,
                'fecha_prestamo' => date('Y-m-d H:i:s'),
                'fecha_devolucion' => null,
                'observaciones' => $_POST['observaciones'],
                'situacion' => 1
            ]);

            $resultado = $prestamo->crear();

            if ($resultado['resultado']) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'El préstamo se registró correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo registrar el préstamo'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el préstamo',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarPrestamosAPI()
    {
        try {
            $sql = "SELECT 
                    p.id,
                    p.fecha_prestamo,
                    p.fecha_devolucion,
                    p.observaciones,
                    p.id_estado,
                    l.titulo,
                    l.autor,
                    e.nombre as estado_nombre
                FROM prestamos p 
                INNER JOIN libros l ON p.id_libro = l.id 
                INNER JOIN estados_prestamo e ON p.id_estado = e.id
                WHERE p.situacion = 1";

            $sql .= " ORDER BY p.fecha_prestamo DESC";
            $prestamos = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Préstamos obtenidos correctamente',
                'data' => $prestamos
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los préstamos',
                'detalle' => $e->getMessage()
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
