/* ACCUEIL récupération de champs de la table article avec le login 
de l'auteur et les éventuelles sections où se trouvent les articles 
Ne fonctionne que lorsqu'on a pas de catégories, ou une seule catégorie par article
*/

SELECT  a.id, a.letitre, a.letexte, a.ladate,
		au.id AS autid, au.lelogin, 
        r.id AS rubid, r.letitre AS rubtitre
	FROM article a 
		INNER JOIN auteur au ON au.id = a.auteur_id
			LEFT JOIN rubrique_has_article h ON a.id = h.article_id
			LEFT JOIN rubrique r ON r.id = h.rubrique_id
	ORDER BY a.ladate DESC;
    
/* ACCUEIL récupération de champs de la table article avec le login 
de l'auteur et les éventuelles sections où se trouvent les articles 
fonctionne si on à plusieures catégories pour au moins un article
*/

SELECT  a.id, a.letitre, a.letexte, a.ladate,
		au.id AS autid, au.lelogin, 
        GROUP_CONCAT(r.id) AS rubid, GROUP_CONCAT(r.letitre SEPARATOR '^|^') AS rubtitre
	FROM article a 
		INNER JOIN auteur au ON au.id = a.auteur_id
			LEFT JOIN rubrique_has_article h ON a.id = h.article_id
			LEFT JOIN rubrique r ON r.id = h.rubrique_id
	GROUP BY a.id
	ORDER BY a.ladate DESC;

/* idem en ordonnant les sections par titre desc (! corresspondance de idrub!) */
SELECT  a.id, a.letitre, a.letexte, a.ladate,
		au.id AS autid, au.lelogin, 
        GROUP_CONCAT(r.id ORDER BY r.letitre ASC ) AS rubid, 
        GROUP_CONCAT(r.letitre ORDER BY r.letitre ASC SEPARATOR '^|^') AS rubtitre
	FROM article a 
		INNER JOIN auteur au ON au.id = a.auteur_id
			LEFT JOIN rubrique_has_article h ON a.id = h.article_id
			LEFT JOIN rubrique r ON r.id = h.rubrique_id
	GROUP BY a.id
	ORDER BY a.ladate DESC;

/*
JOINTURE INTERNE (INNER JOIN)
*/
    
/* AVEC INNER seules les tables jointes seront sélectionnées */
SELECT  a.id, a.letitre, a.letexte, a.ladate,
		au.id AS autid, au.lelogin 
	FROM article a 
		INNER JOIN auteur au ON au.id = a.auteur_id
	GROUP BY a.id
	ORDER BY a.ladate DESC;
  
/*
JOINTURES EXTERNEs (LEFT, RIGHT, ...)
*/  

/* AVEC LEFT Tous les résultats de la table appelée par le FROM seront affichés, et les résultats 
joints à la droite LEFT JOIN seront affichés si il y a un lien avec la table qui suit le JOIN */
SELECT  a.id, a.letitre, a.letexte, a.ladate,
		au.id AS autid, au.lelogin 
	FROM article a 
		LEFT JOIN auteur au ON au.id = a.auteur_id
	GROUP BY a.id
	ORDER BY a.ladate DESC;
    
/* AVEC RIGHT Tous les résultats de la table à la droite du LEFT JOIN seront affichés 
ainsi que les résultats appelés après le FROM  si il y a un lien avec la table qui suit le JOIN */
SELECT  a.id, a.letitre, a.letexte, a.ladate,
		au.id AS autid, au.lelogin 
	FROM article a 
		RIGHT JOIN auteur au ON au.id = a.auteur_id
	GROUP BY a.id
	ORDER BY a.ladate DESC;
    
    