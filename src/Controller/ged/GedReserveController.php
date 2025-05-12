<?php
namespace App\Controller\ged;

use App\Service\ConfigurationPage;
use App\Service\SystemExpert;
use App\Service\PageUtilities;
use App\Service\LoggingMessage;

use App\Entity\ged\GedReservation;
use App\Entity\ged\GedDocument;
use App\Entity\ged\GrapheLien;

use PDO;
use PHPUnit\Exception;
use Symfony\Component\Yaml\Yaml;
use App\Service\ClientsUtilities;
use App\Service\RouteurUtilities;
use App\Entity\commercial\AnnuaireEntreprise;
use App\Service\PDOutilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\console;

class GedReserveController extends AbstractController
{
    /**
     *  menu_principal GED: v240903-BG
     * 
     */
	#[Route('/ged_menu_principal', name: 'app_ged_menu_principal')]
    public function ged_menu_principal(
        Request $request,
        EntityManagerInterface $entityManager,
        LoggingMessage $logger, 
        RequestStack $requestStack,
        ConfigurationPage $configurationPage,
        SystemExpert $expert
    )
	{
        // 20_100_0 0-globales
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='ged';

       //20_100_0  1-getter session
        $session = 	$requestStack->getSession();
        $refUser = 	$session->get('refUser');
        $refNode = 	$session->get('nodeCourant');
		$nomNode = 'menu_principal';

        $lang =  	                $session->get('lang');
        $notificationsWarning  =    $session->get('notificationsWarning');
        $notificationsNotice =      $session->get('notificationsNotice');

        //20_100_0 2-Configuration de la page
        $groupesUser = $configurationPage->getSystemeConf('boss_groupe_users', $logger);
        $configNode = $configurationPage->getConfiguration($module,$nomNode,$logger);
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNode.'_'.$lang.'.yaml');

        //20_100_0 3-Boutons outillage

        //20_100_0 4-Titres et commentaires

        //20_100_0 5-Boutons de cmde 
        //  5-1-cmdeConnexPage              (module)
        //  5-2-cmdeContextGenePage         (section)
        $cmdeContextGenePage = $configNode["cmdeContextGenePage"];
        $cmdeContextGenePage =  $configurationPage->getCmdAllowedByUser($cmdeContextGenePage,$refUser,$groupesUser,$txtLang['texte_page'],$logger);
        //  5-3-cmdeContextParticulierPage  (selection)
        //20_100_0 6-1-Crud 
        //20_100_0 10-Appel Template
		return $this->render('ged/menu_principal.html.twig', [
            "nodeCourant" => $refNode,
            "configNode" =>  $configNode,
            "txtLang"=> $txtLang,
            "cmdeContextGenePage"=>$cmdeContextGenePage,
            "notificationsWarning" => $notificationsWarning,
            "notificationsNotice" => $notificationsNotice,
            //visCmdeJson: fichier de configuration visualisation des commande en fonction de la ligne selectionnee
        ]);
	}

	/**
	* node  20_110_0 page principale de reservation des documents
	* permet l'ajoute des documents selectionnes dans dossier tampon et enregistre les donnes dans la table  
	* ged_reservation.
	* Ainsi que la suppression des documents reservés
	**/

	#[Route('/ged_reserve_document', name: 'app_ged_reserve_document')]
    public function reserve_documents(
        Request $request,
        EntityManagerInterface $entityManager,
        LoggingMessage $logger, 
        RequestStack $requestStack,
        ConfigurationPage $configurationPage,
        SystemExpert $expert
    )
    {
        //20_110_0 0-globales
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='ged';
        $nomNode = 'reserve_documents';

        //20_110_0  1-getter session
        $session = 	                $requestStack->getSession();
        $refUser = 	                $session->get('refUser');
        $refNode = 	                $session->get('nodeCourant');
        $lang =  	                $session->get('lang');
        $notificationsWarning  =    $session->get('notificationsWarning');
        $notificationsNotice =      $session->get('notificationsNotice');

        //20_110_0 2-Configuration de la page
        $groupesUser = $configurationPage->getSystemeConf('boss_groupe_users', $logger);
        $configNode = $configurationPage->getConfiguration($module,$nomNode,$logger);
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNode.'_'.$lang.'.yaml');

        //20_100_0 3-Boutons outillage


        //20_110_0 4-Titres et commentaires 

       //20_110_0 5-Boutons de cmde 
        //  5-1-cmdeConnexPage              (module)
        //  5-2-cmdeContextGenePage         (section)
        $cmdeContextGenePage = $configNode["cmdeContextGenePage"];
        $cmdeContextGenePage =  $configurationPage->getCmdAllowedByUser($cmdeContextGenePage,$refUser,$groupesUser,$txtLang['texte_page'],$logger);
        //  5-3-cmdeContextParticulierPage  (selection)

        //20_100_0 6-1-Crud 

        //20_110_0 10-Appel Template
        return $this->render('ged/reserve_document.html.twig', [
            "nodeCourant" => $refNode,
            "configNode" =>  $configNode,
            "txtLang"=> $txtLang,
            "cmdeContextGenePage"=>$cmdeContextGenePage,
            "notificationsWarning" => $notificationsWarning,
            "notificationsNotice" => $notificationsNotice,
        ]);
    }
	/**
	* node  20_110_1 descendant de 20_110_0 page principale de reservation des documents
	* Permet l'ajout de documents selectionnes dans dossier tampon et enregistre les donnes dans la table  
	* ged_reservation
	**/

    #[Route('/ged_reserve_add', name: 'app_ged_reserve_add', methods: ['GET', 'POST'])]
    public function reserve_add(
        Request $request,
        LoggingMessage $logger,
        RequestStack $requestStack,
        ConfigurationPage $configurationPage,
        EntityManagerInterface $entityManager,
        PDOutilities $pdo
    )
    {
		//20_110_1 0-globales
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='ged';
		$nomNodeParent = 'reserve_documents';
        $refNode = 	'20_110_1';
        $nomNode = 	'reserve_add'; 

        //20_110_1 1-getter session
        $session = 	$requestStack->getSession();
        $refUser = 	                $session->get('refUser');
        $refNodeParent = 	        $session->get('nodeCourant');
        $lang =  	                $session->get('lang');


        //20_110_1 2-Configuration de la page
        $configNode = $configurationPage->getConfiguration($module,$nomNode,$logger);
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNode.'_'.$lang.'.yaml');
        $configNodeParent = $configurationPage->getConfiguration($module,$nomNodeParent,$logger);
        $txtLangParent = Yaml::parseFile( '../config/'.$module.'/'.$nomNodeParent.'_'.$lang.'.yaml');
        $groupesUser = $configurationPage->getSystemeConf('boss_groupe_users', $logger);
		
		//20_110_1 3-recuperation des fichiers upload 
		if(isset($_FILES)) {
		//debug $logger->sendDebug("isset FILES".json_encode($_FILES)."isset POST".json_encode($_POST));
			$fichiers = $_FILES;
			//3-1-Transformation des tableaux associatifs en tableau indexe
			foreach ($_POST as $val) {$rem[]= $val;}
			$i_count=0;
			foreach ($fichiers as $fichier) {
				//debug $logger->sendDebug(" POST[i_count]".$rem[$i_count]);
				$msgNotice="";$msgWarning="";
				if($fichier['error'] == 0){
					//3-1-1-Le fichier uploade n'a pas d'erreur(extension autorisees, taille)
					$error = $configurationPage->testGedFilesReservation($fichier,$configNode,$txtLang['message_system'],$logger);
					if( $error !== "" ){ $notificationsWarning[]= $error;} 
					else {
						//-1-Le fichier uploade respecte les containtes définies dans configNode
						//  deplacement  du fichier 
						//  recherche reference du document exemple avec PDO
						$sql = "SELECT MAX(gd.id) FROM ged_reservation gd";
						$refDoc = (int)$pdo->selectOneField($sql,'MAX(gd.id)');
						if(empty($refDoc) || is_null($refDoc) || $refDoc ==0) {$refDoc=100;} else{$refDoc++;}

						//-2-Renommage du fichier
						$ancienChemin = $fichier['tmp_name'];
						//$timestamp_actuel = time()
						$nouveauNom =  $refDoc.'-'. $configNode['hebergement'];
						$extension = $configurationPage->getExtensionDoc($fichier['name']);
						//$nouveauChemin = $gisement.$nouveauNom.'.'.$extension;
						$nouveauChemin = $configNode['gisement'].$nouveauNom;
						//-3-Enregistrement de la table ged_reservation avec PDO
						$dateTimeNow = date('d/m/Y H:i:s');
						$titre = $configurationPage->getTitreDoc($fichier['name']);
						$nomFichier = $fichier['name'];
						$status = 0;
						$valid = 1;
						$sql = "INSERT INTO ged_reservation 
									(id, ref_hebergement, gisement, user, dat_res, titre, extension, fichier, status, rem, valid ) 
								VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
						// requête SQL (sécurisée contre les injections SQL)
						$stmt = $pdo->prepare($sql);
						// Liaison des paramètres à la requête
						$stmt->bindParam(1, $refDoc);
						$stmt->bindParam(2, $configNode['hebergement']);
						$stmt->bindParam(3, $configNode['gisement']);
						$stmt->bindParam(4, $refUser);
						$stmt->bindParam(5, $dateTimeNow);
						$stmt->bindParam(6, $titre);
						$stmt->bindParam(7, $extension);
						$stmt->bindParam(8, $nomFichier);
						$stmt->bindParam(9, $status);
						$stmt->bindParam(10, $rem[$i_count]);
						$stmt->bindParam(11, $valid);
						

						//-4-Enregistrement du document dans le tampon 
						if(rename($ancienChemin, $nouveauChemin)) {
							//Le deplacement c'est bien passé on enregistre les donnees
							// Exécution de la requête
							if ($stmt->execute()){$msgNotice = $txtLang['message_system']['notice_0_0']." ".$fichier['name']." ".$txtLang['message_system']['notice_0_1'];} 
							else {$msgWarning = $txtLang['message_system']['warning_3_0']." ".$fichier['name'];}
						}
						else{ $msgWarning = $txtLang['message_system']['warning_2_0']." ".$fichier['name'];}
					}
				}
                //3-1-2- Erreur fichier uploade 
				else{
					switch ($fichier['error']) {
						case '1': $msgWarning = $txtLang['message_system']['warning_4_1']; break;
						case '2': $msgWarning = $txtLang['message_system']['warning_4_2']; break;
						case '3': $msgWarning = $txtLang['message_system']['warning_4_3']; break;
						case '4': $msgWarning = $txtLang['message_system']['warning_4_4']; break;
						case '5': $msgWarning = $txtLang['message_system']['warning_4_5']; break;
						case '6': $msgWarning = $txtLang['message_system']['warning_4_6']; break;
						case '7': $msgWarning = $txtLang['message_system']['warning_4_7']; break;
						case '8': $msgWarning = $txtLang['message_system']['warning_4_8']; break;
						default: $msgWarning = $txtLang['message_system']['warning_4_5'];
					}
				}
				if($msgNotice !==""){$notificationsNotice[]=$msgNotice;}
				else{
					$notificationsWarning[]= $msgWarning;
					$msgWarning .="20_110_1	5-enregistrement du document dans le tampon ";
					$logger->sendDebug("error". $msgWarning);
				}
				$i_count++;
			}
			// 3-2-Appel de la page parent
			$cmdeContextGenePage = $configNodeParent["cmdeContextGenePage"];
			$cmdeContextGenePageParent =  $configurationPage->getCmdAllowedByUser($cmdeContextGenePage,$refUser,$groupesUser,$txtLangParent['texte_page'],$logger);
			return $this->render('ged/reserve_document.html.twig', [
							"nodeCourant" => $refNodeParent,
							'configNode' => $configNodeParent,
							'txtLang' => $txtLangParent,
							"cmdeContextGenePage"=>$cmdeContextGenePageParent,
							"notificationsWarning" => $notificationsWarning,
							"notificationsNotice" => $notificationsNotice,
			]);
		}
	}
	/**
	* node  20_110_2
	* Retourne tous les fichiers en reservation  pour la GED
	**/
	#[Route('/ged_reserve_data_table_ajax', name: 'app_ged_reserve_data_table_ajax', methods: ['GET', 'POST'])]
    public function reserve_data_table_ajax(
        Request $request,
        LoggingMessage $logger,
        RequestStack $requestStack,
        ConfigurationPage $configurationPage,
        EntityManagerInterface $entityManager,
        PDOutilities $pdo
    )
    {
        //20_110_2 0-globales
          $paramsJson = $request->query->get('params');
        // Recuperation du tableau de context
		//debug $logger->sendDebug('20_110_2 0-globales');
        $context = json_decode($paramsJson);
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='ged';
		$refNode = '20_110_2';
        $nomNode = 	'reserve_data_table_ajax';

        //20_110_2 1-getter session
        $session = 	$requestStack->getSession();
        $refUser = 	        $session->get('refUser');
        $refNodeParent = 	$session->get('nodeCourant');
        $lang =  	        $session->get('lang');

        //20_110_2 2-Configuration de la page
        $configNode = $configurationPage->getConfiguration($module,$nomNode,$logger);
        // !jamais utilisé? $txtLang 
    
        //20_110_2 3-Recuperation de l'entite
		// Requete sql retourne le contenu de la table des fichiers reserves
 		//!Parent appellant toujours node 20_110_0
		if($refNodeParent = '20_110_0'){$sql= $configNode['sql_20_110_0'];}
        $responseArray = $pdo->select($sql);
        $dataResponse =  json_encode(array('data'=>$responseArray));

        //20_110_2 4-retour Ajax
        $returnResponse = new JsonResponse();
        $returnResponse->setJson($dataResponse);
        return $returnResponse;    
    }	
	/**
	* node  20_110_3
	* Supprime le document du dossier tampon et supprime les donnes de la table  
	* ged_reservation
	**/

	#[Route('/ged_reserve_supp', name: 'app_ged_reserve_supp')]
	public function reserve_supp(
        Request $request,
        LoggingMessage $logger,
        RequestStack $requestStack,
        ConfigurationPage $configurationPage,
        EntityManagerInterface $entityManager,
        PDOutilities $pdo
    )
	{
		//20_110_3 0-globales
        $paramsJson = $request->query->get('params');
        // On recupere le tableau de context
        $context = json_decode($paramsJson);

        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='ged';
        $refNode = 	'20_110_3';
        $nomNode = 	'reserve_supp';

        //20_110_3 1-getter session
        $session = 	    $requestStack->getSession();
        $refUser = 	        $session->get('refUser');
        $refNodeParent =    $session->get('nodeCourant');
        $lang =  	        $session->get('lang');
    
		//20_110_3 2-Configuration de la page
        $configNode = $configurationPage->getConfiguration($module,$nomNode,$logger);
        $txtLang = Yaml::parseFile( '../config/'.$module.'/txt_'.$nomNode.'_'.$lang.'.yaml');

        $configNodeParent = $configurationPage->getConfiguration($module,"reserve_documents",$logger);
        $txtLangParent = Yaml::parseFile( '../config/'.$module.'/txt_'."reserve_documents".'_'.$lang.'.yaml');
        $groupesUser = $configurationPage->getSystemeConf('boss_groupe_users', $logger);
    
		//Recuperation du contexte parent
		$indexCol = $configNode['indexColumn'];
		// retourne refDoc
        $refDoc = $configurationPage->getSelectionIdent($context, $indexCol);
		$sql_sel = $configNode['sql_sel'].$refDoc;
		$entity= $pdo->selectOneEntity($sql_sel);
 
		//Suppression  du fichier dans le tampon
		$chemin_fichier = $entity['gisement'].$refDoc.'-'.$entity['ref_hebergement'];
		if (unlink($chemin_fichier)) {

			$sql = $configNode['sql_supp'];
			//Exécution de la requête avec les paramètres
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':ref_doc', $refDoc, PDO::PARAM_INT);
			$stmt->execute();
			$notificationsNotice[]= $txtLang['message_system']['notice_0_0'].": ".$refDoc." ".$entity['fichier'].$txtLang['message_system']['notice_0_1'];

		} 
		else { $notificationsWarning[]= $txtLang['message_system']['warning_0_0'];}

		//20_110_3 6-Appel de la page parent
		$cmdeContextGenePage = $configNodeParent["cmdeContextGenePage"];
		$cmdeContextGenePageParent =  $configurationPage->getCmdAllowedByUser($cmdeContextGenePage,$refUser,$groupesUser,$txtLangParent['texte_page'],$logger);
		return $this->render('ged/reserve_document.html.twig', [
						"nodeCourant" => $refNodeParent,
						'configNode' => $configNodeParent,
						'txtLang' => $txtLangParent,
						"cmdeContextGenePage"=>$cmdeContextGenePageParent,
						"notificationsWarning" => $notificationsWarning,
						"notificationsNotice" => $notificationsNotice,
		]);

	}


}