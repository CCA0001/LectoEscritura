<!DOCTYPE html>
<html>
<head>
    <title> hola mundo </title>
</head>

<body>

<form method="POST" action="guardar.php">

    <h1>Cuestionario</h1>
    <h2>Hola, a continuación verá unas preguntas</h2>

    <h3>Objetivo: Lea atentamente y responda las siguientes preguntas</h3>

    <p>
    En la plaza central de la ciudad, cada domingo se organiza un mercado de artesanías. 
    Los visitantes pueden encontrar collares hechos a mano, 
    figuras talladas en madera y tejidos de colores brillantes. 
    Además, los artesanos explican a los compradores cómo elaboran sus productos, 
    lo que convierte la visita en una experiencia cultural única.
    </p>

    <h4>1. ¿Qué se organiza cada domingo en la plaza central?  </h4>

    <input type="radio" name="respuesta" value="concierto"> Un concierto de música clásica<br>
    <input type="radio" name="respuesta" value="mercado"> Un mercado de artesanías<br>
    <input type="radio" name="respuesta" value="feria"> Una feria de libros<br>
    <input type="radio" name="respuesta" value="competencia">Una competencia deportiva<br><br>

    <button type="submit">Comprobar PHP</button>
    <button type="button" id="btnComprobar">Comprobar JS</button>

</form>

<p id="resultado"></p>

<script src="script.js"></script>

</body>
</html>