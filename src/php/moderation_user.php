<?php
session_start();
if (isset($_SESSION['connect'])) // On vérifie que le variable existe.
{
   $connect = $_SESSION['connect']; // On récupère la valeur de la variable de session.
}
else
{
   $connect = 0; // Si $_SESSION['connect'] n'existe pas, on donne la valeur "0".
}

if ($connect == "1") // Si le visiteur s'est identifié.
{
   /* CONNEXION BDD */
   include ('../../config/connection.php');
   $bdd = connexionMySQL();
   /* CONNEXION FAITE */

   function modifyUserRights($bdd, $newRight)
   {
      $user = htmlspecialchars($_POST['pseudo']);
      $requete = $bdd->exec("UPDATE users SET id_status = '" . $newRight . "' WHERE pseudo = '" . $user . "'");

      switch ($newRight) {
         case 2:
         echo '<label style="display:block;text-align:center"> L\'utilisateur ' . $user . ' est à présent Utilisateur</label>';
         break;
         case 3:
         echo '<label style="display:block;text-align:center"> L\'utilisateur ' . $user . ' est à présent Fournisseur</label>';
         break;
         case 4:
         echo '<label style="display:block;text-align:center"> L\'utilisateur ' . $user . ' est à présent Gestionnaire</label>';
         break;
         case 5:
         echo '<label style="display:block;text-align:center"> L\'utilisateur ' . $user . ' est à présent Administrateur</label>';
         break;
      }
   }

   include('../html/moderation_user.htm');

   // Si le bouton 'valider' est cliqué, alors on écrase le statut choisi sur le statut de l'utilisateur
   if (isset($_POST['status_validation']))
   {
      modifyUserRights($bdd, htmlspecialchars($_POST['change_status']));
   }
} else {
   echo '<p style="text-align:center">Vous n\'êtes pas autorisé(e) à acceder à cette zone</p>';
   ?>
   <head>
      <meta charset="utf-8" />
      <title>Connexion VegFrance</title>
   </head>
   <?php
   include('../html/sign_in.htm');
   exit;
}
?>
