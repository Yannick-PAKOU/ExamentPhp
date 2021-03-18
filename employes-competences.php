<!DOCTYPE html>
<html>
<head>
	 <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Employes-Competences</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>


    <div class="main">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="competences.php">Competences</a></li>
                <li><a href="employes.php">Retour</a></li>
            </ul>
    </div>

    <div>Nom : <?php echo $_GET['nom'];?></div>
    <div>Prenoms : <?php echo $_GET['prenoms'];?></div>
    <?php 

        $date_de_naissance = $_GET['dnais'];
        $date = date("Y-m-d");
        $age = date_diff(date_create($date_de_naissance), date_create($date));
        echo "Age : ".$age->format('%y');
    ?>
    
     <?php
        include_once "config.php";
               session_start();
               $_SESSION['emploi'] =  $_GET['prendre_id'];
               $emploi = $_SESSION['emploi'];

         

          // 3. Liste des elements
                 $stmt = $pdo->query("SELECT DISTINCT competence, id FROM competences C, employee_competences EC WHERE C.id = EC.competence_id AND EC.employe_id = $emploi ORDER BY competence");   
              $competences = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  
    ?>

     <table border="1" width="80%" cellspacing="0" cellpadding="5" align="center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>COMPETENCE</th>
                </tr>
            </thead>
            <tbody>
               
                <?php foreach ($competences as $c): ?>

                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= $c['competence'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
</body>
</html>