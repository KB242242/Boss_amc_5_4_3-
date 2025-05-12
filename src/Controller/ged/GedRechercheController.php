<?php
namespace App\Controller\ged;

use PDO;
use PHPUnit\Exception;

use App\Service\PDOutilities;
use App\Service\SystemExpert;
use App\Service\PageUtilities;
use App\Service\LoggingMessage;
use App\Service\ClientsUtilities;
use App\Service\RouteurUtilities;
use App\Service\ConfigurationPage;
use App\Service\GedUtilities;

use App\Entity\ged\GedReservation;
use App\Entity\ged\GedDocument;
use App\Form\EnregistreDocumentType;
use App\Form\EnregistreDocumentFormType;
use App\Repository\GedDocumentRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\DependencyInjection\Loader\Configurator\console;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


    /**
     * Module 20-Ged  
     */

class GedRechercheController extends AbstractController
{

    private $entityForm;
    private $dossiers;

    /**
     *  Node  20_130_0 page principale 
     **/

    #[Route('/recherche_document', name: 'app_ged_recherche_document')]
    public function recherche_document(
            Request $request,
            EntityManagerInterface $entityManager,
            LoggingMessage $logger, 
            RequestStack $requestStack,
            ConfigurationPage $configurationPage,
            GedUtilities $gedUtilities,
            SystemExpert $expert
    )
	{
        $individu = $request->query->get('context');
        $contextJson = $individu;
        //20_130_0   0-globale
        $notificationsWarning =[];
        $notificationsNotice =[];
        $listeChoixDossiers=[] ;
        $listeChoixModule =   ['Selectionner une option' => ''];
        $option_1="";

        $module='ged';
		$nomNode = 'recherche_document';
		$context_json="[]";

       //20_130_0   1-getter session
        $session = 	$requestStack->getSession();
        $refUser = 	        $session->get('refUser');
        $refNode = 	        $session->get('nodeCourant');
        $context= 	        $session->get('context');
		$crud= 		        $session->get('crud'); 
        $lang =  	        $session->get('lang');
        //Debug
        $logger->sendDebug("20_130_0   1-getter session crud = ".$crud);
		
        $notificationsWarning  =   $session->get('notificationsWarning');
        $notificationsNotice =     $session->get('notificationsNotice');
  
        //20_130_0 2-Configuration de la page
        $groupesUser = $configurationPage->getSystemeConf('boss_groupe_users', $logger);
        $gisement = Yaml::parseFile( '../config/system/boss_gisement.yaml');

        $configNode = $configurationPage->getConfiguration($module,$nomNode,$logger);
        $logger->sendDebug("20_130_0 2-Configuration de la page titre =".$configNode['titre']);

        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNode.'_'.$lang.'.yaml');
        $logger->sendDebug("20_130_0 2-Configuration de la page lgn = 94");
        $dossiers = Yaml::parseFile( '../config/'.'system/boss_dossiers'.'_'.$lang.'.yaml');
        $logger->sendDebug("20_130_0 2-Configuration de la page lgn = 96");
           
        //20_130_0 6-1-Crud 0-Config Page generale
		if($crud == "0"){
            $logger->sendDebug("20_130_0 6-1-Crud 1-Config Page generale");

            //6-1-1 Boutons Page generale 
				$cmdeConnexPage = $configNode["cmdeConnexPage"];
                $cmdeConnexPage =  $configurationPage->getCmdAllowedByUser($cmdeConnexPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
				$cmdeContextGenePage = $configNode["cmdeContextGenePage"];
                $cmdeContextGenePage =  $configurationPage->getCmdAllowedByUser($cmdeContextGenePage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
				$cmdeContextParticulierPage = $configNode["cmdeContextParticulierPage"];
                $cmdeContextParticulierPage =  $configurationPage->getCmdAllowedByUser($cmdeContextParticulierPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);

                //6-1-2 Titre Page generale     
				$nodeTitre = $txtLang['pageParent']['txt_1_0'];
				// evaluer par expert en test
				$nodeSousTitre = $txtLang['pageParent']['txt_2_0'];

           //6-1-3 Config listeChoixModule
             $icount = 0; 
            foreach ($dossiers as $key => $value ){
                //Trouver tous les modules
                //Calcul de la generation du dossier
                $dossiers_generation = explode('_',$key);
                if (strpos($key, 'module') !== false){
                    $listeChoixModule[$value] = $key ;
                    $logger->sendDebug(" icount = ".$icount." key = ".$key);
                    if($icount==0){
                        $option_1= $key;
                        $logger->sendDebug(" icount_select= ".$icount." key_select = ".$key);
                    }
                     $icount++;

                }
            }

            //6-1-5-definition du formulaire entityForm
            $this->entityForm = $this->createFormBuilder()

            ->add('module',ChoiceType::class, ['required'=> true, 'choices' => $listeChoixModule])
            ->add('dossier',ChoiceType::class,  ['required'=> true, 'choices' => $listeChoixDossiers])
             ->getForm();
            
		}
        //20_130_0 6-1-Crud 1-Retour edit
        if($crud == "1"){ 
            //6-1-Recupere le module selectionne
            $moduleGedPresel = $session->get('moduleGed');
            $dossierGedPresel = $session->get('dossierGed');
            $logger->sendDebug("20_130_0 6-1-Crud 1-Retour edit moduleGedPresel = ".$moduleGedPresel.", dossierGedPresel = ".$dossierGedPresel);

            //6-1-1 Boutons Page generale 
				$cmdeConnexPage = $configNode["cmdeConnexPage"];
                $cmdeConnexPage =  $configurationPage->getCmdAllowedByUser($cmdeConnexPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
				$cmdeContextGenePage = $configNode["cmdeContextGenePage"];
                $cmdeContextGenePage =  $configurationPage->getCmdAllowedByUser($cmdeContextGenePage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
				$cmdeContextParticulierPage = $configNode["cmdeContextParticulierPage"];
                $cmdeContextParticulierPage =  $configurationPage->getCmdAllowedByUser($cmdeContextParticulierPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);

                //6-1-2 Titre Page generale     
				$nodeTitre = $txtLang['pageParent']['txt_1_0'];
				// evaluer par expert en test
				$nodeSousTitre = $txtLang['pageParent']['txt_2_0'];

           //6-1-3 Config listeChoixModule
             $icount = 0; 
            foreach ($dossiers as $key => $value ){
                //Trouver tous les modules
                //Calcul de la generation du dossier
                $dossiers_generation = explode('_',$key);
                if (strpos($key, 'module') !== false){
                    $listeChoixModule[$value] = $key ;
                    $logger->sendDebug(" icount = ".$icount." key = ".$key);
                    if($icount==0){
                        $option_1= $key;
                        $logger->sendDebug(" icount_select= ".$icount." key_select = ".$key);
                    }
                     $icount++;

                }
            }
            //6-1-3 Config listeChoixDossier
            // on recherche tous les dossiers freres de dossierGedPresel
             //6-1-4-Dans ce cas le contexte 'individu' contient la reference du parent refParent
             //Exemple: les dossier (dossier_*1_4_3 & dossier_*1_4_4 ) sont freres 
             // ils sont de la 3 generation, ils ont les mm parent
             // il faut donc retrouver le parent de $dossierGedPresel
             $logger->sendDebug("6-1-3 Config listeChoixDossier dossierGedPresel = ".$dossierGedPresel);
    //         $refParent = $dossierGedPresel;
             $tGenerationDossierPresel = explode('_',$dossierGedPresel);
             $generationDossierPresel =count( $tGenerationDossierPresel)-1;
             $logger->sendDebug("6-1-3 Config listeChoixDossier generationDossierPresel = ".$generationDossierPresel);
             $offset =  strlen($dossierGedPresel) - strlen($tGenerationDossierPresel[0]);
             $logger->sendDebug("6-1-3 Config listeChoixDossier offset = ".$offset);

             //6-1-5-Recherche du suffix parent
             $suffixParent = substr($dossierGedPresel, -$offset,-2);
             $logger->sendDebug("6-1-3 Config listeChoixDossier suffixParent = ".$suffixParent);

             //6-1-6-On recherhe les freres direct du parent, dans la table $dossier
            foreach ($dossiers as $key => $value ){
                //6-1-recherche de la generation frere
                $tGeneration = explode('_',$key);
                $generation =count( $tGeneration)-1;
                $isDossier = ( trim($tGenerationDossierPresel[0]) !== 'module')  ?true:false;
                $isDescendant = strpos($key,$suffixParent) !== false;
                $isFrere = ($generationDossierPresel - $generation)== 0;
//Debug
$isDossier_ = $isDossier?"isDossier":"isNotDossier";
$isDescendant_ = $isDescendant?"isDescendant":"isNotDescendant";
$isFrere_ = $isFrere?"isFrere":"isNotFrere";
$logger->sendDebug(" isdossier_  =".$isDossier_." isDescendant_ = ". $isDescendant_." isFrere_ = ". $isFrere_);
                //6-2-si enfant dossier direct, le dossier fait parti de la liste de choix
                if($isDossier && $isDescendant && $isFrere){ 
                    $listeChoixDossiers[$value['nom']] = $key ;
                    $logger->sendDebug(" isdossier frere key =".$key." nom = ". $value['nom']);
                }
            }
            //-----------------------------------------------------------------------
            //6-1-7-definition du formulaire entityForm
            $this->entityForm = $this->createFormBuilder(['module' => $moduleGedPresel, 'dossier' => $dossierGedPresel])

            ->add('module',ChoiceType::class, ['required'=> true, 'choices' => $listeChoixModule])
            ->add('dossier',ChoiceType::class,  ['required'=> true, 'choices' => $listeChoixDossiers])
             ->getForm();
            
        }      
        //20_130_0 6-2-Crud 2-Recherche dossier enfant du choix module   
        if($crud == "2"){
            //6-2-1 Config $listeChoixDossiers
            $refParent = $individu;
            // Enregistre dans session le module selectionne
            $session->set('moduleGed',$refParent);
            $logger->sendDebug("20_130_0 6-2-Crud 2 session->set moduleGed = ".$refParent);

            $tGenerationParent = explode('_',$refParent);
            $generationParent =count( $tGenerationParent)-1;
            $offset =  strlen($refParent) - strlen($tGenerationParent[0]);
            //6-2-2-Recherche du suffix parent
            $suffixParent = substr($refParent, -$offset);
            //6-2-3-On recherhe les enfants direct du parent, dans la table $dossier
            //generation du parent
            $tGenerationParent = explode('_',$refParent);
            $generationParent =count( $tGenerationParent)-1;
            $suffixOptions = '';

            foreach ($dossiers as $key => $value ){
                //recherche dossier down
                $logger->sendDebug("  key = ".$key);
                //recherche de la generation enfant
                $tGenerationEnfant = explode('_',$key);
                $generationEnfant =count( $tGenerationEnfant)-1;
                $suffixOptions = $suffixParent;

                $isDossier = ( trim($tGenerationEnfant[0]) !== 'module') && $key !== 'cle_chiffrage' ?true:false;
                $isDescendant = strpos($key,$suffixParent) !== false;
                $isEnfant = ($generationEnfant - $generationParent)== 1;

                //si enfant dossier direct, le dossier fait parti de la liste de choix
                if($isDossier && $isDescendant && $isEnfant){ 
                    $listeChoixDossiers[ $key] = $value['nom'] ;
                    $logger->sendDebug(" isdossier enfant key =".$key." nom = ". $value['nom']);
                }
            }
            //6-2-4-Response Ajax
            $logger->sendDebug("Response Ajax dossier enfant!");
            // $dossier_enfant = $listeChoixDossiers['Interventions exterieures']?"isDefine":"isNotDefine";
            $optionsDossier=[];
            if (!empty($listeChoixDossiers)){
                $optionsDossier = ['' => 'Selectionner une option'] + $listeChoixDossiers;
            }
            return new Response(json_encode( $optionsDossier));  
        }

        //20_130_0 6-3-Crud 3-Recherche dossier down 4-recherche dossier up
		if($crud == "3" ||$crud == "4" ){
            //6-3-1-Dans ce cas le contexte 'individu' contient la reference du parent refParent
             $refParent =  $individu;
             $tGenerationParent = explode('_',$refParent);
             $generationParent =count( $tGenerationParent)-1;
             $offset =  strlen($refParent) - strlen($tGenerationParent[0]);
             //6-3-2-Recherche du suffix parent
             $suffixParent = substr($refParent, -$offset);
             //6-3-3-On recherhe les enfants direct du parent, dans la table $dossier
             //generation du parent
             $tGenerationParent = explode('_',$refParent);
             $generationParent =count( $tGenerationParent)-1;
             $suffixOptions = '';
             foreach ($this->dossiers as $key => $value ){
                 //6-3-4-Crud 3-recherche dossier down
                 if($crud == "3" ){
                     $logger->sendDebug("----------------------------------------------------------------------------------------------");
                     $logger->sendDebug("  key = ".$key);
                     //6-3-4-1-recherche de la generation enfant
                     $tGenerationEnfant = explode('_',$key);
                     $generationEnfant =count( $tGenerationEnfant)-1;
                     $suffixOptions = $suffixParent;
 
                     $isDossier = ( trim($tGenerationEnfant[0]) !== 'module') && $key !== 'cle_chiffrage' ?true:false;
                     $isDescendant = strpos($key,$suffixParent) !== false;
                     $isEnfant = ($generationEnfant - $generationParent)== 1;
 
                     //6-3-4-2-si enfant dossier direct, le dossier fait parti de la liste de choix
                     if($isDossier && $isDescendant && $isEnfant){ 
                         $listeChoixDossiers[$key] = $value['nom'] ;
                         $logger->sendDebug(" isdossier enfant key =".$key." nom = ". $value['nom']);
                     }
                 }
                 //6-3-5--Crud 4-Recherche dossier up
                 if($crud == "4" ){
                     //6-3-5-1-On recherche les antecedents du parent sous-dossier_*1_4_1: a pour entecedant suffixRacine = xxx_*1_4
                     $tGenerationKey = explode('_',$key);
                     $generationKey =count( $tGenerationKey)-1;
                     $prefixKey = $tGenerationKey[0];
 
                     $offset =  strlen($key) - strlen($tGenerationKey[0]);
                     $suffixKey = substr($key, -$offset);
 
                     //6-3-5-2-On calcule  suffixRacine a partir de key, Retourne le segment de string défini par offset et length.
                     $lenKey = strlen($key); 
                     $offset = strlen($prefixKey); 
                     //6-3-5-3-On lit le dernier element du tableau tGenerationKey
                     $lenDernierElementKey =strlen(end($tGenerationKey));
                     $length = $lenKey -$offset - $lenDernierElementKey;
                     $suffixRacine = substr( $key, $offset, $length);    
 
                     $isDossier = ( trim($tGenerationKey[0]) !== 'module') && $key !== 'cle_chiffrage' ?true:false;
                     $isModule =  (trim($tGenerationKey[0]) !== 'dossier') && $key !== 'cle_chiffrage' ?true:false;
                     $isAscendant = strpos($suffixParent,$suffixRacine) !== false;
                     $isAntecedent = ($generationParent - $generationKey)== 1;
                  
                     //6-3-5-4-si antecedent dossier direct le dossier fait parti de la liste de choix
                     if($isDossier && $isAscendant && $isAntecedent){ 
                         $listeChoixDossiers[$key] = $value['nom'] ;
                         $logger->sendDebug(" isdossier antecedent key =".$key);
                     }
                     if($isModule && $isAscendant && $isAntecedent){ 
                         $listeChoixDossiers[$key] = $value ;
                         $logger->sendDebug(" isModule antecedent key =".$key);
                    }
                }
            }    
             //6-3-6-Response Ajax
             $logger->sendDebug("Response Ajax dossier enfant!");
             // $dossier_enfant = $listeChoixDossiers['Interventions exterieures']?"isDefine":"isNotDefine";
             $optionsDossier=[];
             if (!empty($listeChoixDossiers)){
                 $optionsDossier = ['' => 'Selectionner une option'] + $listeChoixDossiers;
             }
             return new Response(json_encode( $optionsDossier));  
        }
        //20_130_0 6-4-Crud 5-Recherche des document du dossier selectionne
        if($crud == "5" ){
            //6-4-0-cmdeContextParticulierPage  (selection)
            $cmdeContextGene =  $configurationPage->getCmdAllowedByUser($configNode["cmdeEditPage"],$refUser,$groupesUser,$txtLang['pageParent'],$logger);
            $context = json_decode($contextJson);            
           // $dossier = $individu;
            // 6-4-1-Recherche du document dans la table GedDocument     
			$indexCol = $configNode['indexColumn'];
$logger->sendDebug("6-4-1-Recherche du document dans la table GedDocument  indexCol = ".$indexCol." context = ".$context[0][0]);
			//6-4-2-retourne l'identifiant de l'entite id_gedDocument

			$id_gedDocument = $configurationPage->getSelectionIdent($context, $indexCol);
$logger->sendDebug("20_130_0 6-4-Crud 5-recherche des document du dossier selectionne id_gedDocument = ".$id_gedDocument);		  
  
            //6-4-3-sauvegarde de l'index de l'entite id_gedDocument dans la session
            $session->set('id_gedDocument_recherch', $id_gedDocument);
			//6-4-4-Recupere l'entite gedDocument
			$gedDocument = $configurationPage->getIdEntity($entityManager,'ged\GedDocument',$id_gedDocument);
            $refGisement = $gedDocument->getGisement();
            $nomFicCrypted = $gedDocument->getId().'-'.$refGisement.'.enc';
            $filePath = $gisement[$refGisement]['chemin'].$nomFicCrypted;
            $password = $gisement[$refGisement]['cle_chiffrage']; 
            //6-4-5-Titre de iFrame = titre du document
            $txtLang['pageParent']['txt_2_0'] =	$gedDocument->getTitre();
            //6-4-6-Decodage du fichier
            $plaintext =$gedUtilities->decryptFile($filePath, $password);
            if ($plaintext === false) {
                //6-4-7-Erreur decodage du fichier
                $logger->sendDebug("6-4-6-Erreur decodage du fichier");
                return new Response('Erreur lors du déchiffrement du fichier.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $response = new Response($plaintext);

  
         //6-4-8-Créer une data URI
         //text/plain est le type MIME par défaut pour les fichiers texte
         $extension = 'txt';
         $mimeType = $extension == 'txt'? 'text/plain':'application/octet-stream';
         $dataUri = 'data:' . $mimeType . ';base64,' . base64_encode($plaintext);

         //6-4-9-Appel Template
         return $this->render('ged/edit_document.html.twig', [
            "nodeCourant"=> $refNode,
            "cmdeConnex"=> [],
            "cmdeContextGene"=>$cmdeContextGene,
            "cmdeContextParticulier" => [],
            "timeOutPage" =>  $configNode['timeOutPage'],
            "txtLang"=> $txtLang,
            'txtLang_texte_Form' => $txtLang['pageFormulaire'],
            'notificationsWarning'=>$notificationsWarning,
            'notificationsNotice'=>$notificationsNotice,
             'iframeSrc' => $dataUri,
         ]);
        }
        //20_130_0 6-5-Crud 6-Telechargement du fichier pour ayant droit
        if($crud == "6" ){
            //6-5-0-Configuration 
            $id_gedDocument = $session->get('id_gedDocument_recherch');
            $gedDocument = $configurationPage->getIdEntity($entityManager,'ged\GedDocument',$id_gedDocument);
            $refGisement = $gedDocument->getGisement();
            $nomFicCrypted = $gedDocument->getId().'-'.$refGisement.'.enc';
            $filePath = $gisement[$refGisement]['chemin'].$nomFicCrypted;
            $password = $gisement[$refGisement]['cle_chiffrage'];           
            
            //6-5-1-Decodage du fichier
            $plaintext =$gedUtilities->decryptFile($filePath, $password);           
            //6-5-2-Créer un fichier temporaire
            $nomFichierTemporaire = tempnam(sys_get_temp_dir(), 'decrypted_');
            file_put_contents($nomFichierTemporaire, $plaintext);

            //6-5-3-Crée une réponse de téléchargement de fichier
            $response = new BinaryFileResponse($nomFichierTemporaire);
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'fichier_decrypte.txt' // Nom du fichier téléchargé
            );

            //6-5-4-Supprime le fichier temporaire après le téléchargement
            $response->deleteFileAfterSend(true);

            return $response;
        }
        //20_130_0 10 -Appel Template
            $template = 'ged/recherche_document.html.twig';
            $options = [
                        'form' =>  $this->entityForm->createView(),
                        "nodeCourant"=> $refNode,
                        "cmdeConnex"=> $cmdeConnexPage,
                        "cmdeContextGene"=>$cmdeContextGenePage,
                        "cmdeContextParticulier" => $cmdeContextParticulierPage,
                        "configNode" =>  $configNode,
                        "txtLang"=> $txtLang,
                        'txtLang_texte_Form' => $txtLang['pageFormulaire'],
                        'notificationsWarning'=>$notificationsWarning,
                        'notificationsNotice'=>$notificationsNotice,
            ];

		return $this->render($template, $options);
    }

#[Route('/recherche_data_table_ajax', name: 'app_ged_recherche_data_table_ajax', methods: ['GET', 'POST'])]
    public function enregistre_data_table_ajax(
        Request $request,
        LoggingMessage $logger,
        RequestStack $requestStack,
        ConfigurationPage $configurationPage,
        EntityManagerInterface $entityManager,
        PDOutilities $pdo
    )
    {
        $logger->sendDebug("20_130_3 enregistre_data_table_ajax");
        //20_130_3 0-globales
        $individu = $request->query->get('context');
        $contextJson = $individu;

        $paramsJson = $request->query->get('params');
        //20_130_3 1-On recupere le tableau de context qui contient la reference de la section
        $context = json_decode($paramsJson);
        $notificationsWarning =[];
        $notificationsNotice =[];


        $module='ged';
		$refNode = '20_130_3';
		$nomNode = 'enregistre_data_table_ajax';
        $nomNodeParent = 'enregistre_document';
        //Declaration de la table de retour
		$data =[];
        
		//20_130_3 1-getter session
        $session = 	$requestStack->getSession();
        $refUser = 	        $session->get('refUser');
        $refNodeParent = 	$session->get('nodeCourant');
        $lang =  	        $session->get('lang');
        //Debug         $logger->sendDebug("20_130_3 0-globales");
        //20_130_3 2-Configuration de la page
        $configNode = $configurationPage->getConfiguration($module,$nomNodeParent,$logger);
        //jamais utilisé? $txtLang 

        //20_130_3 3-Recuperation de l'entite
		// Requete sql retourne le contenu de la table des fichiers mis en reserve
        $dossier = $individu;
        $session->set('dossierGed',$dossier);
        $logger->sendDebug("20_130_0 6-2-Crud 2 session->set dossierGed = ".$dossier);
         $responseArray = $pdo->select("SELECT id,titre,rem,mots_cles FROM ged_document  WHERE dossier = '".$dossier."' AND valid = 1");
        $logger->sendDebug("20_130_3 3-Recuperation de l'entite");
        $dataResponse =  json_encode(array('data'=>$responseArray));
       //20_130_3 4-retour Ajax
        $returnResponse = new JsonResponse();
    $logger->sendDebug("20_130_3 returnResponse = ".$returnResponse);

        $returnResponse->setJson($dataResponse);
        return $returnResponse; 
    }
}