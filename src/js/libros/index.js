import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormLibros = document.getElementById('FormLibros');
const BtnGuardarLibro = document.getElementById('BtnGuardarLibro');
const BtnModificarLibro = document.getElementById('BtnModificarLibro');
const BtnLimpiarLibro = document.getElementById('BtnLimpiarLibro');

const GuardarLibro = async (event) => {
    event.preventDefault();
    BtnGuardarLibro.disabled = true;

    if (!validarFormulario(FormLibros, ['libro_id'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos",
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
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarFormularioLibros();
            BuscarLibros();
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
    BtnGuardarLibro.disabled = false;
}

const BuscarLibros = async () => {
    const url = '/parcial1_GAHA/libros/buscarAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            datatableLibros.clear().draw();
            if (data) {
                datatableLibros.rows.add(data).draw();
            }
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

const datatableLibros = new DataTable('#TablaLibros', {
    language: lenguaje,
    data: [],
    columns: [
        { title: 'Título', data: 'titulo' },
        { title: 'Autor', data: 'autor' },
        {
            title: 'Acciones',
            data: 'id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                return `
                    <div class='d-flex justify-content-center gap-2'>
                        <button class='btn btn-warning btn-sm modificar-libro' 
                            data-id="${data}" 
                            data-titulo="${row.titulo}"  
                            data-autor="${row.autor}"  
                            data-descripcion="${row.descripcion || ''}">
                            <i class='bi bi-pencil-square'></i>
                        </button>
                        <button class='btn btn-danger btn-sm eliminar-libro' 
                            data-id="${data}">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>`;
            }
        }
    ]
});

const llenarFormularioLibros = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('libro_id').value = datos.id;
    document.getElementById('titulo').value = datos.titulo;
    document.getElementById('autor').value = datos.autor;
    document.getElementById('descripcion').value = datos.descripcion;

    BtnGuardarLibro.classList.add('d-none');
    BtnModificarLibro.classList.remove('d-none');
}

const limpiarFormularioLibros = () => {
    FormLibros.reset();
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
            text: "Debe completar todos los campos",
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
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarFormularioLibros();
            BuscarLibros();
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
}

const EliminarLibro = async (e) => {
    const idLibro = e.currentTarget.dataset.id;

    const confirmacion = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "Eliminar libro",
        text: 'Esta seguro que desea eliminar este libro?',
        showConfirmButton: true,
        confirmButtonText: 'Si',
        confirmButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        showCancelButton: true
    });

    if (confirmacion.isConfirmed) {
        const url = `/parcial1_GAHA/libros/eliminarAPI?id=${idLibro}`;
        const config = {
            method: 'GET'
        }

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();
            const { codigo, mensaje } = datos;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                BuscarLibros();
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
}

// Event Listeners
BuscarLibros();
datatableLibros.on('click', '.eliminar-libro', EliminarLibro);
datatableLibros.on('click', '.modificar-libro', llenarFormularioLibros);
FormLibros.addEventListener('submit', GuardarLibro);
BtnLimpiarLibro.addEventListener('click', limpiarFormularioLibros);
BtnModificarLibro.addEventListener('click', ModificarLibro);