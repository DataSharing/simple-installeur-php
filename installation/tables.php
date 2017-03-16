<?php

Class Tables extends Install{

	public static function creation($db,$prefixe)
	{
		//Création de la table utilisateurs
		$tables[0] = "CREATE TABLE `" . $prefixe . "utilisateurs` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`nom` VARCHAR(50) NULL DEFAULT NULL,
			`prenom` VARCHAR(50) NULL DEFAULT NULL,
			`telephone` VARCHAR(10) NULL DEFAULT NULL,
			`mail` VARCHAR(150) NULL DEFAULT NULL,
			`id_groupe` INT(11) NULL DEFAULT NULL,
			`password` VARCHAR(255) NULL DEFAULT NULL,
			`salage` VARCHAR(255) NULL DEFAULT NULL,
			`date_creation` DATE NULL DEFAULT NULL,
			`vue` VARCHAR(10) NULL DEFAULT NULL,
			`active` INT(1) NULL DEFAULT '1',
			`est_archive` INT(11) NULL DEFAULT '0',
			PRIMARY KEY (`id`),
			INDEX `id_groupe` (`id_groupe`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		AUTO_INCREMENT=1
		;";

		//Création de la table droits
		$tables[1] = "CREATE TABLE `" . $prefixe . "droits` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`controller` VARCHAR(150) NOT NULL DEFAULT '0',
			`droit` INT(4) NOT NULL DEFAULT '0',
			`id_groupe` INT(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`),
			INDEX `id_groupe` (`id_groupe`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=MyISAM
		AUTO_INCREMENT=1
		;
		";

		//Création de la table groupes
		$tables[2] = "CREATE TABLE `" . $prefixe . "groupes` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`id_reference` INT(11) NULL DEFAULT '0',
			`nom` VARCHAR(150) NULL DEFAULT '0',
			`description` VARCHAR(255) NULL DEFAULT '0',
			`interface` VARCHAR(50) NULL DEFAULT NULL,
			`est_archive` INT(11) NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=MyISAM
		AUTO_INCREMENT=1
		;";

		//Création de la table logs
		$tables[3] = "CREATE TABLE IF NOT EXISTS `" . $prefixe . "logs` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			  `id_element` int(11) DEFAULT NULL,
			  `controller` varchar(150) DEFAULT NULL,
			  `modifie_par` varchar(150) DEFAULT NULL,
			  `date_modification` datetime DEFAULT NULL,
			  `action` varchar(255) DEFAULT NULL,
			  `id_utilisateur` INT(11) NOT NULL,
			  PRIMARY KEY (`id`))";

		//Création de vue droits_groupes
		$tables[4] = "CREATE VIEW ".$prefixe."droits_groupes AS select `droits`.`id_groupe` AS `id_groupe`,`droits`.`controller` AS `controller`,group_concat(`droits`.`droit` order by `droits`.`droit` ASC separator '+') AS `droit` from `droits` group by `droits`.`controller`,`droits`.`id_groupe`";

		//Création des tables;
		foreach($tables as $table){
			if(!$db->query($table)){
				return false;
			}
		}

		//Insertion groupe Administrateur
		if(!$db->query('insert into groupes(nom,description,id_reference) values("Administrateurs","Groupe administrateur",1)')){
			return false;
		}
		//Inscription des droits
		$controllers = array('accueil','parametres','groupes','profil','utilisateurs');
		$droits = array(7,77,777,7777);

		foreach($controllers as $controller){
			foreach($droits as $droit){
				if(!$db->query('insert into '.$prefixe.'droits(controller,droit,id_groupe) value("'.$controller.'",'.$droit.',1)')){
					return false;
				}
			}
		}
		return true;
	}

}