<?php

$id_empresa = $_GET['id_empresa'];

//obtenemos los años de los estados financieros
$sql = "SELECT ef.anio FROM estados_financieros ef WHERE ef.id_empresa = $id_empresa GROUP BY ef.anio";
$statement = $pdo->prepare($sql);
$statement->execute();
$anios = $statement->fetchAll();

//obtenemos el nombre de la empresa
$sql = "SELECT e.nombre_empresa FROM empresa e WHERE e.id_empresa = $id_empresa";
$statement = $pdo->prepare($sql);
$statement->execute();
$nombre_empresa = $statement->fetch();

//eliminamos los años repetidos
$anios = array_unique($anios, SORT_REGULAR);

//hacemos una copia del array
$anio_1 = $anios;
array_pop($anio_1); 

$anio_2 = $anios;
array_shift($anio_2);