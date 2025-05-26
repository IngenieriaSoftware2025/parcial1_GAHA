<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\LibrosController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

// Ruta principal
$router->get('/', [AppController::class,'index']);
$router->get('/libros', [LibrosController::class, 'renderizarPagina']);


$router->post('/libros/guardarLibroAPI', [LibrosController::class, 'guardarLibroAPI']);
$router->get('/libros/buscarLibrosAPI', [LibrosController::class, 'buscarLibrosAPI']);
$router->post('/libros/modificarLibroAPI', [LibrosController::class, 'modificarLibroAPI']);
$router->get('/libros/eliminarLibroAPI', [LibrosController::class, 'eliminarLibroAPI']);

$router->post('/libros/guardarPrestamoAPI', [LibrosController::class, 'guardarPrestamoAPI']);
$router->get('/libros/buscarPrestamosAPI', [LibrosController::class, 'buscarPrestamosAPI']);
$router->post('/libros/devolverLibroAPI', [LibrosController::class, 'devolverLibroAPI']);
$router->get('/libros/librosDisponiblesAPI', [LibrosController::class, 'librosDisponiblesAPI']);

$router->post('/libros/guardarPersonaAPI', [LibrosController::class, 'guardarPersonaAPI']);
$router->get('/libros/buscarPersonasAPI', [LibrosController::class, 'buscarPersonasAPI']);

// Comprueba y valida las rutas
$router->comprobarRutas();