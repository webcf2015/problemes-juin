-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema problemes_juin
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema problemes_juin
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `problemes_juin` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `problemes_juin` ;

-- -----------------------------------------------------
-- Table `problemes_juin`.`droit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `problemes_juin`.`droit` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `letitre` VARCHAR(60) NULL,
  `laperm` SMALLINT UNSIGNED NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `problemes_juin`.`auteur`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `problemes_juin`.`auteur` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lelogin` VARCHAR(45) NULL,
  `lemdp` VARCHAR(45) NULL,
  `droit_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `lelogin_UNIQUE` (`lelogin` ASC),
  INDEX `fk_auteur_droit_idx` (`droit_id` ASC),
  CONSTRAINT `fk_auteur_droit`
    FOREIGN KEY (`droit_id`)
    REFERENCES `problemes_juin`.`droit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `problemes_juin`.`article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `problemes_juin`.`article` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `letitre` VARCHAR(60) NULL,
  `letexte` TEXT NULL,
  `ladate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `auteur_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_article_auteur1_idx` (`auteur_id` ASC),
  CONSTRAINT `fk_article_auteur1`
    FOREIGN KEY (`auteur_id`)
    REFERENCES `problemes_juin`.`auteur` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `problemes_juin`.`rubrique`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `problemes_juin`.`rubrique` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `letitre` VARCHAR(60) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `problemes_juin`.`rubrique_has_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `problemes_juin`.`rubrique_has_article` (
  `rubrique_id` INT UNSIGNED NOT NULL,
  `article_id` INT UNSIGNED NOT NULL,
  INDEX `fk_rubrique_has_article_article1_idx` (`article_id` ASC),
  INDEX `fk_rubrique_has_article_rubrique1_idx` (`rubrique_id` ASC),
  CONSTRAINT `fk_rubrique_has_article_rubrique1`
    FOREIGN KEY (`rubrique_id`)
    REFERENCES `problemes_juin`.`rubrique` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rubrique_has_article_article1`
    FOREIGN KEY (`article_id`)
    REFERENCES `problemes_juin`.`article` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `problemes_juin`;

DELIMITER $$
USE `problemes_juin`$$
$$

USE `problemes_juin`$$
$$


DELIMITER ;

-- -----------------------------------------------------
-- Data for table `problemes_juin`.`droit`
-- -----------------------------------------------------
START TRANSACTION;
USE `problemes_juin`;
INSERT INTO `problemes_juin`.`droit` (`id`, `letitre`, `laperm`) VALUES (DEFAULT, 'Administrateur', 0);
INSERT INTO `problemes_juin`.`droit` (`id`, `letitre`, `laperm`) VALUES (DEFAULT, 'Modérateur', 1);
INSERT INTO `problemes_juin`.`droit` (`id`, `letitre`, `laperm`) VALUES (DEFAULT, 'Utilisateur', 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `problemes_juin`.`auteur`
-- -----------------------------------------------------
START TRANSACTION;
USE `problemes_juin`;
INSERT INTO `problemes_juin`.`auteur` (`id`, `lelogin`, `lemdp`, `droit_id`) VALUES (DEFAULT, 'admin', 'admin', 1);
INSERT INTO `problemes_juin`.`auteur` (`id`, `lelogin`, `lemdp`, `droit_id`) VALUES (DEFAULT, 'modo', 'modo', 2);
INSERT INTO `problemes_juin`.`auteur` (`id`, `lelogin`, `lemdp`, `droit_id`) VALUES (DEFAULT, 'util1', 'util1', 3);
INSERT INTO `problemes_juin`.`auteur` (`id`, `lelogin`, `lemdp`, `droit_id`) VALUES (DEFAULT, 'util2', 'util2', 3);

COMMIT;


-- -----------------------------------------------------
-- Data for table `problemes_juin`.`rubrique`
-- -----------------------------------------------------
START TRANSACTION;
USE `problemes_juin`;
INSERT INTO `problemes_juin`.`rubrique` (`id`, `letitre`) VALUES (DEFAULT, 'Sport');
INSERT INTO `problemes_juin`.`rubrique` (`id`, `letitre`) VALUES (DEFAULT, 'Eté');
INSERT INTO `problemes_juin`.`rubrique` (`id`, `letitre`) VALUES (DEFAULT, 'Politique');
INSERT INTO `problemes_juin`.`rubrique` (`id`, `letitre`) VALUES (DEFAULT, 'Fiction');
INSERT INTO `problemes_juin`.`rubrique` (`id`, `letitre`) VALUES (DEFAULT, 'Cf2m');

COMMIT;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
