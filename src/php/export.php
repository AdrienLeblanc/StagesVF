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

   if(!isset($_SESSION['messagesParPage']))
   {
      $_SESSION['messagesParPage'] = 5;
   }

   if(isset($_POST['nb_elem']))
   {
      $_SESSION['messagesParPage'] = htmlspecialchars($_POST['select_nb_elem']);
   }

   include ('../html/export.htm');

   $retour_total = $bdd->query('SELECT COUNT(*) AS total FROM files'); //Nous récupérons le contenu de la requête dans $retour_total
   $donnees_total = $retour_total->fetch(); //On range retour sous la forme d'un tableau.
   $total = $donnees_total['total']; //On récupère le total pour le placer dans la variable $total.



   //Nous allons maintenant compter le nombre de pages.
   $nombreDePages = ceil($total / $_SESSION['messagesParPage']);

   if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
   {
      $pageActuelle = intval($_GET['page']);
      if($pageActuelle > $nombreDePages) // Si la valeur de $pageActuelle (le numéro de la page) est plus grande que $nombreDePages...
      {
         $pageActuelle = $nombreDePages;
      }
   }
   else // Sinon
   {
      $pageActuelle = 1; // La page actuelle est la n°1
   }

   $premiereEntree=($pageActuelle-1)*$_SESSION['messagesParPage']; // On calcul la première entrée à lire

   // La requête sql pour récupérer les messages de la page actuelle.
   $reponse = $bdd->query('SELECT * FROM files ORDER BY up_id DESC LIMIT '.$premiereEntree.', '.$_SESSION['messagesParPage'].'');

   ?>
   <table class="table table-striped" style="margin:auto; width:600px;table-layout:fixed; word-wrap:break-word;">
      <thead>
         <tr>
            <th class="col-md-1 col-xs-1">ID</th>
            <th class="col-md-2 col-xs-2">Type de données</th>
            <th class="col-md-4 col-xs-4">Nom fichier</th>
            <th class="col-md-2 col-xs-2">Date upload</th>
            <th class="col-md-2 col-xs-2">Action</th>
         </tr>
      </thead>
      <tbody class="searchable">
         <?php
         while ($donnees = $reponse->fetch())
         {
            ?>
            <tr>
               <td> <?php echo "<b>".$donnees['up_id']."</b>"; ?> </td>
               <td> <?php echo $donnees['up_type']; ?> </td>
               <td> <?php echo "<b>".$donnees['up_filename']."</b>"; ?> </td>
               <td> <?php echo $donnees['up_filedate']; ?> </td>
               <td>
                  <form method="post" action="filedownload.php">
                     <input type="hidden" name="id" value="<?php echo htmlspecialchars($donnees['up_id']); ?>" />
                     <input type="submit" class="btn btn-primary" value="Télécharger">
                  </form>
               </td>
            </tr>
            <?php
         }
         ?>
      </tbody>
   </table>
   <div class="text-center">
      <ul class="pagination">
         <li>
            <?php if ($pageActuelle - 1 < 1) { ?>
               <a href="export.php?page=<?php echo $pageActuelle ?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
               </a>
               <?php
            } else { ?>
               <a href="export.php?page=<?php echo $pageActuelle - 1?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
               </a>
               <?php
            }
            ?>
         </li>
         <?php
         for ($i = 1; $i <= $nombreDePages; $i++) //On fait notre boucle
         {
            //On va faire notre condition
            if($i == $pageActuelle) //S'il s'agit de la page actuelle...
            {
               echo '<li class="active"><a href="#">'.$i.'</a></li>';
            }
            else //Sinon...
            {
               echo ' <li><a href="export.php?page='.$i.'">'.$i.'</a></li>';
            }
         }
         ?>
         <li>
            <?php if ($pageActuelle + 1 > $nombreDePages) { ?>
               <a href="export.php?page=<?php echo $pageActuelle ?>" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
               </a>
               <?php
            } else { ?>
               <a href="export.php?page=<?php echo $pageActuelle + 1 ?>" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
               </a>
               <?php
            }
            ?>
         </li>
      </ul>
   </div>
   <?php
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
