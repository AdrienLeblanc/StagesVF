<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
   <title>Déconnexion</title>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" href="../../favicon.ico">
   <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
         <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            </button>
            <a class="navbar-brand" href="../../index.php">Accueil</a>
         </div>
         <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
               <li><a href="sign_in.php">Identification</a></li>
               <li><a href="import.php">Importation de données</a></li>
               <li><a href="export.php">Exportation de données</a></li>
               <?php
               if(isset($_SESSION['pseudo']))
               { ?>
                  <li><a href="myaccount.php">Mon compte</a></li>
                  <?php
               }
               if(isset($_SESSION['status']))
               {
                  if($_SESSION['status']=="Administrateur")
                  { ?>
                     <li><a href="moderation.php">Modération</a></li>
                     <?php
                  }
               }
               ?>
            </ul>
         </div><!--/.nav-collapse -->
      </div>
   </div>
   <p style="text-align:center;margin-top:10%">Vous êtes à présent déconnecté(e) <br />
      Cliquez <a href="../../index.php">ici</a> pour revenir à la page principale</p>
      <?php
      header("refresh:2; url=../../index.php");
      ?>
</body>
</html>