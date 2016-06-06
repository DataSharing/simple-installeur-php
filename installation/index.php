<?php 
include 'install.php'; 
$install = New install();
$sansmdp = '';
$msgerror = '';
$msgsucces = '';
$code = -1;
if(isset($_POST['sansmdp'])) $sansmdp = $_POST['sansmdp'];
if(isset($_GET['code'])) $code = $_GET['code'];
if(isset($_POST['suppr_rep_install'])) { $install->supprimer_rep_install();}
if(isset($_POST['install'])){
	if($sansmdp == 'on'){
		if(!empty($_POST['hostname']) 
                    && !empty($_POST['namebdd']) 
                    && !empty($_POST['userbdd'])
                    && !empty($_POST['mail'])
                    && !empty($_POST['base_url'])
                    && !empty($_POST['nom_du_site'])
                    && !empty($_POST['password'])    
                    && !empty($_POST['passwordconfirme'])){
                    $msgerror = $install->index($_POST['prefixebdd'],
                            $_POST['hostname'],
                            $_POST['namebdd'],
                            $_POST['userbdd'],
                            NULL,
                            $_POST['mail'],
                            $_POST['base_url'],
                            $_POST['nom_du_site'],
                            $_POST['password'],
                            $_POST['passwordconfirme']);
		}
	}elseif(!empty($_POST['hostname']) 
                && !empty($_POST['namebdd']) 
                && !empty($_POST['userbdd']) 
                && !empty($_POST['pwdbdd'])
                && !empty($_POST['mail'])
                && !empty($_POST['base_url'])
                && !empty($_POST['nom_du_site'])
                && !empty($_POST['password'])    
                && !empty($_POST['passwordconfirme'])){
		$msgerror = $install->index($_POST['prefixebdd'],
                        $_POST['hostname'],
                        $_POST['namebdd'],
                        $_POST['userbdd'],
                        $_POST['pwdbdd'],
                        $_POST['mail'],
                        $_POST['base_url'],
                        $_POST['nom_du_site'],
                        $_POST['password'],
                        $_POST['passwordconfirme']);
	}else{
		$msgerror = 'Tous les champs sont obligatoires!';
	}
}

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <?php if(isset($_POST['suppr_rep_install'])){echo '<meta http-equiv="Refresh" content="5; url=../index.php" />';}?>
  <title>Installation ...</title>
  <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- Compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
<script>
    function preload() {
        document.getElementById("patienter").style.display = "block";
    }
</script>
    <style>
body {
	padding:0;
	margin:0;
	background:#f1f1f1;
}

.container {
	background:#fff;
	border:1px solid #d3e0e9;
	border-radius:5px;
	padding:3em;
	box-shadow:0 1px 8px #d3e0e9;
	width:450px;
}

h3 {
    margin:0 auto;
}
</style>
</head>
<body>
<div class='container' style='margin:1em auto;width:450px'>
<?php
if($code == 0){
	$install->InfosOk();
}else{
?>
	<form action='' method='post'>
            <h3><i>Installation app...</i></h3></br>   
                <div class="progress" style="display:none" id="patienter">
                    <div class="indeterminate"></div>
                </div>
		<?php 	if(!empty($msgerror)){ 
					echo '<div class="card-panel red lighten-1" role="alert">';
                                            echo '<span class="white-text text-darken-2">';
                                                echo $msgerror;
                                            echo "</span>";
					echo '</div>';
				}
		?>
                <input type="checkbox" name='sansmdp' id='sansmdp' /> 
                <label for='sansmdp'>Hébergeur locaux sans mot de passe Mysql</label>
                <h5>Infos site</h5>
		<input type='text' name='nom_du_site' class='form-control' placeholder='Nom du site' value='<?php if(isset($_POST['nom_du_site'])) echo $_POST['nom_du_site'] ?>'/></br>
		<input type='text' name='base_url' class='form-control' placeholder='exemple : http://localhost/site' value='<?php if(isset($_POST['base_url'])) echo $_POST['base_url'] ?>'/></br>
                <input type='email' name='mail' class='form-control' placeholder='Adresse mail' value='<?php if(isset($_POST['mail'])) echo $_POST['mail'] ?>'/></br>
                <input type='password' name='password' class='form-control' placeholder='mot de passe' /></br>
                <input type='password' name='passwordconfirme' class='form-control' placeholder='Confirmer le mot de passe' /></br>
		<h5>Base de données</h5>
		<input type='text' name='hostname' class='form-control' placeholder='Nom du serveur' value='<?php if(isset($_POST['hostname'])) echo $_POST['hostname'] ?>'/></br>
		<input type='text' name='namebdd' class='form-control' placeholder='Nom de la base de données' value='<?php if(isset($_POST['namebdd'])) echo $_POST['namebdd'] ?>'/></br>
                <input type='text' name='prefixebdd' class='form-control' placeholder='Préfixe des tables' value='<?php if(isset($_POST['prefixebdd'])) echo $_POST['prefixebdd'] ?>'/></br>
		<input type='text' name='userbdd' class='form-control' placeholder='Utilisateur' value='<?php if(isset($_POST['userbdd'])) echo $_POST['userbdd'] ?>'/></br>
		<input type='password' name='pwdbdd' class='form-control' placeholder='Mot de passe' /></br>
		<input type='submit' name='install' class='btn btn-success' onclick='preload()' style='width:100%' value='Installer' /></br>
		

	</form>
<?php } ?>
</div>
</body>
</html>