<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a la Aplicación para el registro, modificación y eliminación de libros!</h5>
                        <h3 class="fw-bold text-primary mb-0">CONTROL DE LIBROS DE LAURA</h3>
                    </div>
                    
                    <!-- Tabs de navegación -->
                    <ul class="nav nav-pills justify-content-center mb-4" id="mainTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="libros-tab" data-bs-toggle="pill" data-bs-target="#libros" type="button" role="tab">
                                <i class="bi bi-book me-2"></i>Mis Libros
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="prestamos-tab" data-bs-toggle="pill" data-bs-target="#prestamos" type="button" role="tab">
                                <i class="bi bi-hand-thumbs-up me-2"></i>Préstamos
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="mainTabsContent">
                        <!-- TAB LIBROS -->
                        <div class="tab-pane fade show active" id="libros" role="tabpanel">
                            <form id="FormLibros" class="p-4 bg-white rounded-3 shadow-sm border">
                                <input type="hidden" id="libro_id" name="id">
                                
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <label for="titulo" class="form-label">Título del Libro</label>
                                        <input type="text" class="form-control form-control-lg" id="titulo" name="titulo" placeholder="Ingrese el título" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="autor" class="form-label">Autor</label>
                                        <input type="text" class="form-control form-control-lg" id="autor" name="autor" placeholder="Ingrese el autor" required>
                                    </div>
                                </div>
                                
                                <div class="row g-4 mb-4">
                                    <div class="col-12">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control form-control-lg" id="descripcion" name="descripcion" rows="3" placeholder="Descripción opcional del libro"></textarea>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-center gap-3">
                                    <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardarLibro">
                                        <i class="bi bi-save me-2"></i>Guardar
                                    </button>
                                    <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificarLibro">
                                        <i class="bi bi-pencil-square me-2"></i>Modificar
                                    </button>
                                    <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiarLibro">
                                        <i class="bi bi-eraser me-2"></i>Limpiar
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- TAB PRÉSTAMOS -->
                        <div class="tab-pane fade" id="prestamos" role="tabpanel">
                            <form id="FormPrestamos" class="p-4 bg-white rounded-3 shadow-sm border">
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <label for="id_libro" class="form-label">Seleccionar Libro</label>
                                        <select class="form-select form-select-lg" id="id_libro" name="id_libro" required>
                                            <option value="">-- Seleccione un libro --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nombre_persona" class="form-label">¿A quién se le va a prestar?</label>
                                        <input type="text" class="form-control form-control-lg" id="nombre_persona" name="nombre_persona" placeholder="Ej: María García" required>
                                    </div>
                                </div>
                                
                                <div class="row g-4 mb-4">
                                    <div class="col-12">
                                        <label for="observaciones" class="form-label">Observaciones</label>
                                        <textarea class="form-control form-control-lg" id="observaciones" name="observaciones" rows="2" placeholder="Observaciones opcionales..."></textarea>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-success btn-lg px-4 shadow w-100" type="submit" id="BtnGuardarPrestamo">
                                        <i class="bi bi-hand-thumbs-up me-2"></i>Registrar Préstamo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLAS DE DATOS -->
    <div class="row justify-content-center mt-5">
        <div class="col-lg-11">
            <div class="card shadow-lg border-primary rounded-4">
                <div class="card-body">
                    
                    <!-- Tabs para las tablas -->
                    <ul class="nav nav-tabs mb-4" id="tablaTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tabla-libros-tab" data-bs-toggle="tab" data-bs-target="#tabla-libros" type="button" role="tab">
                                <i class="bi bi-book me-2"></i>Libros Registrados
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tabla-prestamos-tab" data-bs-toggle="tab" data-bs-target="#tabla-prestamos" type="button" role="tab">
                                <i class="bi bi-list-check me-2"></i>Control de Préstamos
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="tablaTabsContent">
                        <!-- TABLA LIBROS -->
                        <div class="tab-pane fade show active" id="tabla-libros" role="tabpanel">
                            <h3 class="text-center text-primary mb-4">Mis Libros en la Biblioteca</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TablaLibros">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Título</th>
                                            <th>Autor</th>
                                            <th>Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TABLA PRÉSTAMOS -->
                        <div class="tab-pane fade" id="tabla-prestamos" role="tabpanel">
                            <h3 class="text-center text-primary mb-4">Control de Préstamos Activos</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden W-100" id="TablaPrestamos">
                                    <thead class="table-warning">
                                        <tr>
                                            <th>Libro</th>
                                            <th>Persona</th>
                                            <th>Fecha Préstamo</th>
                                            <th>Estado</th>
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

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/libros/index.js') ?>"></script>