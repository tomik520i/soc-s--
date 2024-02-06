<?php
$db = new PDO(
    // parametry pripojeni
    "mysql:host=localhost;dbname=soc_sit;charset=utf8",
    "root", // prihlasovaci jmeno
    "", // heslo
    array(
        // v pripade sql chyby chceme aby to vyhazovalo vyjimky
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ),
);
