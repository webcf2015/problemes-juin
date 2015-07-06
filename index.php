<?php
// fichiers requis
require_once 'config.php';
require_once 'fonctions.php';
require_once 'connectdb.php';

// requête qui récupère les rubriques
$recup_rub = mysqli_query($connect,"SELECT * FROM rubrique ORDER BY letitre ASC;")or die(mysqli_error($connect));



/*
 * 
 * Système de pagination
 * ---------------------
 * Paramètres importants:
 * 1) nombre total d'articles à afficher sur l'accueil ou la section (variable!)
 * 2) page actuelle (par défaut page 1, doit changer avec la variable de type get pour la pagination, ici "page" dans config.php)
 * 3) nombre par page (voir config.php)
 * 4) Nom de la variable GET de pagination (config.php)
 * 
 * 
 * A) !!! Ne pas oublier de changer les variables LIMIT dans la requête sql de sélection d'articles pour que celle-ci s'adapte à la pagination
 * B) Si on est dans une section 
 * - vérification de la variable get "idrub"
 * - on doit compter le nombre d'articles DANS cette section (modification du point 1)
 * - modification de la requête du point A) (si simplifiée en retirant les sections de l'affichage, si on souhaite les garder il faut utiliser des SELECT imbriqués)
 * 
 * 5) Si on a plusieures variables dans l'url, on complète celle-ci avec la chaine de caractère nécessaire
 * 
 * 
 * 
 */
// ----------- BEGIN B)

// vérification de la variable get idrub
if(isset($_GET['idrub'])&&  ctype_digit($_GET['idrub'])){
    // variable locale contant idrub
    $idrubrique = $_GET['idrub'];
    // variable qui contient l'sql pour compter le nombre d'élément dans cette rubrique
    $dans_rubrique = "INNER JOIN rubrique_has_article h ON a.id = h.article_id WHERE h.rubrique_id = $idrubrique";
}else{
    // chaine vide si on est pas dans une section
    $dans_rubrique = "";
}

// création de la jointure dans une variable que l'on va insérée dans $sql de 1)

// ----------- END B)

// ----------- BEGIN 1

$sql = "SELECT COUNT(*) AS nombre_tot_articles FROM article a $dans_rubrique ;";
$total_articles = mysqli_query($connect,$sql) or die(mysqli_error($connect));
$total_articles = mysqli_fetch_assoc($total_articles);
// ----------- END 1


// ----------- BEGIN 2 Vérification de la page actuelle grâce à la variable GET (utilisation de la variable se trouvant dans config.php)

if(isset($_GET[$nom_variable_get_pg])&&  ctype_digit($_GET[$nom_variable_get_pg])){
    $pg_actuelle = $_GET[$nom_variable_get_pg];
}else{
    $pg_actuelle = 1;
}

// ----------- END 2

// LES POINTS 3 et 4 sont implicites

// ----------- BEGIN A)

// calcul pour le LIMIT sql
$debut= ($pg_actuelle-1)*$nb_par_pg;
$fin=$nb_par_pg; // point 3

// si on est sur l'accueil (pas de rubrique)
if(!isset($idrubrique)){
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
	ORDER BY a.ladate DESC
        LIMIT $debut, $fin;
        ")or die(mysqli_error($connect)
        );

// ----------- 5
    $chaine_url="";
    
    
// on est dans une rubrique
}else{
    
    /* SOLUTION 1 - simple mais il vaut mieux retirer le lien d'affichage des rubriques */
   $recup_articles = mysqli_query($connect,"
    SELECT  a.id, a.letitre, a.
            letexte, a.ladate,
            au.id AS autid, au.lelogin, 
        GROUP_CONCAT(r.id) AS rubid, 
        GROUP_CONCAT(r.letitre SEPARATOR '^|^') AS rubtitre
	FROM article a 
		INNER JOIN auteur au ON au.id = a.auteur_id
		INNER JOIN rubrique_has_article h ON a.id = h.article_id
		INNER JOIN rubrique r ON r.id = h.rubrique_id
        WHERE r.id = $idrubrique
	GROUP BY a.id
	ORDER BY a.ladate DESC
        LIMIT $debut, $fin;
        ")or die(mysqli_error($connect)
        ); 
   
   /* SOLUTION 2 -  */
   $recup_articles = mysqli_query($connect,"
    SELECT  a.id, a.letitre, a.
            letexte, a.ladate,
            au.id AS autid, au.lelogin, 
         (SELECT GROUP_CONCAT(r.id) FROM rubrique r
         INNER JOIN rubrique_has_article h ON r.id = h.rubrique_id
         INNER JOIN article ar ON ar.id = h.article_id
WHERE ar.id=a.id) AS rubid,
        (SELECT GROUP_CONCAT(r.letitre SEPARATOR '^|^') FROM rubrique r  INNER JOIN rubrique_has_article h ON r.id = h.rubrique_id
         INNER JOIN article ar ON ar.id = h.article_id
WHERE ar.id=a.id) AS rubtitre
	FROM article a 
		INNER JOIN auteur au ON au.id = a.auteur_id
		INNER JOIN rubrique_has_article h ON a.id = h.article_id
 
        WHERE h.rubrique_id = $idrubrique
	GROUP BY a.id
	ORDER BY a.ladate DESC
        LIMIT $debut, $fin;
        ")or die(mysqli_error($connect)
        ); 
   
  // ----------- 5
    $chaine_url="idrub=$idrubrique&";
}
// ----------- END A)

$lapagination = pagination($total_articles['nombre_tot_articles'], $pg_actuelle, $nb_par_pg, $nom_variable_get_pg,$chaine_url);
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
                echo "<li><a href='".CHEMIN."?idrub=".$ligne['id']."'>".$ligne['letitre']."</a></li>";
            }
            ?>
            <li><a href='<?=CHEMIN?>insert.php'>Admin</a></li>
        </ul>
        <nav><?=$lapagination?></nav>
        <?php
        while($ligne=  mysqli_fetch_assoc($recup_articles)){
            echo "<div id='article'>".
            "<h1 style='color:blue;'>".$ligne['letitre']."</h1>".
            "<h4> par  <i>".$ligne['lelogin']." </i>le <i> ".$ligne['ladate']."</i></h4>".
            "<h3>".$ligne['letexte']."</h3>".        
            "</div>";
        
        
        $rubrik = explode('^|^',$ligne['rubtitre']);
        $rub_id = explode(',', $ligne['rubid']);
        foreach($rubrik AS $cle => $value)
        {
            echo'<span><a href="'.CHEMIN.'?idrub='.$rub_id[$cle].'">'.$value.'</a></span> &nbsp;';
        }
         echo '<hr/><br/>';       
        }
        // put your code here
        ?>
        <nav><?=$lapagination?></nav>
    </body>
</html>
