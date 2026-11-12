/**
 * Helpers de escape para evitar XSS.
 *
 * Todo lo que viene de la base de datos (títulos, contenidos,
 * retroalimentación de la IA, nombres de usuarios) se inserta en el DOM
 * con template strings + innerHTML. Sin escapar, un texto con
 * <script> se ejecuta en el navegador de todos los que lo ven.
 *
 * Uso:
 *   html += `<td>${escaparHTML(texto.contenido)}</td>`;
 */

function escaparHTML(valor) {

    if(valor === null || valor === undefined) {
        return "";
    }

    return String(valor)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

/**
 * Variante para valores que van dentro de un atributo HTML entre
 * comillas simples, por ejemplo onclick="mover('...')".
 */
function escaparAtributo(valor) {

    if(valor === null || valor === undefined) {
        return "";
    }

    return escaparHTML(valor).replace(/\n/g, " ");
}
