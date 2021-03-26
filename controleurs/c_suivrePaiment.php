<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */ 

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
case 'suivre_LePaiment':
    $idVisiteur = $_SESSION['idVisiteur'];
    $mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    MoiSelectionne($mois);
    $moisASelectionner = $mois;
    $nomASelectionner = $idVisiteur; 
    $nom = $pdo->getVisiteur();
    $etat="VA";
    $lesMois = $pdo->getLesMois($idVisiteur,$etat); // verification s'ill existe des mois VA pour ce visiteur
    $lesCles = array_keys($lesMois); 
    if(!$lesMois){
         include 'vues/v_listeVisiteur.php';
         echo '<script type="text/javascript">window.alert("Aucune fiche à valider pour ce visiteur  ");</script>';
    }else{  
      $mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
      MoiSelectionne($mois);
  include 'vues/v_mois.php';
    }
    break;
    case"rembourser":
        $idVisiteur = $_SESSION['idVisiteur'];
        $mois=$_SESSION['mois'];
        $moisASelectionner = $mois;
        $nomASelectionner = $idVisiteur; 
        $nom = $pdo->getVisiteur();
        $etat="VA";
        $lesMois = $pdo->getLesMois($idVisiteur,$etat); // verification s'ill existe des mois VA pour ce visiteur
        $lesCles = array_keys($lesMois); 
     try{
    if (isset($_POST['Demander_Remboursement'])) {
             $etat = "MP";
             $pdo-> majEtatFicheFrais($idVisiteur, $mois,$etat); // permet de modifier l'etat d'une fiche
              echo '<script type="text/javascript">window.alert("La fiche a bien été mis dans etat mise en paiement ");</script>';
    } elseif(isset($_POST['Confirmer_Remboursement'])) {
               if ($pdo->testEtat($idVisiteur, $moisASelectionner)) { // si yen a ps
             echo '<script type="text/javascript">window.alert("Avant de mettre la fiche dans l etat rembourser, la mettre en mise en paiment");</script>';
      }else{
           $etat = "RB";
         $pdo-> majEtatFicheFrais($idVisiteur, $mois,$etat); // permet de modifier l'etat d'une fiche
          echo '<script type="text/javascript">window.alert("La fiche est bien remboursée ");</script>';
           } 
      }
   }
catch(Exception $e)
{
	exit('<b>Catched exception at line '. $e->getLine() .' :</b> '. $e->getMessage());
}
 include 'vues/v_mois.php';
    break;

}
      
     $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $moisASelectionner);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $moisASelectionner);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $moisASelectionner);
    $numAnnee = substr($moisASelectionner, 0, 4);
    $numMois = substr($moisASelectionner, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
   require 'vues/v_etatFrais.php';
?>