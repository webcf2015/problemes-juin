<?php
// fichiers requis
require_once 'config.php';
require_once 'fonctions.php';
require_once 'connectdb.php';

// requête qui récupère les rubriques
$recup_rub = mysqli_query($connect,"SELECT * FROM rubrique ORDER BY letitre ASC;")or die(mysqli_error($connect));

// ACCUEIL récupération de champs de la table article avec le login de l'auteur et les éventuelles sections où se trouvent les articles
$recup_articles = mysqli_query($connect,"
    SELECT  a.id, a.letitre, a.
            letexte, a.ladate,
            au.id AS autid, au.lelogin, 
        GROUP_CONCAT(r.id) AS rubid, 
        GROUP_CONCAT(r.letitre SEPARATOR '^|^') AS rubtitre
	FROM article a 
		INNER JOIN auteur au ON au.id = a.auteur_id
		LEFT JOIN rubrique_has_article h ON a.id = h.article_id
		LEFT JOIN rubrique r ON r.id = h.rubrique_id
	GROUP BY a.id
	ORDER BY a.ladate DESC;
        ")or die(mysqli_error($connect)
        );


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Accueil</title>
    </head>
    <body>

        <ul>
            <li><a href='<?=CHEMIN?>'>Accueil</a></li>
            <?php

            while($ligne=  mysqli_fetch_assoc($recup_rub)){
                echo "<li><a href='".CHEMIN."/?idrub=.".$ligne['id']."'>".$ligne['letitre']."</a></li>";
            }
            ?>
            <li><a href='<?=CHEMIN?>/insert.php'>Admin</a></li>
        </ul>
        <?php
        // put your code here
        ?>
    </body>
</html>
