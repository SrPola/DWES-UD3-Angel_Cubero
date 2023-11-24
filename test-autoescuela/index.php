<?php
/**
 * @author Angel Cubero Olivares
 * @date 2023-11-23
 */

include("./config/tests_cnf.php");

$iniciar_test = false;
$corregir_test = false;

if (isset($_POST['iniciar_test'])) {
    $iniciar_test = true;
}

if (isset($_POST['corregir_test'])) {
    $iniciar_test = true;
    $corregir_test = true;
}

function mostrar_test($test)
{
    foreach ($test["Preguntas"] as $pregunta) {
        echo "<h2>" . $pregunta["Pregunta"] . "</h2>";
        if (file_exists("./dir_img_test" . $test["idTest"] . "/img" . $pregunta["idPregunta"] . ".jpg")) {
            echo '<img src="./dir_img_test' . $test["idTest"] . '/img' . $pregunta["idPregunta"] . '.jpg"><br>';
        }
        for ($i = 0; $i < count($pregunta["respuestas"]); $i++) {
            echo '<input type="radio" id="' . $pregunta["idPregunta"] . $i . '" value="' . $i . '" name="' . $pregunta["idPregunta"] . '">';
            echo '<label for="' . $pregunta["idPregunta"] . $i . '">' . $pregunta["respuestas"][$i] . '</label><br>';
        }
    }
}

function corregir_test($test, $respuestas)
{
    $puntaje = 0;
    $respuestasCorrectas = $test["Corrector"];

    foreach ($test["Preguntas"] as $index => $pregunta) {
        // Comparar la respuesta del estudiante con la respuesta correcta
        $respuestaEstudiante = $respuestas[$pregunta["idPregunta"]];
        $respuestaCorrecta = $respuestasCorrectas[$index];

        if ($respuestaEstudiante == $respuestaCorrecta) {
            $puntaje++;
        }
    }

    return $puntaje;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Angel Cubero Olivares">
    <title>Test autoescuela</title>
</head>

<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="elegir_test">Selecciona un test:</label>
        <select name="elegir_test" id="elegir_test">
            <?php
            foreach ($Tests as $test) {
                echo '<option value="' . $test["idTest"] . '"';
                if (isset($_POST['elegir_test']) && $_POST['elegir_test'] == $test["idTest"]) {
                    echo ' selected';
                }
                echo '>' . $test["Permiso"] . ' ' . $test["Categoria"] . '</option>';
            }
            ?>
        </select>
        <br>
        <input type="submit" name="iniciar_test" value="Iniciar test">
        <?php
        if ($iniciar_test) {
            $selectedTest = $Tests[$_POST["elegir_test"]];
            mostrar_test($selectedTest);
            echo '<input type="submit" name="corregir_test" value="Corregir test">';
        }
        if ($corregir_test) {
            echo '<input type="hidden" name="corregir_test" value="1">'; // Campo oculto
            $respuestas = array();
            foreach ($selectedTest["Preguntas"] as $pregunta) {
                $respuestas[$pregunta["idPregunta"]] = isset($_POST[$pregunta["idPregunta"]]) ? $_POST[$pregunta["idPregunta"]] : '';
            }
            $puntaje = corregir_test($selectedTest, $respuestas);
            echo "<p>Puntuación: {$puntaje} de " . count($selectedTest["Preguntas"]) . "</p>";
        }
        ?>
        <br>
    </form>
</body>

</html>
