<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="text-primary">Control de libros de Laura</h1>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4" id="mainTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="libros-tab" data-bs-toggle="tab" data-bs-target="#libros" type="button" role="tab">
                Gestión de Libros
            </button>
        </li>
    </ul>

   
    <div class="tab-content" id="mainTabsContent">

        <div class="tab-pane fade show active" id="libros" role="tabpanel">

            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Agregar Libro</h5>
                        </div>
                        <div class="card-body">
                            <form id="FormLibros">
                                <input type="hidden" id="libro_id" name="id">
                                
                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título del libro</label>
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
                                        <i class="bi bi-save"></i> Guardar Libro
                                    </button>
                                    <button type="button" class="btn btn-warning d-none" id="BtnModificarLibro">
                                        <i class="bi bi-pencil"></i> Modificar Libro
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="BtnLimpiarLibro">
                                        <i class="bi bi-eraser"></i> Limpiar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/biblioteca.js') ?>"></script>