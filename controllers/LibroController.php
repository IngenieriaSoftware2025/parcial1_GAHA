<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;

class LibrosController extends ActiveRecord
{
    public function renderizarPagina(Router $router)
    {
        $router->render('libros/index', []);
    }
}