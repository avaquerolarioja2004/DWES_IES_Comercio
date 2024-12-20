<?php
$open = fopen('lorem.txt', 'r') or die('No encontrado');
echo fread($open, filesize('lorem.txt'));
echo fgets($open);
while (!feof($open)) {
    echo fgets($open);
    echo "\n";
}
fclose($open);

/*
Apartir de un fichero que contiene un numero de lineas y en cada linea tiene una palabra quiero 
mostrar el reverso de cada palabra y se pasa a un fichero que se llame lorem_copia.txt
*/
$open = fopen('lorem.txt', 'r') or die('No encontrado');
$reves = '';
while (!feof($open)) {
    $palabra = fgets($open);
    $vueltas = strlen($palabra);
    for ($i = $vueltas - 1; $i > -1; $i--) {
        $reves .= trim($palabra[$i]);
    }
    $reves .= "\n";
}
fclose($open);

if (!file_exists('lorem_copia.txt')) {
    $fh = fopen("lorem_copia.txt", 'w') or die("Se produjo un error al crear el archivo");
    fwrite($fh, $reves) or die("No se pudo escribir en el archivo");
    fclose($fh);
}

/*
Hay dos ficheros dentro de cada fichero hay comunidades ordenadas alfabeticamente, genera uno nuevo ordenado.
*/
$f1 = fopen('f1.txt', 'r');
$f2 = fopen('f2.txt', 'r');
$f3 = fopen('f3.txt', 'w');

$var1 = fgets($f1);
$var2 = fgets($f2);

while ($var1 !== false && $var2 !== false) {
    if (strcasecmp(trim($var1), trim($var2)) < 0) {
        fwrite($f3, $var1);
        $var1 = fgets($f1);
    } elseif (strcasecmp(trim($var1), trim($var2)) > 0) {
        fwrite($f3, $var2);
        $var2 = fgets($f2);
    } else {
        fwrite($f3, $var1);
        $var1 = fgets($f1);
        $var2 = fgets($f2);
    }
}

while ($var1 !== false) {
    fwrite($f3, "\n" . trim($var1));
    $var1 = fgets($f1);
}

while ($var2 !== false) {
    fwrite($f3, "\n" . trim($var2));
    $var2 = fgets($f2);
}

fclose($f1);
fclose($f2);
fclose($f3);

/*
Crea un script en PHP que realice lo siguiente:

Leer el contenido de una página web utilizando la función file_get_contents.
Eliminar todas las etiquetas HTML del contenido obtenido, de modo que solo se conserve el texto visible.
Eliminar cualquier bloque de código JavaScript que se encuentre en el contenido.
Mostrar el texto limpio en la salida del script.
*/
function cleanHtml($url) {
    $content = file_get_contents($url);
    //$content = preg_replace('/<script.*?<\/script>/is', '', $content);
    $content = preg_replace('/<(script).*?<\/\\1>/is', '', $content);
    return strip_tags($content);
}

$url = "./test.html";
$cleanText = cleanHtml($url);
echo trim($cleanText);
?>


