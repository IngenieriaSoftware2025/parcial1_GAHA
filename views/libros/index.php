<div class="container py-5">

    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">Registro de Libros</h5>
                        <h3 class="fw-bold text-primary mb-0">REGISTRAR NUEVO LIBRO</h3>
                    </div>
                    <form id="FormLibros" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="libro_id" name="id">
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="titulo" class="form-label">Título del Libro</label>
                                <input type="text" class="form-control form-control-lg" id="titulo" name="titulo" placeholder="Ingrese el título del libro">
                            </div>
                            <div class="col-md-6">
                                <label for="autor" class="form-label">Autor del Libro</label>
                                <input type="text" class="form-control form-control-lg" id="autor" name="autor" placeholder="Ingrese el autor del libro">
                            </div>
                        </div>
                        <div class="row g-4 mb-3">
                            <div class="col-12">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control form-control-lg" id="descripcion" name="descripcion" rows="3" placeholder="Descripción opcional del libro"></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardarLibro">
                                <i class="bi bi-save me-2"></i>Guardar Libro
                            </button>
                            <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificarLibro">
                                <i class="bi bi-pencil-square me-2"></i>Modificar Libro
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiarLibro">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">Registro de Libros Prestados</h5>
                        <h3 class="fw-bold text-primary mb-0">REGISTRAR PRÉSTAMO DE LIBRO</h3>
                    </div>
                    <form id="FormPrestamos" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="prestamo_id" name="id">
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="id_libro" class="form-label">Seleccionar Libro</label>
                                <select name="id_libro" class="form-select form-select-lg" id="id_libro">
                                    <option value="">Seleccione un libro</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <input type="text" class="form-control form-control-lg" id="observaciones" name="observaciones" placeholder="a quien se le presta?">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardarPrestamo">
                                <i class="bi bi-save me-2"></i>Guardar Préstamo
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiar">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-lg-11">
            <div class="card shadow-lg border-primary rounded-4">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">Libros registrados en la base de datos</h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden" id="TablaLibros">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-lg-11">
            <div class="card shadow-lg border-success rounded-4">
                <div class="card-body">
                    <h3 class="text-center text-success mb-4">Préstamos registrados en la base de datos</h3>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                            <input type="date" id="fecha_inicio" class="form-control form-control-lg">
                        </div>
                        <div class="col-md-4">
                            <label for="fecha_fin" class="form-label">Fecha de fin</label>
                            <input type="date" id="fecha_fin" class="form-control form-control-lg">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary btn-lg w-100 shadow" id="btn_filtrar_fecha">
                                <i class="bi bi-funnel-fill me-2"></i>Buscar por fecha
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden" id="TablaPrestamos">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/libros/index.js') ?>"></script>