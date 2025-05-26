<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\LibrosController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class, 'index']);
$router->get('/libros', [LibrosController::class, 'renderizarPagina']);
$router->post('/libros/guardarAPI', [LibrosController::class, 'guardarLibroAPI']);
$router->get('/libros/buscarAPI', [LibrosController::class, 'buscarLibrosAPI']);
$router->post('/libros/modificarAPI', [LibrosController::class, 'modificarLibroAPI']);
$router->get('/libros/eliminarAPI', [LibrosController::class, 'eliminarLibroAPI']);


$router->get('/libros/librosDisponiblesAPI', [LibrosController::class, 'librosDisponiblesAPI']);
$router->post('/libros/guardarPrestamoAPI', [LibrosController::class, 'guardarPrestamoAPI']);
$router->get('/libros/buscarPrestamosAPI', [LibrosController::class, 'buscarPrestamosAPI']);
$router->get('/libros/marcarDevueltoAPI', [LibrosController::class, 'marcarDevueltoAPI']);


$router->comprobarRutas();