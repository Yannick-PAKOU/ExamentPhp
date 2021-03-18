<!DOCTYPE html>
<html>
<head>
	<title>Recherche-Competences</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

	    <div class="main">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="employes.php">Employes</a></li>
                <li><a href="competences.php">Competences</a></li>
            </ul>
        </div>

         <!--  Formulaire de recherche  
        <form method="post" class="recherche">
            <label>
                Recherche :
                  <input type="text" name="mot">
            </label>
            <button type="submit">OK</button>
        </form>-->

        <?php
            include_once "config.php";
             $recherche = array();
        
        	 if(array_key_exists("affiche_id", $_GET)){
                $test = $_GET["affiche_id"];
                $stmt = $pdo->query("SELECT DISTINCT id,nom,prenoms FROM employe,employee_competences WHERE employee_competences.competence_id = $test AND employe.id = employee_competences.employe_id");
                try{
                    $recherche = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch( PDOException $resultat){
                   exit($resultat->getMessage());
                }

            }

            //echo $_GET['competence'];
        ?>


        
        		 <table border="1" width="100%" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOM</th>
                    <th>PRENOMS</th>
                </tr>
            </thead>
            <tbody>
               
                <?php foreach ($recherche as $cd): ?>
                    <tr>
                        <td><?= $cd['id'] ?></td>
                        <td><?= $cd['nom'] ?></td>
                        <td><?= $cd['prenoms'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        	
        
        

</body>
</html>