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

   $requete = $bdd->query("SELECT firstname, lastname, pseudo, password, email FROM users WHERE pseudo = '". $_SESSION['pseudo'] ."'");
   $donnees = $requete->fetch();

   include("../html/myaccount.htm");


   // Si on clique sur enregistrer
   if(isset($_POST['Enregistrer'])) {

      $new_firstname = htmlspecialchars($_POST['firstname']);
      $new_lastname = htmlspecialchars($_POST['lastname']);
      $new_email = htmlspecialchars($_POST['email']);

      // On vérifie si un des champs des mots de passe est rentré
      if(($_POST['old_password'] != NULL) || ($_POST['new_password_1'] != NULL) || ($_POST['new_password_2'] != NULL))
      {
         // On vérifie lesquels ne sont pas renseignés
         if($_POST['old_password'] == NULL)
         {
            echo "Vous n'avez pas rentré votre ancien mot de passe";
         } else if($_POST['new_password_1'] == NULL)
         {
            echo "Vous n'avez pas rentré votre nouveau mot de passe";
         } else if($_POST['new_password_2'] == NULL)
         {
            echo "Vous n'avez pas rentré la confirmation de votre nouveau mot de passe";
         } else { // Si les 3 sont renseignés on les ajoute dans l'insertion de la BDD
            $old_password = sha1($_POST['email']);
            $new_password_1 = sha1($_POST['new_password_1']);
            $new_password_2 = sha1($_POST['new_password_2']);

            // Seulement s'ils sont conformes
            if ($old_password != $new_password_2 || empty($new_password_1) || empty($new_password_2))
            {
               echo "Soit vous avez rentré un faux mot de passe, soit votre nouveau mot de passe et votre confirmation diffèrent";
            } else { // Insertion SQL
               $sql = "UPDATE users SET firstname = '" . $new_firstname . "', lastname = '" . $new_lastname;
               $sql .= "', email = '" . $new_email . "', password = '" . $new_password_1 . " WHERE pseudo = '" . $_SESSION['pseudo'] . "'";
               $requete = $bdd->exec($sql); //requete avec changement de mot de passe
            }
         }
      } else { // Sinon on insert dans la BDD (on écrase toutes les infos) sans les mots de passe
         $sql = "UPDATE users SET firstname = '" . $new_firstname . "', lastname = '" . $new_lastname . "', email = '" . $new_email . "' WHERE pseudo = '" . $_SESSION['pseudo'] . "'";
         $requete = $bdd->exec($sql); //requete sans changement de mot de passe
      }
      // Refresh page pour actualiser les infos dans les inputs, je n'ai rien trouvé de mieux
      // /!\ /!\ /!\ /!\ ATTENTION FORAIN /!\ /!\ /!\ /!\
      ?>
      <script>
      window.location.reload();
      </script>
      <?php
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
