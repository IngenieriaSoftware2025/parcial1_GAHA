import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguage";

// Referencias a los elementos del DOM - LIBROS
const FormLibros = document.getElementById('FormLibros');
const BtnGuardarLibro = document.getElementById('BtnGuardarLibro');
const BtnModificarLibro = document.getElementById('BtnModificarLibro');
const BtnLimpiarLibro = document.getElementById('BtnLimpiarLibro');

// Referencias a los elementos del DOM - PRÉSTAMOS
const FormPrestamos = document.getElementById('FormPrestamos');
const BtnGuardarPrestamo = document.getElementById('BtnGuardarPrestamo');

// Variables para las tablas
let tablaLibros;
let tablaPrestamos;

// Inicialización cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    inicializarDataTables();
    inicializarEventos();
    BuscarLibros();
    BuscarPrestamos();
    CargarLibrosDisponibles();
});

// ========== FUNCIONES DE INICIALIZACIÓN ==========
const inicializarDataTables = () => {
    // DataTable para libros
    tablaLibros = new DataTable('#TablaLibros', {
        language: lenguaje,
        data: [],
        columns: [
            { 
                title: 'Título', 
                data: 'titulo',
                width: '40%'
            },
            { 
                title: 'Autor', 
                data: 'autor',
                width: '35%'
            },
            { 
                title: 'Acciones', 
                data: 'id',
                width: '25%',
                orderable: false,
                searchable: false,
                render: (data, type, row) => {
                    return `
                        <button class="btn btn-warning btn-sm btnModificarLibro" data-id="${data}" title="Modificar">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btnEliminarLibro" data-id="${data}" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });

    // DataTable para préstamos
    tablaPrestamos = new DataTable('#TablaPrestamos', {
        language: lenguaje,
        data: [],
        columns: [
            { 
                title: 'Libro', 
                data: 'titulo_libro',
                width: '25%'
            },
            { 
                title: 'Persona', 
                data: 'nombre_completo',
                width: '25%'
            },
            { 
                title: 'Fecha', 
                data: 'fecha_prestamo',
                width: '20%',
                render: (data) => {
                    return new Date(data).toLocaleDateString('es-GT');
                }
            },
            { 
                title: 'Estado', 
                data: 'estado',
                width: '15%',
                render: (data) => {
                    return data === 'Prestado' ? 
                        '<span class="badge bg-warning">Prestado</span>' : 
                        '<span class="badge bg-success">Devuelto</span>';
                }
            },
            { 
                title: 'Acción', 
                data: 'id',
                width: '15%',
                orderable: false,
                searchable: false,
                render: (data, type, row) => {
                    if (row.estado === 'Prestado') {
                        return `
                            <button class="btn btn-success btn-sm btnDevolverLibro" data-id="${data}" title="Marcar como devuelto">
                                <i class="bi bi-check-circle"></i>
                            </button>
                        `;
                    }
                    return '<span class="text-muted">Devuelto</span>';
                }
            }
        ]
    });
};

const inicializarEventos = () => {
    // Eventos para formulario de libros
    if (FormLibros) {
        FormLibros.addEventListener('submit', GuardarLibro);
    }
    if (BtnModificarLibro) {
        BtnModificarLibro.addEventListener('click', ModificarLibro);
    }
    if (BtnLimpiarLibro) {
        BtnLimpiarLibro.addEventListener('click', limpiarFormularioLibros);
    }

    // Eventos para formulario de préstamos
    if (FormPrestamos) {
        FormPrestamos.addEventListener('submit', GuardarPrestamo);
    }

    // Eventos delegados para botones dinámicos
    document.addEventListener('click', (e) => {
        if (e.target.closest('.btnModificarLibro')) {
            const btn = e.target.closest('.btnModificarLibro');
            prepararModificacionLibro(btn.dataset.id);
        }
        if (e.target.closest('.btnEliminarLibro')) {
            const btn = e.target.closest('.btnEliminarLibro');
            EliminarLibro(btn.dataset.id);
        }
        if (e.target.closest('.btnDevolverLibro')) {
            const btn = e.target.closest('.btnDevolverLibro');
            DevolverLibro(btn.dataset.id);
        }
    });
};

// ========== FUNCIONES PARA LIBROS ==========
const GuardarLibro = async (event) => {
    event.preventDefault();
    BtnGuardarLibro.disabled = true;

    const body = new FormData(FormLibros);
    const url = '/parcial1_GAHA/libros/guardarLibroAPI';
    const config = {
        method: 'POST',
        body
    };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
                showConfirmButton: true,
            });
            limpiarFormularioLibros();
            BuscarLibros();
            CargarLibrosDisponibles();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Error de conexión",
            showConfirmButton: true,
        });
    }
    BtnGuardarLibro.disabled = false;
};

const BuscarLibros = async () => {
    const url = '/parcial1_GAHA/libros/buscarLibrosAPI';
    const config = { method: 'GET' };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos;

        if (codigo == 1 && Array.isArray(data)) {
            tablaLibros.clear().draw();
            tablaLibros.rows.add(data).draw();
        }
    } catch (error) {
        console.log(error);
    }
};

const prepararModificacionLibro = async (id) => {
    // Buscar los datos del libro por ID
    const url = `/parcial1_GAHA/libros/buscarLibrosAPI?id=${id}`;
    const config = { method: 'GET' };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos;

        if (codigo == 1 && data.length > 0) {
            const libro = data[0];
            
            // Llenar el formulario con los datos
            document.getElementById('libro_id').value = libro.id;
            document.getElementById('titulo').value = libro.titulo;
            document.getElementById('autor').value = libro.autor;
            document.getElementById('descripcion').value = libro.descripcion || '';

            // Cambiar botones
            BtnGuardarLibro.classList.add('d-none');
            BtnModificarLibro.classList.remove('d-none');
        }
    } catch (error) {
        console.log(error);
    }
};

const ModificarLibro = async (event) => {
    event.preventDefault();
    BtnModificarLibro.disabled = true;

    const body = new FormData(FormLibros);
    const url = '/parcial1_GAHA/libros/modificarLibroAPI';
    const config = { method: 'POST', body };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
                showConfirmButton: true,
            });
            limpiarFormularioLibros();
            BuscarLibros();
            CargarLibrosDisponibles();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error);
    }
    BtnModificarLibro.disabled = false;
};

const EliminarLibro = async (idLibro) => {
    const confirmar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "Eliminar libro",
        text: "¿Estás seguro que querés eliminar este libro?",
        showConfirmButton: true,
        confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancelar',
        showCancelButton: true
    });

    if (confirmar.isConfirmed) {
        const url = `/parcial1_GAHA/libros/eliminarLibroAPI?id=${idLibro}`;
        const config = { method: 'GET' };

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();
            const { codigo, mensaje } = datos;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "¡Éxito!",
                    text: mensaje,
                    showConfirmButton: true,
                });
                BuscarLibros();
                CargarLibrosDisponibles();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }
        } catch (error) {
            console.log(error);
        }
    }
};

const limpiarFormularioLibros = () => {
    if (FormLibros) {
        FormLibros.reset();
        document.getElementById('libro_id').value = '';
        
        // Restaurar botones
        BtnGuardarLibro.classList.remove('d-none');
        BtnModificarLibro.classList.add('d-none');
    }
};

// ========== FUNCIONES PARA PRÉSTAMOS ==========
const GuardarPrestamo = async (event) => {
    event.preventDefault();
    BtnGuardarPrestamo.disabled = true;

    const body = new FormData(FormPrestamos);
    const url = '/parcial1_GAHA/libros/guardarPrestamoAPI';
    const config = {
        method: 'POST',
        body
    };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
                showConfirmButton: true,
            });
            FormPrestamos.reset();
            BuscarPrestamos();
            CargarLibrosDisponibles();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Error de conexión",
            showConfirmButton: true,
        });
    }
    BtnGuardarPrestamo.disabled = false;
};

const BuscarPrestamos = async () => {
    const url = '/parcial1_GAHA/libros/buscarPrestamosAPI';
    const config = { method: 'GET' };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos;

        if (codigo == 1 && Array.isArray(data)) {
            tablaPrestamos.clear().draw();
            tablaPrestamos.rows.add(data).draw();
        }
    } catch (error) {
        console.log(error);
    }
};

const DevolverLibro = async (idPrestamo) => {
    const confirmar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "Marcar como devuelto",
        text: "¿Confirmar que el libro ya fue devuelto?",
        showConfirmButton: true,
        confirmButtonText: 'Sí, devuelto',
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        showCancelButton: true
    });

    if (confirmar.isConfirmed) {
        const body = new FormData();
        body.append('id', idPrestamo);
        
        const url = '/parcial1_GAHA/libros/devolverLibroAPI';
        const config = { method: 'POST', body };

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();
            const { codigo, mensaje } = datos;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "¡Éxito!",
                    text: mensaje,
                    showConfirmButton: true,
                });
                BuscarPrestamos();
                CargarLibrosDisponibles();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }
        } catch (error) {
            console.log(error);
        }
    }
};

const CargarLibrosDisponibles = async () => {
    const url = '/parcial1_GAHA/libros/librosDisponiblesAPI';
    const config = { method: 'GET' };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos;

        const selectLibros = document.getElementById('id_libro');
        if (selectLibros) {
            // Limpiar opciones
            selectLibros.innerHTML = '<option value="">Seleccione un libro</option>';
            
            if (codigo == 1 && Array.isArray(data)) {
                data.forEach(libro => {
                    const option = document.createElement('option');
                    option.value = libro.id;
                    option.textContent = `${libro.titulo} - ${libro.autor}`;
                    selectLibros.appendChild(option);
                });
            }
        }
    } catch (error) {
        console.log(error);
    }
};