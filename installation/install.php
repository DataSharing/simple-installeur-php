 <?php

class Install {
        protected $db;
	public $msgerror;
        public $msgsucces;
        
	function index($prefixebdd = NULL,$hostname,$namebdd,$userbdd,$pwdbdd,$mail,$base_url,$nom_du_site,$password,$passwordconfirme){
            if($password == $passwordconfirme){
		if($this->TestConnection($hostname,$namebdd,$userbdd,$pwdbdd)){
                    if($this->CreateTableAndUser($mail,$password,$prefixebdd)){
			if($this->CreateFileDatabase($prefixebdd,$hostname,$namebdd,$userbdd,$pwdbdd)){
                            if($this->CreateFileConfigs($base_url,$nom_du_site)){
                                header('location:?code=0');
                            }else{
                                return 'Le fichier de configuration n\'a pas été créé, veuillez vérifier les droits sur le repertoire ! (fichier : config.php)';
                            }	
			}else{
                            return 'Le fichier de configuration n\'a pas été créé, veuillez vérifier les droits sur le repertoire ! (fichier : database.php)';
			}
                     }else{
                         return 'Erreur à la création de l\'utilisateur!';
                     }                       
		}else{
                    return $this->msgerror;
		}
            }else{
                return 'Vous avez fait une erreur à la saisie du mot de passe de confirmation!';
            }
	}
	
	function CreateFileDatabase($prefixebdd = NULL,$hostname,$namebdd,$userbdd,$pwdbdd){
		if(!is_dir('../config/')){
			if (!mkdir('../config/', 0664, true)) {
				die('Echec lors de la création des répertoires...');
			}
		}
		if(!$prefixebdd == ""){
			$prefixebdd = $prefixebdd."_";
		}
		$config = fopen('../config/database.php','w+');
                fputs($config, "<?php".PHP_EOL);
                fputs($config, '$database["prefixebdd"]= "'.$prefixebdd.'";'.PHP_EOL);
		fputs($config, '$database["hostname"]= "'.$hostname.'";'.PHP_EOL);
                fputs($config, '$database["namebdd"]= "'.$namebdd.'";'.PHP_EOL);
                fputs($config, '$database["userbdd"]= "'.$userbdd.'";'.PHP_EOL);
                fputs($config, '$database["passbdd"]= "'.$pwdbdd.'";'.PHP_EOL);
		fclose($config);
		return true;
	}
        
        function CreateFileConfigs($base_url,$nom_du_site){
		$config = fopen('../config/config.php','w+');
                fputs($config, "<?php".PHP_EOL);
				fputs($config, '$config["date_du_jour"]= date("Y-m-d H:i:s");'.PHP_EOL);
                fputs($config, '$config["nom_du_site"]= "'.$nom_du_site.'";'.PHP_EOL);
				fputs($config, '$config["controller_principal"]= "Accueil";'.PHP_EOL);
                fputs($config, '$config["base_url"]= "'.$base_url.'/";'.PHP_EOL);
				fputs($config, '$config["langage"] = "fr-FR";'.PHP_EOL);
		fclose($config);
		return true;
	}
	
        function CreateTableAndUser($mail,$password,$prefixebdd = NULL){
			if(!$prefixebdd == NULL){
				$prefixe = $prefixebdd."_";
			}else{
				$prefixe = "";
			}

            $create_table = "CREATE TABLE IF NOT EXISTS `" . $prefixe . "utilisateurs` (
                              `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
							  `nom` VARCHAR(50) NULL COMMENT '',
							  `prenom` VARCHAR(50) NULL COMMENT '',
							  `telephone` VARCHAR(10) NULL COMMENT '',
							  `ville` VARCHAR(150) NULL COMMENT '',
							  `mail` VARCHAR(150) NULL COMMENT '',
							  `acces` VARCHAR(20) NULL DEFAULT '',
							  `password` VARCHAR(255) NULL COMMENT '',
							  `salage` VARCHAR(255) NULL COMMENT '',
							  `date_creation` DATE NULL COMMENT '',
							  `active` INT(1) NULL DEFAULT '1',
							  `corbeille` INT(1) NULL DEFAULT '0',
							  PRIMARY KEY (`id`)  COMMENT '')
							  ENGINE = InnoDB
							  COMMENT = ''";
			$create_table_logs = 'CREATE TABLE IF NOT EXISTS `' . $prefixe . 'logs` (
									`id` int(11) NOT NULL AUTO_INCREMENT,
									  `id_element` int(11) DEFAULT NULL,
									  `controller` varchar(150) DEFAULT NULL,
									  `modifie_par` varchar(150) DEFAULT NULL,
									  `date_modification` datetime DEFAULT NULL,
									  `action` varchar(255) DEFAULT NULL,
									  `id_utilisateur` INT(11) NOT NULL,
									  PRIMARY KEY (`id`))';
            $create_user = 'insert into '.$prefixe.'utilisateurs(nom,prenom,mail,password,acces) values("Super","Admin","'.$mail.'","'.sha1($password).'","admin")';
            if($this->db->query($create_table) && $this->db->query($create_user) && $this->db->query($create_table_logs)){
                return true;
            }
        }
        
	function TestConnection($hostname,$namebdd,$userbdd,$pwdbdd){
		$DB = NULL;
		Try {
			$DB = new PDO("mysql:host=" . $hostname . ";dbname=" . $namebdd . "", "" . $userbdd . "", "" . $pwdbdd . "");
			$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db = $DB;
			return true;
		} catch (PDOException $e) {
			$this->msgerror = 'Échec lors de la connexion : ' . $e->getMessage();
		}
	}
	
	function InfosOk(){
		echo '<div class="card-panel teal lighten-2" role="alert">';
                echo '<span class="white-text text-darken-2">';
		echo "Connexion à la base de données : <i class='material-icons dp48'>thumb_up</i></br>";
                echo "Création du fichier database.php : <i class='material-icons dp48'>thumb_up</i></br>";
                echo "Création du fichier de configuration.php : <i class='material-icons dp48'>thumb_up</i></br>";
                echo "</span>";
		echo "</div>";
                echo "</br>";
                echo "<p style='color:orange'><b>N'oubliez pas de supprimer le dossier d'installation</b></p>";
                echo "<form action='' method='post'>";
                echo "<div align='center' ><input type='submit' class='waves-effect waves-light btn orange";
				if(isset($_POST['suppr_rep_install'])){
					echo " disabled'";
				}else{
					echo "'";
				}
				echo "onclick='preload();' id='suppr_rep_install' name='suppr_rep_install' value='Supprimer' /></br></br>";
                echo '<div class="preloader-wrapper active" id="patienter" style="display:none">';
                echo '<div class="spinner-layer spinner-red-only">';
                echo '<div class="circle-clipper left">';
                echo '<div class="circle"></div>';
                echo '</div><div class="gap-patch">';
                echo '<div class="circle"></div>';
                echo '</div><div class="circle-clipper right">';
                echo '<div class="circle"></div>';
                echo '</div>';
                echo '</div>';
                echo '</div></div>';
				if(isset($_POST['suppr_rep_install'])){
					echo '<h5 style="color:orange;text-align:center"><b><i>DOSSIER SUPPRIME !</b></i></h5>';
				}
                echo "</form>";
	}
        
        function supprimer_rep_install(){
            $ex_url = explode('/', $_SERVER['REQUEST_URI']);
            $dir = $_SERVER["DOCUMENT_ROOT"]."/".$ex_url[1]."/".$ex_url[2];
            if($this->deleteDir($dir)){
               header('location:../index.php');
            }
        }
        
        public static function deleteDir($dirPath) {
            if (! is_dir($dirPath)) {
                throw new InvalidArgumentException("$dirPath must be a directory");
            }
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }
}

?>