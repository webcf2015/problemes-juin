<?php
// fichiers requis
require_once 'config.php';
require_once 'fonctions.php';
require_once 'connectdb.php';

// si formulaire envoyé
if(isset($_POST['letitre'])&&isset($_POST['letexte'])&&isset($_POST['lauteur'])&&  ctype_digit($_POST['lauteur'])){
    $letitre = htmlspecialchars(strip_tags(trim($_POST['letitre'])),ENT_QUOTES);
    $letexte = htmlspecialchars(trim($_POST['letexte']),ENT_QUOTES);
    $lauteur = $_POST['lauteur'] ;
    
    $insert_article = mysqli_query($connect, "INSERT INTO article (letitre, letexte,auteur_id) VALUES ('$letitre','$letexte',$lauteur)");
    
    // récupération de l'id de l'article
    $id_article = mysqli_insert_id($connect);
    
    // condition ternaire : récupération des rubriques si présentes
    // (true)? true : false; // (false)? true : false;
    //           ^                                ^
    (isset($_POST['section']))? $rub = $_POST['section'] : $rub = false;
    // si on a des rubriques
    if($rub){
        $sql = "INSERT INTO rubrique_has_article (rubrique_id,article_id) VALUES ";
        foreach($rub AS $value){
            $sql .="($value, $id_article),"; 
        }
        echo $sql = substr($sql, 0,-1);
    }
    mysqli_query($connect,$sql); 
    header("Location: ./");
}

// requête qui récupère les rubriques
$recup_rub = mysqli_query($connect,"SELECT * FROM rubrique ORDER BY letitre ASC;")or die(mysqli_error($connect));

// requête qui récupère les auteurs
$recup_util = mysqli_query($connect,"SELECT * FROM auteur ORDER BY lelogin ASC;")or die(mysqli_error($connect));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Insert provisoire</title>
    </head>
    <body>
        <ul>
            <li><a href='<?=CHEMIN?>'>Accueil</a></li>
            <?php

            while($ligne=  mysqli_fetch_assoc($recup_rub)){
                echo "<li><a href='".CHEMIN."?idrub=.".$ligne['id']."'>".$ligne['letitre']."</a></li>";
            }
            ?>
            <li><a href='<?=CHEMIN?>/insert.php'>Admin</a></li>
        </ul>
        <form action="" method="POST" name="envoie">
            <input type="text" name='letitre' required /><br/>
            <textarea name="letexte" required></textarea><br/>
            Auteur : <select name='lauteur' required>
              <?php
              
            while($ligne=  mysqli_fetch_assoc($recup_util)){
                echo "<option value=".$ligne['id'].">".$ligne['lelogin']."</option>";
            }
        ?>  
            </select><br/>
            <?php
            // on remet le pointeur au début du tableau de résultat pour éviter de refaire une requête inutile
            mysqli_data_seek($recup_rub,0); 
            while($ligne=  mysqli_fetch_assoc($recup_rub)){
                echo $ligne['letitre']." <input type='checkbox' name='section[]' value='".$ligne['id']."'/> | ";
            }
            // on vide le résultat sql
            mysqli_free_result($recup_rub);
            ?>
            <input type="submit" value="envoyer" />
        </form>
        
    </body>
</html>
