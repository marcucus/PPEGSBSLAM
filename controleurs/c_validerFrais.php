  <?php

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
case 'listeVisiteurs':
    $nom = $pdo->getVisiteur();// pour afficher la liste des visiteurs
        $idVisiteur = filter_input(INPUT_POST, 'visit', FILTER_SANITIZE_STRING); // recupere le user
   VisiteurSelectionne($idVisiteur);
    $nom = $pdo->getVisiteur(); // pour l'affichage
    $nomASelectionner = $idVisiteur;      // pour que quand la page se recharge user sélectionné est mis par defaut 
  include 'vues/v_listeVisiteur.php';
        break;
     
case 'listeMois': // lorsqu'il a choisit l'utilisateur
   $idVisiteur = filter_input(INPUT_POST, 'visit', FILTER_SANITIZE_STRING); // recupere le user
   VisiteurSelectionne($idVisiteur);
   $nomASelectionner = $idVisiteur; 
   $nom = $pdo->getVisiteur(); // pour l'affichage 
   $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
        if($uc=='validerFrais'|| $uc=='corriger_frais'){
           $etat="CL";
   }else{
        $etat="VA";
   }
   $lesMois = $pdo->getLesMois($idVisiteur,$etat);// Afin de sélectionner par défaut le dernier mois dans la zone liste
    $lesCles = array_keys($lesMois); 
    if(!$lesMois){
         include 'vues/v_listeVisiteur.php';
          if($uc=='validerFrais'|| $uc=='corriger_frais'){
         echo '<script type="text/javascript">window.alert("Aucune fiche à valider pour ce visiteur ");</script>';
          }else{
              echo '<script type="text/javascript">window.alert("Aucune fiche à rembourser pour ce visiteur ");</script>';
          }
}else{  
$mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
MoiSelectionne($mois);
$moisASelectionner = $mois;
include 'vues/v_mois.php';
}
 break;
}
?>
