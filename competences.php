
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Compétence</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>

        <div class="main">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="employes.php">Employes</a></li>
            </ul>
        </div>
      
        <form method="post">
            <label>
                Compétence:
                <?php if( array_key_exists('modifier_id',$_GET)):?>
                  <input type="text" name="competence" value="<?php echo $_GET['competence']?>">
                <?php else:?>
                  <input type="text" name="competence">
                <?php endif?>
            </label>
            <button type="submit">Enregistrer</button>
        </form>
        <form method="post" class="recherche">
            <label>
                Recherche :
                  <input type="text" name="mot">
            </label>
            <button type="submit">OK</button>
        </form>

        <?php
            include_once "config.php";

            // 1. Enregistrement des compétence et Modifications
            //partie modification
            if( array_key_exists('competence', $_POST) && array_key_exists('modifier_id', $_GET) )
            {   
                $idV= $_GET['modifier_id'];
                $stmt = $pdo->prepare(" UPDATE competences SET competence = :comp  WHERE  id = :idc");
                $stmt->bindParam('comp',$_POST['competence'],PDO::PARAM_STR);
                $stmt->bindParam('idc', $idV, PDO::PARAM_INT);
                try{
                    $stmt->execute();
                }catch(PDOException $e){
                    exit($e->getMessage());
                }
                header("Location:competences.php");
                //parite enregistrement
                }elseif(array_key_exists('competence', $_POST)) {
                  $inter ="%".$_POST['competence']."%";
                  $stmt = $pdo->prepare("SELECT id FROM competences WHERE competence like ?");
                  $stmt->bindParam(1,$inter);
                  try{
                    $stmt->execute();
                  }catch(PDOException $e){
                    exit($e->getMessage());
                  }
                  $vide = $stmt->fetchAll();
                  if(empty($vide)){
                  $stmt = $pdo->prepare("INSERT INTO competences VALUES (NULL, :competence)");
                  $stmt->bindParam("competence", $_POST['competence'], PDO::PARAM_STR);
                  try {
                      $stmt->execute();
                  } catch (PDOException $e) {
                      exit($e->getMessage());
                  }
                     header("Location:competences.php");
                  }
                }

            // 2. Suppression
            if (array_key_exists('delete_id', $_GET)) {

                $stmt = $pdo->prepare("DELETE FROM employee_competences WHERE competence_id = :del");
                $stmt->bindParam('del', $_GET['delete_id'], PDO::PARAM_INT);
                try {
                    $stmt->execute();
                } catch (PDOException $e) {
                    exit($e->getMessage());
                }
                $stmt = $pdo->prepare("DELETE FROM competences WHERE id = :del");
                $stmt->bindParam('del', $_GET['delete_id'], PDO::PARAM_INT);
                try {
                    $stmt->execute();
                } catch (PDOException $e) {
                    exit($e->getMessage());
                }
                header("Location:competences.php");
            }

            

//a revoir       
            //Partie recherche

            if(array_key_exists("mot", $_POST)){
                $test = "%".$_POST["mot"]."%";
                //echo $test;
                $stmt = $pdo->query("SELECT * FROM competences where competence like '".$test."'");
                //$stmt->bindParam('parametre', $test);
                try{
                    $competences = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    //print_r($competences);
                } catch( PDOException $resultat){
                   exit($resultat->getMessage());
                }
            } else {
                  // 3. Liste des elements
                 $stmt = $pdo->query("SELECT * FROM competences");   
                 $competences = $stmt->fetchAll(PDO::FETCH_ASSOC);

            }

        //insertion des competences
        if(array_key_exists("compet_id",$_GET) && array_key_exists("empl_id", $_GET)){
            $stmt = $pdo->prepare("INSERT INTO employee_competences VALUES(null,?,?)");
            $stmt->bindParam(1,$_GET['empl_id']);
            $stmt->bindParam(2,$_GET['compet_id']);
            try{
                $stmt->execute();
            }catch(PDOException $e){
                exit($e->getMessage());
            }
            $var1=$_GET['empl_id'];
            header("Location:competences.php?empl_id=$var1");
        }

        //fonction de verification existence d' une competence
        if(array_key_exists("empl_id", $_GET)){
            $stmt = $pdo->prepare("SELECT competence_id FROM employee_competences where employe_id = ? ");
            $stmt->bindParam(1,$_GET['empl_id']);
            try{
              $stmt->execute();
            }catch(PDOException $e){
                exit($e->getMessage());
            }
            $reponse = $stmt->fetchAll();
        }
        $verification = true;
            
            
        ?>
       
        <br>
        
        <table border="1" width="100%" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>COMPETENCE</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
               
                <?php foreach ($competences as $c): ?>
                
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= $c['competence'] ?></td>
                        <td>
                            <button><a href="competences.php?delete_id=<?= $c['id']; ?>">Supprimer</a></button>
                            <button><a href="competences.php?modifier_id=<?= $c['id']; ?>&&competence=<?= $c['competence']?> ">Modifier</a></button>
                            <button><a href="recherche-competences.php?affiche_id=<?= $c['id']; ?>">Afficher</a></button>
                            <?php if (array_key_exists("empl_id", $_GET)): ?>
                                 <?php foreach($reponse as $b):?>
                                 
                                     <?php $verification=true;?>
                                    <?php if($b['competence_id'] == $c['id']):?>
                                        <?php $verification=false;?>
                                        <button>Desactiver</button>
                                    <?php endif?>
                                 <?php endforeach?>
                                 <?php if($verification):?>
                                 <button><a href="competences.php?compet_id=<?= $c['id']; ?>&&empl_id=<?= $_GET['empl_id']?>">Ajouter a l'employe</a></button>
                                 <?php endif;?>
                            <?php endif;?>
                           
                         
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
       


    </body>
</html>
