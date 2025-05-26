import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguage";

const FormLibros = document.getElementById('FormUsuarios');
const BtnGuardarLibro = document.getElementById('BtnGuardarLibro');
const BtnModificarLibro = document.getElementById('BtnModificarLibro');
const BtnLimpiarLibro = document.getElementById('BtnLimpiarLibro');

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
                title: "Éxito",
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