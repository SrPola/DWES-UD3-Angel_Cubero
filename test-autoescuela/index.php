<?php
    /**
     * @author SrPola
     */

    $iniciarTest = false;
    $corregirTest = false;
    $fallosPermitidos = 1;
    $aprobado = false;
    

    include("./config/tests_cnf.php");

    if (isset($_POST["iniciarTest"])) {
        $iniciarTest = true;
        $idTest = $_POST["test"];
    }


    $respuestas = array();
    $contadorFallos = 0;
    $respuestasElegidas = array();
    if (isset($_POST["corregirTest"])) {
        $idTest = $_POST["idTest"];
        $corregirTest = true;
        $correctas = $aTests[$_POST["indice_corrector"]]["Corrector"];

        foreach ($aTests[$_POST["indice_corrector"]]["Preguntas"] as $pregunta) {
            $idPregunta = $pregunta["idPregunta"];
            if (isset($_POST[$idPregunta])) {
                $respuestasElegidas[] = $_POST[$idPregunta];
            }
        }
        echo "<br>";
    }
        

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Test autoescuela</title>
    </head>
    <body>
        <form method="post">
            <label for="test">Seleccione el test a realizar: </label>
            <select name="test">
                <?php
                    foreach ($aTests as $value) {
                       echo '<option value="'.$value["idTest"].'">'.$value["Categoria"].' Examen '.$value["idTest"].' '.$value["Permiso"].'</option>';
                    }
                ?>
            </select>
            <br>
            <input type="submit" name="iniciarTest" value="Iniciar test">
        </form>
        <?php
            if ($iniciarTest) {
        ?>
            <h2>Examen</h2>
            <form method="post">
                <?php
                    $contadorTest = 0;
                    echo '<input type="hidden" name="idTest" value="'.$idTest.'">';
                    foreach ($aTests as $value) {
                        if ($value["idTest"] == $idTest) {
                            echo '<input type="hidden" name="indice_corrector" value="'.$contadorTest.'">';
                            foreach ($value["Preguntas"] as $pregunta) {
                                echo '<h3>'.$pregunta["Pregunta"].'</h3>';

                                $rutaImagen = './dir_img_test'.$idTest.'/img'.$pregunta["idPregunta"].'.jpg';
                                if (file_exists($rutaImagen)) {
                                    echo '<img src="'.$rutaImagen.'"><br>';
                                }

                                $posicionPregunta = 0;
                                foreach ($pregunta["respuestas"] as $respuesta) {
                                    echo '<input type="radio" id="'.$pregunta["idPregunta"].'" name="'.$pregunta["idPregunta"].'" value="'.$indexLetra[$posicionPregunta].'">';
                                    echo '<label for="'.$pregunta["idPregunta"].'">'.$respuesta.'</label><br>';
                                    $posicionPregunta ++;
                                }
                            }

                            echo '<br><input type="submit" name="corregirTest" value="Corregir test">';
                        }
                        $contadorTest ++;
                    }
                ?>
            </form>
        <?php
            }
        ?>

        <?php
            if ($corregirTest) {
                $contadorTest = 0;
                foreach ($aTests as $value) {
                    if ($value["idTest"] == $idTest) {
                        foreach ($value["Preguntas"] as $pregunta) {
                            $idPregunta = $pregunta["idPregunta"];
                            if (isset($_POST[$idPregunta])) {
                                $respuestas[] = $_POST[$idPregunta];
                            }
                        }
                    }
                    $contadorTest ++;
                }

                $contadorRespuestas = 0;
                foreach ($respuestas as $respuesta) {
                    if ($respuesta == $correctas[$contadorRespuestas]) {
                        echo '<p style="color: green;">Respuesta correcta</p>';
                    } else {
                        echo '<p style="color: red;">Respuesta incorrecta</p>';
                        $contadorFallos ++;
                    }
                    $contadorRespuestas ++;
                }

                if ($contadorFallos <= $fallosPermitidos) {
                    $aprobado = true;
                }

                if ($aprobado) {
                    echo '<p style="color: green;">Aprobado</p>';
                } else {
                    echo '<p style="color: red;">Suspendido</p>';
                }
            }
        ?>
    </body>
</html>