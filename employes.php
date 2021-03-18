<!DOCTYPE html>
<html>
<head>
	      <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
	      <title>Employes</title>
        <link rel="stylesheet" href="css/style.css">
</head>
<body>

      <div class="main">
        <ul>
          <li><a href="index.php">Accueil</a></li>
          <li><a href="competences.php">Competences</a></li>
          <li><a href="recherche-competences.php">Recherche competences</a></li>
        </ul>
      </div>

      <!--  Formulaire d'informations de l'employe  -->
	     <form method="post">
            <label>
                Nom:
                <?php if( array_key_exists('change_id',$_GET)):?>
                  <input type="text" name="nom" value="<?php echo $_GET['nom']?>">
                  
                <?php else:?>
                  <input type="text" name="nom">
                <?php endif?>
            </label>
            <label>
                Prenom:
                <?php if( array_key_exists('change_id',$_GET)):?>
                  <input type="text" name="prenoms" value="<?php echo $_GET['prenoms']?>">
                <?php else:?>
                  <input type="text" name="prenoms">
                <?php endif?>
            </label>
            <label>
                Date:
                <?php if( array_key_exists('change_id',$_GET)):?>
                  <input type="date" name="dnais" value="<?php echo $_GET['dnais']?>">
                <?php else:?>
                  <input type="text" name="dnais">
                <?php endif?>
            </label>
            <button type="submit">Enregistrer</button>
       </form>


      <!--  Formulaire de recherche  -->
        <form method="post" class="recherche">
            <label>
                Recherche :
                  <input type="text" name="mot">
            </label>
            <button type="submit">OK</button>
        </form>


      <!-- interactions avec la base de donne  -->
        <?php
            include_once "config.php";
  
            // Modification des informations

            if( array_key_exists('nom', $_POST) && array_key_exists('prenoms', $_POST) && array_key_exists('dnais', $_POST) && array_key_exists('change_id', $_GET) ){
               $idV= $_GET['change_id'];
                $stmt = $pdo->prepare(" UPDATE employe SET nom = :compnom, prenoms = :compprenoms, dnais = :compdnais  WHERE  id = :idc");
                $stmt->bindParam('compnom',$_POST['nom'],PDO::PARAM_STR);
                $stmt->bindParam('compprenoms',$_POST['prenoms'],PDO::PARAM_STR);
                $stmt->bindParam('compdnais',$_POST['dnais'],PDO::PARAM_STR);
                $stmt->bindParam('idc', $idV, PDO::PARAM_INT);
                
                try {
                    $stmt->execute();
                } catch (PDOException $e) {
                    exit($e->getMessage());
                }
                header("Location:employes.php");

                // Enregistrement des informations

                }elseif( array_key_exists('nom', $_POST) && array_key_exists('prenoms', $_POST) && array_key_exists('dnais', $_POST) ) {
                  $stmt = $pdo->prepare("INSERT INTO employe VALUES (NULL, :nom , :prenoms , :dnais)");
                  $stmt->bindParam("nom", $_POST['nom'], PDO::PARAM_STR);
                  $stmt->bindParam("prenoms", $_POST['prenoms'], PDO::PARAM_STR);
                  $stmt->bindParam("dnais", $_POST['dnais'], PDO::PARAM_STR);
                  try {
                      $stmt->execute();
                  } catch (PDOException $e) {
                      exit($e->getMessage());
                  }
                  header("Location:employes.php");
            }

            // Suppression des informations

            if (array_key_exists('delete_id', $_GET)) {
                 $stmt = $pdo->prepare("DELETE FROM employee_competences WHERE employe_id = :del");
                $stmt->bindParam('del', $_GET['delete_id'], PDO::PARAM_INT);
                try {
                    $stmt->execute();
                } catch (PDOException $e) {
                    exit($e->getMessage());
                }
                $stmt = $pdo->prepare("DELETE FROM employe WHERE id = :del");
                $stmt->bindParam('del', $_GET['delete_id'], PDO::PARAM_INT);
                try {
                    $stmt->execute();
                } catch (PDOException $e) {
                    exit($e->getMessage());
                }
                header("Location:employes.php");
            }

            // Recherche des informations

            if(array_key_exists("mot", $_POST)){
                $test = "%".$_POST["mot"]."%";
                $stmt = $pdo->query("SELECT * FROM employe where nom like '".$test."' or prenoms like '".$test."' or dnais like '".$test."'" );
                try{
                    $employe = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch( PDOException $resultat){
                   exit($resultat->getMessage());
                }
            } else {
                  // Liste des informations
                 $stmt = $pdo->query("SELECT * FROM employe");   
                 $employe = $stmt->fetchAll(PDO::FETCH_ASSOC);

            }
        ?>

      <!-- Tableau d'affichage des informations  -->
        <table border="1" width="100%" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOM</th>
                    <th>PRENOMS</th>
                    <th>DATE DE NAISSANCE</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
               
                <?php foreach ($employe as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= $c['nom'] ?></td>
                        <td><?= $c['prenoms'] ?></td>
                        <td><?= $c['dnais'] ?></td>
                        <td>
                            <button><a href="employes.php?delete_id=<?= $c['id']; ?>">Supprimer</a></button>
                             <button><a href="employes.php?change_id=<?= $c['id']; ?>&&nom=<?= $c['nom']; ?>&&prenoms=<?= $c['prenoms']; ?>&&dnais=<?= $c['dnais']?> ">Modifier</a></button>
                             <button><a href="employes-competences.php?prendre_id=<?= $c['id']; ?>&&nom=<?= $c['nom']; ?>&&prenoms=<?= $c['prenoms']?>&&dnais=<?= $c['dnais']?>">Afficher</a></button>
                             <button><a href="competences.php?empl_id=<?= $c['id']; ?>">Ajouter des competences</a></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        




</body>
</html>