import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormLibros = document.getElementById('FormLibros');
const BtnGuardarLibro = document.getElementById('BtnGuardarLibro');
const BtnModificarLibro = document.getElementById('BtnModificarLibro');
const BtnLimpiarLibro = document.getElementById('BtnLimpiarLibro');

const FormPrestamos = document.getElementById('FormPrestamos');
const BtnGuardar = document.getElementById('BtnGuardarPrestamo');
const libro_id_prestamo = document.getElementById('id_libro');
const observaciones = document.getElementById('observaciones');

const ValidarObservaciones = () => {
    if (!observaciones) return;
    
    const CantidadCaracteres = observaciones.value

    if (CantidadCaracteres.length < 1) {
        observaciones.classList.remove('is-valid', 'is-invalid');
    } else {
        if (CantidadCaracteres.length < 2) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Revise las observaciones",
                text: "La cantidad de caracteres debe ser mayor a 2",
                showConfirmButton: true,
            });

            observaciones.classList.remove('is-valid');
            observaciones.classList.add('is-invalid');
        } else {
            observaciones.classList.remove('is-invalid');
            observaciones.classList.add('is-valid');
        }
    }
}

const GuardarLibro = async (event) => {
    event.preventDefault();
    BtnGuardarLibro.disabled = true;

    if (!validarFormulario(FormLibros, ['libro_id'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardarLibro.disabled = false;
        return;
    }

    const body = new FormData(FormLibros);

    const url = '/parcial1_GAHA/libros/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        console.log(datos)
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodoLibros();
            CargarLibros();
            BuscarLibros();
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error)
    }
    BtnGuardarLibro.disabled = false;
}

const CargarLibros = async () => {
    if (!libro_id_prestamo) return;

    const url = '/parcial1_GAHA/libros/librosDisponiblesAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1 && data) {
            libro_id_prestamo.innerHTML = '<option value="">-- Seleccione un libro --</option>';
            
            data.forEach(libro => {
                const option = document.createElement('option');
                option.value = libro.id;
                option.textContent = `${libro.titulo} - ${libro.autor}`;
                libro_id_prestamo.appendChild(option);
            });
        }
    } catch (error) {
        console.log(error)
    }
}

const BuscarLibros = async () => {
    const url = '/parcial1_GAHA/libros/buscarAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            datatableLibros.clear().draw();
            if (data && data.length > 0) {
                datatableLibros.rows.add(data).draw();
            }
        }
    } catch (error) {
        console.log(error)
    }
}

const GuardarPrestamo = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormPrestamos, ['prestamo_id'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormPrestamos);

    const url = '/parcial1_GAHA/libros/guardarPrestamoAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        console.log(datos)
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarPrestamos();
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error)
    }
    BtnGuardar.disabled = false;
}

const BuscarPrestamos = async () => {
    const url = `/parcial1_GAHA/libros/buscarPrestamosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            datatable.clear().draw();
            if (data && data.length > 0) {
                datatable.rows.add(data).draw();
            }
        } else {
            console.log('Error al buscar préstamos:', mensaje);
        }
    } catch (error) {
        console.log(error)
    }
}

const datatableLibros = new DataTable('#TablaLibros', {
    language: lenguaje,
    data: [],
    columns: [
        { title: 'Título', data: 'titulo' },
        { title: 'Autor', data: 'autor' },
        { title: 'Descripción', data: 'descripcion' },
        {
            title: 'Acciones',
            data: 'id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                return `
                    <div class='d-flex justify-content-center'>
                        <button class='btn btn-warning modificar-libro mx-1' 
                            data-id="${data}" 
                            data-titulo="${row.titulo}"  
                            data-autor="${row.autor}"  
                            data-descripcion="${row.descripcion || ''}">
                            <i class='bi bi-pencil-square me-1'></i> Modificar
                        </button>
                        <button class='btn btn-danger eliminar-libro mx-1' 
                            data-id="${data}">
                            <i class="bi bi-trash3 me-1"></i>Eliminar
                        </button>
                    </div>`;
            }
        }
    ]
});

const datatable = new DataTable('#TablaPrestamos', {
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'No.',
            data: 'id',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Libro', data: 'titulo' },
        { title: 'Autor', data: 'autor' },
        { title: 'Observaciones', data: 'observaciones' },
        { title: 'Fecha Préstamo', data: 'fecha_prestamo'},
        { title: 'Fecha Devolución', data: 'fecha_devolucion'},
        {
            title: 'Estado',
            data: 'id_estado',
            render: (data, type, row) => {
                const estado = row.id_estado

                if (estado == 1) {
                    return "<span class='badge bg-danger'>PRESTADO</span>"
                } else if (estado == 2) {
                    return "<span class='badge bg-success'>DEVUELTO</span>"
                }
            }
        },
        {
            title: 'Acciones',
            data: 'id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                const estado = row.id_estado;
                
                if (estado == 1) {
                    return `
                     <div class='d-flex justify-content-center'>
                         <button class='btn btn-success devolver mx-1' 
                             data-id="${data}">
                            <i class="bi bi-arrow-return-left me-1"></i>Devolver
                         </button>
                     </div>`;
                } else {
                    return `<span class="text-muted">Devuelto</span>`;
                }
            }
        }
    ]
});

const llenarFormularioLibros = (event) => {
    const datos = event.currentTarget.dataset

    document.getElementById('libro_id').value = datos.id
    document.getElementById('titulo').value = datos.titulo
    document.getElementById('autor').value = datos.autor
    document.getElementById('descripcion').value = datos.descripcion

    BtnGuardarLibro.classList.add('d-none');
    BtnModificarLibro.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    })
}

const limpiarTodo = () => {
    if (FormPrestamos) {
        FormPrestamos.reset();
    }
}

const limpiarTodoLibros = () => {
    if (FormLibros) {
        FormLibros.reset();
    }
    BtnGuardarLibro.classList.remove('d-none');
    BtnModificarLibro.classList.add('d-none');
}

const ModificarLibro = async (event) => {
    event.preventDefault();
    BtnModificarLibro.disabled = true;

    if (!validarFormulario(FormLibros, [''])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnModificarLibro.disabled = false;
        return;
    }

    const body = new FormData(FormLibros);

    const url = '/parcial1_GAHA/libros/modificarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodoLibros();
            BuscarLibros();
            CargarLibros();
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error)
    }
    BtnModificarLibro.disabled = false;
}

const EliminarLibro = async (e) => {
    const idLibro = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Desea ejecutar esta acción?",
        text: 'Esta completamente seguro que desea eliminar este registro',
        showConfirmButton: true,
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: 'red',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url =`/parcial1_GAHA/libros/eliminarAPI?id=${idLibro}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarLibros();
                CargarLibros();
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
            console.log(error)
        }
    }
}

const DevolverPrestamo = async (e) => {
    const idPrestamo = e.currentTarget.dataset.id

    const AlertaConfirmarDevolucion = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Desea marcar como devuelto?",
        text: 'Esta completamente seguro que desea devolver este libro',
        showConfirmButton: true,
        confirmButtonText: 'Si, Devolver',
        confirmButtonColor: 'green',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarDevolucion.isConfirmed) {
        const url =`/parcial1_GAHA/libros/marcarDevueltoAPI?id=${idPrestamo}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarPrestamos();
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
            console.log(error)
        }
    }
}

CargarLibros();
BuscarPrestamos();
BuscarLibros();
datatable.on('click', '.devolver', DevolverPrestamo);
datatableLibros.on('click', '.eliminar-libro', EliminarLibro);
datatableLibros.on('click', '.modificar-libro', llenarFormularioLibros);
FormLibros.addEventListener('submit', GuardarLibro);
FormPrestamos.addEventListener('submit', GuardarPrestamo);
if (observaciones) {
    observaciones.addEventListener('change', ValidarObservaciones);
}
BtnLimpiarLibro.addEventListener('click', limpiarTodoLibros);
BtnModificarLibro.addEventListener('click', ModificarLibro);