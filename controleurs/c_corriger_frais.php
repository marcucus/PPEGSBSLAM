<?php
 
 $idVisiteur = $_SESSION['idVisiteur'];
 $mois=$_SESSION['mois'];
 MoiSelectionne($mois);
$moisASelectionner = $mois;
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

switch ($action) {
    case'afficherFrais' :
        $mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $nom = $pdo->getVisiteur(); // pour l'affichage
        $nomASelectionner = $idVisiteur;      // pour que quand la page se recharge l'utilisateur seletionner est mis par defaut 
        $_SESSION['mois'] = $mois;
        $moisASelectionner=$mois;
    break;

    case 'validerMajFraisForfaitt':  
      $nom = $pdo->getVisiteur(); // pour l'affichage
      $nomASelectionner = $idVisiteur;      // pour que quand la page se recharge l'utilisateur seletionner est mis par defaut 
      $_SESSION['mois'] = $mois;
      $moisASelectionner=$mois;
      $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
       if (lesQteFraisValides($lesFrais)) { //verifie que lesFrais contienne que des valeurs numerique  RETOURNE VRAI OU FAUX EN FONCTION 
        $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais); //Met à jour la table ligneFraisForfait pour le visiteur , le mois avec les frais saisis
        } else {
           ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
  
    break;
    
    case'horsforfait':
      $nom = $pdo->getVisiteur(); // pour l'affichage
      $nomASelectionner = $idVisiteur;      // pour que quand la page se recharge l'utilisateur seletionner est mis par defaut 
      $_SESSION['mois'] = $mois;
      $moisASelectionner=$mois;
      try
        {
          $date = filter_input(INPUT_POST, 'lesFraisD', FILTER_DEFAULT, FILTER_FORCE_ARRAY); 
         $libelle = filter_input(INPUT_POST, 'lesFraisL', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
         $montant= filter_input(INPUT_POST, 'lesFraisM', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
         $idFrais = filter_input(INPUT_POST, 'FraisHorsForfait', FILTER_DEFAULT , FILTER_FORCE_ARRAY); // recuperation des ID, nous avons ici tous les id

         //$pdo->SupprimerFrais($idVisiteur, $mois,$libelle,$idFrais);
           //$pdo->majFraisForfaitHdd($idVisiteur, $mois , $idFrais,$libelle,$montant,$date);
        if (isset($_POST['corriger'])) {
  
  $pdo->SupprimerFrais($idVisiteur, $mois,$libelle,$idFrais);
 // $pdo->majFraisForfaitHdd($idVisiteur, $mois , $idFrais,$libelle,$montant,$date);
} elseif (isset($_POST['reporter'])) {
 
    $moisASelectionner=$moisASelectionner+1;
   if ($pdo->estPremierFraisMois($idVisiteur, $moisASelectionner)) { // si yen a ps
      $pdo->creeNouvellesLignesFrais($idVisiteur, $moisASelectionner);//il creer la fiche de frais avec comme valeure 0 pour ts les montants mais il a crer la fiche avec le mois l
    } 
   $pdo->creeFraisHorsForfait($idVisiteur,$moisASelectionner,$libelle,$date,$montant,$idFrais); // creer une nouvelle fiche dans le mois suivant
   $moisASelectionner=$moisASelectionner-1;
  $pdo->supprimerLeFraisHorsForfait($idFrais,$moisASelectionner); //supprime la fiche du mois initiale
  
}    
}
catch(Exception $e)
{
	exit('<b>Catched exception at line '. $e->getLine() .' :</b> '. $e->getMessage());
}

break;

    case 'valider_frais': // valide la fiche de frais d'un visiteur
     $nom = $pdo->getVisiteur(); // pour l'affichage
     $nomASelectionner = $idVisiteur; 
     $_SESSION['mois'] = $mois;
    $moisASelectionner=$mois;
    $nbJustificatifs=filter_input(INPUT_POST, 'nbJustificatifs', FILTER_SANITIZE_STRING );
              try{ 
               $pdo->majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs)  ; // prend en compte le nombre de justificatifs
               $etat = "VA";
              $pdo-> majEtatFicheFrais($idVisiteur, $mois,$etat);      // met la fiche a l'etat valider
              }catch(Exception $e)
              {
                	exit('<b>Catched exception at line '. $e->getLine() .' :</b> '. $e->getMessage());
              }
               echo '<script type="text/javascript">window.alert("La fiche a bien été validé,vous pouvez choisir une autre fiche ");</script>';
     
break;
}
$etat="CL";
$lesMois = $pdo->getLesMois($idVisiteur,$etat);
require 'vues/v_mois.php'; 
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $moisASelectionner);
require 'vues/v_listeFraisForfait.php';
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $moisASelectionner);
require 'vues/v_fraisHorsForfait.php';
