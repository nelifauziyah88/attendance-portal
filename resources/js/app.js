import "./bootstrap";
import Swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import '@fontsource/plus-jakarta-sans/300.css';
import '@fontsource/plus-jakarta-sans/400.css';
import '@fontsource/plus-jakarta-sans/500.css';
import '@fontsource/plus-jakarta-sans/600.css';
import confetti from 'canvas-confetti';
import ExcelJS from 'exceljs';

window.ExcelJS = ExcelJS;
window.Swal = Swal;
window.confetti = confetti;
