<?php

$pol = mysqli_connect('localhost', 'root', '', 'ksiegarnia');

if (!$pol) {
    echo "Połączenie nie udane.";
    mysqli_close($pol);
}
?>
