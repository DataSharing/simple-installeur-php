# Installation
- Coller le dossier "installation" à la racine de votre site.
- Dans le fichier "index.php" de votre site, faire une vérification de l'existance du dosser "installation"
- Si le dossier existe, alors on fait une redirection sur "installation/index.php"
https://github.com/DataSharing/simple-installeur-php/blob/master/installation.png

-Une fois valider, l'installeur va créer:
--./config
---database.php
----$database['prefixebdd'] = "prefixe_";
----$database['hostname'] = "localhost";
----$database['namebdd'] = "mybdd";
----$database['userbdd'] = "root";
----$database['passbdd'] = "root";
--config.php
---$config['date_du_jour'] = date("Y-m-d H:i:s");
---$config['nom_du_site'] = "Mon site";
---$config['controller_principal'] = "Accueil";
---$config['base_url'] = "http://localhost/monsite/";
---$config['langage'] = "fr-FR";
