<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="text-primary">Control de libros de Laura</h1>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4" id="mainTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="libros-tab" data-bs-toggle="tab" data-bs-target="#libros" type="button" role="tab">
            Mis Libros
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="prestamos-tab" data-bs-toggle="tab" data-bs-target="#prestamos" type="button" role="tab">
            Préstamos
            </button>
        </li>
    </ul>

    <div class="tab-content" id="mainTabsContent">
        
        <div class="tab-pane fade show active" id="libros" role="tabpanel">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Agregar Libro</h5>
                        </div>
                        <div class="card-body">
                            <form id="FormLibros">
                                <input type="hidden" id="libro_id" name="id">
                                
                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="autor" class="form-label">Autor</label>
                                    <input type="text" class="form-control" id="autor" name="autor">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success" id="BtnGuardarLibro">
                                        Guardar
                                    </button>
                                    <button type="button" class="btn btn-warning d-none" id="BtnModificarLibro">
                                        Modificar
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="BtnLimpiarLibro">
                                        Limpiar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Mis Libros</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="TablaLibros">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Autor</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="prestamos" role="tabpanel">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Nuevo Préstamo</h5>
                        </div>
                        <div class="card-body">
                            <form id="FormPrestamos">
                                <div class="mb-3">
                                    <label for="id_libro" class="form-label">Libro</label>
                                    <select class="form-select" id="id_libro" name="id_libro" required>
                                        <option value="">Seleccione un libro</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nombre_persona" class="form-label">a quien se le va a prestar</label>
                                    <input type="text" class="form-control" id="nombre_persona" name="nombre_persona" 
                                           placeholder="Ej: María García" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" 
                                              rows="2" placeholder="Opcional..."></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-success w-100" id="BtnGuardarPrestamo">
                                    Registrar Préstamo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">Control de Préstamos</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="TablaPrestamos">
                                    <thead>
                                        <tr>
                                            <th>Libro</th>
                                            <th>Persona</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/biblioteca.js') ?>"></script>