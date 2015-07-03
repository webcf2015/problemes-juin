<?php

/*
 * fonction de pagination avec comme argument : 
 * 
 * pagination(int => nombre total d'articles [obligatoire], 
 *            int => page actuelle commence par 1 [1 comme valeur par défaut],
 *            int => nombre d'articles par page [défaut = 5], 
 *            string => nom de la variable get [défaut = "pg"]
 * )
 *            string => chaine complémentaire pour l'url de la variable get [défaut = ""]
 * 
 * 
 */

function pagination($total, $page_actu = 1, $par_pg = 5, $var_get = "pg",$chaine_url="") {
    $nombre_pg = ceil($total / $par_pg);
    if ($nombre_pg > 1) {
        $sortie = "Page ";
        for ($i = 1; $i <= $nombre_pg; $i++) {
            if ($i == 1) {
                if ($i == $page_actu) {
                    $sortie.= "<< < ";
                } else {
                    $sortie.= "<a href='?$chaine_url$var_get=$i'><<</a> <a href='?$chaine_url&$var_get=" . ($page_actu - 1) . "'><</a> ";
                }
            }
            if ($i != $page_actu) {
                if($i==1){
                    $lien = (stripos($_SERVER['PHP_SELF'],"index.php")&& !stripos($_SERVER['PHP_SELF'],"/index.php/"))? "./" : $_SERVER['PHP_SELF'];
                    $sortie .= "<a href='".$lien."?$chaine_url'>$i</a>";
                }else{
                    $sortie .= "<a href='?$chaine_url$var_get=$i'>$i</a>";
                }
            } else {
                $sortie .= " $i ";
            }
            if ($i != $nombre_pg) {
                $sortie.= " - ";
            } else {
                if ($i == $page_actu) {
                    $sortie.=" > >>";
                } else {
                    $sortie.= " <a href='?$chaine_url$var_get=" . ($page_actu + 1) . "'>></a> <a href='?$chaine_url$var_get=$nombre_pg'>>></a> ";
                }
            }
        }
        return $sortie;
    } else {
        return "Page 1";
    }
}
