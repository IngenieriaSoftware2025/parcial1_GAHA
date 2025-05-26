import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { data, event } from "jquery";

const FormLibros = document.getElementById('FormUsuarios');
const BtnGuardarLibro = document.getElementById('BtnGuardarLibro');
const BtnModificarLibro = document.getElementById('BtnModificarLibro');
const BtnLimpiarLibro = document.getElementById('BtnLimpiarLibro');

