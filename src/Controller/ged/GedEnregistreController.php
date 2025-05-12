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
use App\Form\ged\EnregistreDocumentType;
use App\Form\ged\EnregistreDocumentFormType;
use App\Repository\ged\GedDocumentRepository;

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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


    /**
     * Module 20-Ged  
     * Section 120-GedEnregistreController
     * 20_120_0  page principale
	 *   permet de selectionner un document par le user habiliter
	 * et soit de le supprimer soit de l'enregistrer dans la base
	 * l'enregistrement se déroule en 4 etapes
	 * 	1-enregistrement du formulaire lie au document pour son identification et ses parametres de gestion
	 *  2-mise a jour de la table reservation
	 *  3-Chiffrage du document
	 *  4-Enregistrement du fichier document dans un hebergement
     */

class GedEnregistreController extends AbstractController
{
    /**
     *  Node  20_120_0 page principale enregistrement des documents reserves
	 *  Crud 0-Read
     *   Permet l'affichage des documents en attente d'enregistrement 
	 *   et de poouvoir soit le supprimer soit l'enregistrer 
	 *  Crud 3-Select
	 *   permet de selectionner un document depuis un autre module
     **/
    private $entityForm;
    private $dossiers;
    private $listeChoixGisement=[];
    private $listeChoixDossiers= [];



    #[Route('/enregistre_document', name: 'app_ged_enregistre_document')]
    public function enregistre_document(
            Request $request,
            EntityManagerInterface $entityManager,
            LoggingMessage $logger, 
            RequestStack $requestStack,
            ConfigurationPage $configurationPage,
            SystemExpert $expert
    )
	{
        //20_120_0   0-globale
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='ged';
		$nomNode = 'enregistre_document';
		$context_json="[]";

       //20_120_0   1-getter session
        $session = 	$requestStack->getSession();
        $refUser = 	        $session->get('refUser');
        $refNode = 	        $session->get('nodeCourant');
        $context= 	        $session->get('context');
		$crud= 		        $session->get('crud'); // 1-Creat 0-Read 3-Select
        $lang =  	        $session->get('lang');
//Debug
$logger->sendDebug("20_120_0   1-getter session crud = ".$crud);
		
        $notificationsWarning  =   $session->get('notificationsWarning');
        $notificationsNotice =     $session->get('notificationsNotice');
  
        //20_120_0 2-Configuration de la page
        $groupesUser = $configurationPage->getSystemeConf('boss_groupe_users', $logger);
        $configNode = $configurationPage->getConfiguration($module,$nomNode,$logger);
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNode.'_'.$lang.'.yaml');

        //20_120_0 4 Elements du menu
            
        //20_120_2 6-1-Crud 1-Config Page generale
		if($crud == "0"){
            //6-1-1 Boutons Page generale 
				$cmdeConnexPage = $configNode["cmdeConnexPage"];
				$cmdeContextGenePage = $configNode["cmdeContextGenePage"];
				$cmdeContextParticulierPage = $configNode["cmdeContextParticulierPage"];
            //6-1-2 Titre Page generale     
				$nodeTitre = $txtLang['pageParent']['txt_1_0'];
				// evaluer par expert en test
				$nodeSousTitre = $txtLang['pageParent']['txt_2_0'];
               $isView = true;
		}
            
        //20_120_2 6-2-Crud 3-Config Page select
		if($crud == "3"){
            //6-2-1 Boutons Page select 
				$cmdeConnexPage = $configNode["cmdeConnexSelect"];
				$cmdeContextGenePage=[];
				$cmdeContextParticulierPage = $configNode["cmdeContextParticulierSelect"];
            //6-2-2 Titre Page select 
                $nodeTitre = $txtLang['pageSelect']['txt_1_0'];
                $isView = false;
		}

        //20_120_0 8 Boutons de cmde droits user
        //  8-1-cmdeConnexPage              (module)
        $cmdeConnexPage =  $configurationPage->getCmdAllowedByUser($cmdeConnexPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
        //  8-2-cmdeContextGenePage         (section)
        $cmdeContextGenePage =  $configurationPage->getCmdAllowedByUser($cmdeContextGenePage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
        //  8-3-cmdeContextParticulierPage  (selection)
        $cmdeContextParticulierPage =  $configurationPage->getCmdAllowedByUser($cmdeContextParticulierPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);

        //20_120_0 10 -Appel Template
               $template = 'ged/enregistre_document.html.twig';
				$options = [
                            "isView"=> $isView,
							"nodeTitre"=> $nodeTitre,
							"nodeCourant"=> $refNode,
							"cmdeConnex"=> $cmdeConnexPage,
							"cmdeContextGene"=>$cmdeContextGenePage,
							"cmdeContextParticulier" => $cmdeContextParticulierPage,
							"configNode" =>  $configNode,
							"txtLang"=> $txtLang,
							'notificationsWarning'=>$notificationsWarning,
							'notificationsNotice'=>$notificationsNotice,
				];

		return $this->render($template, $options);
	}

    /**
     *  Node  20_120_1 enregistre_document_form 
 	 *  	------------------------------------------------------------
 	 *  Parametres du document
 	 *  Champs du formulaire
 	 *  	1-titre				Titre du document dans la page de recherche
     *  	2-gisement			Emplacement de stockage du fichier
     *  	3-dossier			reference du dossier de rangement
 	 *  	4-motsCles			Liste de mots cles de recherche
	 *  	5-rem
     * 
 	 *  	6-extension			recopiee depuis le fichier reservation
 	 *  	7-version			date heure de l'enregistrement
 	 *  Champs du formulaire remplis automatiquement  	
 	 *  	1-id				Index enregistrement
 	 *  	3-userRes  		    Reference de l'utilisateur ayant reserve le document
 	 *  	4-dateRes			Date de reservation
 	 *  	5-userVal			Reference de l'utilisateur ayant enregistre le document
 	 *  	6-dateVal			Date d' enregistrement
     *  	6-extension			recopiee depuis le fichier reservation
 	 *  	7-version			date heure de l'enregistrement
  	 *  	16-valid

     *  	!  * voir ../config/system/boss_dossiers_fr.yaml

 	 *  	------------------------------------------------------------
 	 *  Crud 1-Creat
     *   Permet l'enregistrement de nouveaux document 
	 *  Crud 2-Update
	 *   permet la modification des parametres du document
     **/


    #[Route('/enregistre_document_form', name: 'app_ged_enregistre_form')]
    public function enregistre_document_form(
        Request $request,
        RequestStack $requestStack,
        LoggingMessage $logger,
        ConfigurationPage $configurationPage,
		GedDocument $gedDocument,
        GedUtilities $gedUtilities,
        EntityManagerInterface $entityManager,
		PDOutilities $pdo
    )
    {
        
        // 20_120_1 0-globales
        $individu = $request->query->get('context');
        $contextJson = $individu;
        $context = json_decode($contextJson);
        //Debug
        $logger->sendDebug("20_120_1 0-globales context = ".$contextJson);

        $notificationsWarning =[];
        $notificationsNotice =[];

        $module='ged';
        $nomNode = 'enregistre_document_form';
        $nomNodeParent = 'enregistre_document';
        $refNode= '20_120_1';

        //20_120_1 1-getter session
        $session = 			$requestStack->getSession();
        $refUser = 			$session->get('refUser');
        $lang =  	        $session->get('lang');
        $crud =  	        $session->get('crud');
        $logger->sendDebug("20_120_1 0-globales crud = ".$crud);

        //20_120_1 2-Configuration de la page
			//Debug 
            $logger->sendDebug("20_120_1 2-Configuration de la page");
    
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNodeParent.'_'.$lang.'.yaml');
        $logger->sendDebug("20_120_1 2-Configuration de la page 1-txtLang = ".$nomNodeParent);
        
        $configNode = $configurationPage->getConfiguration($module,$nomNodeParent,$logger);
        $logger->sendDebug("20_120_1 2-Configuration de la page 2-Appli");
        
        $groupe_users = $configurationPage->getSystemeConf('boss_groupe_users', $logger); 
        $logger->sendDebug("20_120_1 2-Configuration de la page 3-groupe_users  ");

        $this->dossiers = Yaml::parseFile( '../config/'.'system/boss_dossiers'.'_'.$lang.'.yaml');
        $logger->sendDebug("20_120_1 2-Configuration de la page 4-boss_dossiers  ");

        $gisement = Yaml::parseFile( '../config/'.'system/boss_gisement.yaml');
        $logger->sendDebug("20_120_1 2-Configuration de la page 5-boss_gisement  ");

        $indexCol = $configNode['indexColumn'];
        //Config titres et commentaires (traduction)
        $configNode["titre"] = $txtLang['pageFormulaire']['txt_trt_1'];
        $configNode["sousTitre"] = $txtLang['pageFormulaire']['txt_trt_2'];
        $configNode["commentaireTitre"] = $txtLang['pageFormulaire']['txt_comm_0'];
        $configNode["commentaire"] = $txtLang['pageFormulaire']['txt_comm_1'];


		//20_120_1 3-Boutons outillage
            // Pour chaque commande du tableau on copie la commande si elle est autorise pour refUser
			// Non implemente

		//20_120_1 4-Titre et commentaires
		$titre = $txtLang['pageFormulaire']['txt_trt_1'];

        //20_120_1 6-1-Crud 1-Creat GedDocument
		if($crud == "1"){
            //Debug
            $logger->sendDebug("20_120_1 Crud 1-Creat");
            //6-1-1-recherche pdo-sql de l'index suivant de l'entite ged_document 
			$sql = "SELECT MAX(gd.id) FROM ged_document gd";
			$id_gedDocument = (int)$pdo->selectOneField($sql,'MAX(gd.id)')+1;

            //6-1-2-sauvegarde de l'index de l'entite ged_document dans la session
            $session->set('id_gedDocument_enreg', $id_gedDocument);
			//Debug 
            $logger->sendDebug("20_120_1 6-1-Crud 1-Creation de l'entite GedDocument idEntity= ".$session->get('id_entity'));
            //6-1-3-creation de l'entite ged_document
            $gedDocument = new GedDocument();
			$gedDocument->setId($id_gedDocument);
			//6-1-4-recuperation de l'entite selectionnee  gedReservation
            $id_gedReservation=$configurationPage->getSelectionIdent($context,$indexCol);
            //6-1-5-sauvegarde de l'index de l'entite gedReservation dans la session
            $session->set('id_gedReservation', $id_gedReservation);
			//6-1-6-getIdEntity retourne l'enregistrement du document selectionne
			$gedReservation = $configurationPage->getIdEntity($entityManager,'ged\GedReservation',$id_gedReservation);

            
            $old_gisement_file = $gedReservation->getGisement(); //../public/Ged/fichiersReserves/
            $old_hebergement_file = $gedReservation->getRefHebergement();//100
            $old_id_file = $gedReservation->getId();//101
            $old_path_file =  $old_gisement_file.$old_id_file.'-'.$old_hebergement_file;
            $session->set('old_path_file',$old_path_file);

            //6-1-7-Recuperation des valeurs de gedReservation transferee dans gedDocument
			$gedDocument->setUserRes($gedReservation->getUser());
			$gedDocument->setDateRes($gedReservation->getDatRes());
			$gedDocument->setTitre($gedReservation->getTitre());
			$gedDocument->setExtension($gedReservation->getExtension());
			$gedDocument->setRem($gedReservation->getRem());

			$gedDocument->setUserVal($refUser);
			$now = (string)date('d/m/Y H:i:s');
			$gedDocument->setDateVal($now);
            //6-1-8-sauvegarde de gedDocument
            $gedDocument->setValid(0);
			$entityManager->persist($gedDocument);
			$entityManager->flush();
		}

        //20_120_1 6-2-Crud 2-Update

		if($crud == "2"){
			//6-2-1-Recuperation de l'entite gedDocument      
			$indexCol = $configNode['indexColumn'];
			//6-2-2-retourne l'identifiant de l'entite id_gedDocument
			$id_gedDocument = $configurationPage->getSelectionIdent($context, $indexCol);
            //6-2-3-sauvegarde de l'index de l'entite id_gedDocument dans la session
			//6-2-4-Recupere l'entite id_gedDocument
			$gedDocument = $configurationPage->getIdEntity($entityManager,'ged\GedDocument',$id_gedDocument);
		    if(!$gedDocument){
				//6-2-5-error entity
				$message = $txtLang['message_system']['warning_0_0'].$id_gedDocument;
				$logger->sendDebug($message);
				return $this->render('0-system/erreur.html.twig', ['erreur_system'=>$message]);
			}
		}
        //20_120_1 6-3-Crud 1 || 2   
		if($crud == "1" ||$crud == "2" ){
		    //6-3-1-initialise listeChoixDossiers
            $logger->sendDebug(" Crud 1-Creat ou 2-Update");
            $this->listeChoixDossiers = ['Sélectionner une option' => '*'];
            foreach ($this->dossiers as $key => $value ){
                //Trouver tous les modules
                //Calcul de la generation du dossier
                $dossiers_generation = explode('_',$key);
                if (strpos($key, 'module') !== false){
                    $this->listeChoixDossiers[$value] = $key ;
                }
            }
            //6-3-2-initialise listeChoixGisement
            $i_count =0;
            foreach ($gisement as $key => $value ){
                $this->listeChoixGisement[$value['nom']] = $key;
                $i_count++;
            }
            //6-3-3-definition du formulaire entityForm
            $this->entityForm = $this->createFormBuilder($gedDocument)
            ->add('titre',TextType::class, ['required'=> true])
            ->add('gisement',ChoiceType::class, ['required'=> true, 'choices' => $this->listeChoixGisement])
            ->add('dossier',ChoiceType::class,  ['required'=> true, 'choices' => $this->listeChoixDossiers])
            ->add('mots_cles',TextareaType::class,['required'=> false]) 
            ->add('rem',TextareaType::class, ['required'=> false]) 
            ->getForm();
        }
        //20_120_1 6-4-Crud 3-recherche dossier down 4-recherche dossier up
		if($crud == "3" ||$crud == "4" ){
           //6-4-1-Dans ce cas le contexte 'individu' contient la reference du parent refParent
            $refParent =  $individu;
            $tGenerationParent = explode('_',$refParent);
            $generationParent =count( $tGenerationParent)-1;
            $offset =  strlen($refParent) - strlen($tGenerationParent[0]);
            //6-4-2-Recherche du suffix parent
            $suffixParent = substr($refParent, -$offset);
            //6-4-3-On recherhe les enfants direct du parent, dans la table $dossier
            //generation du parent
            $tGenerationParent = explode('_',$refParent);
            $generationParent =count( $tGenerationParent)-1;
            $suffixOptions = '';
            foreach ($this->dossiers as $key => $value ){
                //6-4-4-Crud 3-recherche dossier down
                if($crud == "3" ){
                    $logger->sendDebug("----------------------------------------------------------------------------------------------");
                    $logger->sendDebug("  key = ".$key);
                    //6-4-4-1-recherche de la generation enfant
                    $tGenerationEnfant = explode('_',$key);
                    $generationEnfant =count( $tGenerationEnfant)-1;
                    $suffixOptions = $suffixParent;

                    $isDossier = ( trim($tGenerationEnfant[0]) !== 'module') && $key !== 'cle_chiffrage' ?true:false;
                    $isDescendant = strpos($key,$suffixParent) !== false;
                    $isEnfant = ($generationEnfant - $generationParent)== 1;

                    //6-4-4-2-si enfant dossier direct, le dossier fait parti de la liste de choix
                    if($isDossier && $isDescendant && $isEnfant){ 
                        $this->listeChoixDossiers[$key] = $value['nom'] ;
                        $logger->sendDebug(" isdossier enfant key =".$key." nom = ". $value['nom']);
                    }
                }
                //6-4-5--Crud 4-recherche dossier up
                if($crud == "4" ){
                    //6-4-5-1-On recherche les antecedents du parent sous-dossier_*1_4_1: a pour entecedant suffixRacine = xxx_*1_4
                    $tGenerationKey = explode('_',$key);
                    $generationKey =count( $tGenerationKey)-1;
                    $prefixKey = $tGenerationKey[0];

                    $offset =  strlen($key) - strlen($tGenerationKey[0]);
                    $suffixKey = substr($key, -$offset);

                    //6-4-5-2-On calcule  suffixRacine a partir de key, Retourne le segment de string défini par offset et length.
                    $lenKey = strlen($key); 
                    $offset = strlen($prefixKey); 
                    //6-4-5-3-On lit le dernier element du tableau tGenerationKey
                    $lenDernierElementKey =strlen(end($tGenerationKey));
                    $length = $lenKey -$offset - $lenDernierElementKey;
                    $suffixRacine = substr( $key, $offset, $length);    

                    $isDossier = ( trim($tGenerationKey[0]) !== 'module') && $key !== 'cle_chiffrage' ?true:false;
                    $isModule =  (trim($tGenerationKey[0]) !== 'dossier') && $key !== 'cle_chiffrage' ?true:false;
                    $isAscendant = strpos($suffixParent,$suffixRacine) !== false;
                    $isAntecedent = ($generationParent - $generationKey)== 1;
                 
                    //6-4-5-4-si antecedent dossier direct le dossier fait parti de la liste de choix
                    if($isDossier && $isAscendant && $isAntecedent){ 
                        $this->listeChoixDossiers[$key] = $value['nom'] ;
                        $logger->sendDebug(" isdossier antecedent key =".$key);
                    }
                    if($isModule && $isAscendant && $isAntecedent){ 
                        $this->listeChoixDossiers[$key] = $value ;
                        $logger->sendDebug(" isModule antecedent key =".$key);
                    }
				}
            }    
            //6-4-6-Response Ajax
            $logger->sendDebug("Response Ajax dossier enfant!");
            // $dossier_enfant = $this->listeChoixDossiers['Interventions exterieures']?"isDefine":"isNotDefine";
            $optionsDossier=[];
            if (!empty($this->listeChoixDossiers)){
                $optionsDossier = ['' => 'Selectionner une option'] + $this->listeChoixDossiers;
            }
            return new Response(json_encode( $optionsDossier));  
        }

        //20_120_1 6-5-Crud 5-Submit
		if($crud == "5"){ 
            //6-5-1-recuperation de l'entity gedDocument
            $logger->sendDebug("20_120_1 6-5-Crud 5-Submit_1");            
            $id_gedDocument=$session->get('id_gedDocument_enreg');
            $logger->sendDebug("20_120_1 6-5-Crud 5-Submit_2 id_entity = ". $id_gedDocument);  

			$gedDocument = $configurationPage->getIdEntity($entityManager,'ged\GedDocument',$id_gedDocument);
            $logger->sendDebug("20_120_1 6-5-Crud 5-Submit_3 UserRes= ".$gedDocument->getUserRes());  
		    
            if(!$gedDocument){
				$message = $txtLang['message_system']['warning_0_0'].$id_gedDocument;
				$logger->sendDebug($message);
				return $this->render('0-system/erreur.html.twig', ['erreur_system'=>$message]);
			}
            //6-5-2-hydratation de l'entity
            $logger->sendDebug("6-5-2-enregistrement l'entity");  
            // 'titre',
            $gedDocument->setTitre($context[0]);
            $logger->sendDebug("6-5-2-enregistrement l'entity titre = ".$context[0]); 
            // 'gisement',
            $gedDocument->setGisement($context[1]);
            $logger->sendDebug("6-5-2-enregistrement l'entity gisement = ".$context[1]); 
            // 'dossier' ,
            $gedDocument->setDossier($context[2]);
            $logger->sendDebug("6-5-2-enregistrement l'entity dossier = ".$context[2]); 
            // 'mots_cles',
            $gedDocument->setMotsCles($context[3]);
            $logger->sendDebug("6-5-2-enregistrement l'entity mots_cles = ".$context[3]); 
            // 'rem' 
            $gedDocument->setRem($context[4]);
            $logger->sendDebug("6-5-2-enregistrement l'entity rem = ".$context[4]); 

            //6-5-3-recup parametre de cryptage && enregistrement
            $nomFichierDoc=  $id_gedDocument.'-'.$context[1].'.enc';	
            $logger->sendDebug("6-5-2-enregistrement l'entity nomFichierDoc = ".$nomFichierDoc); 
            $serveur = $gisement[$context[1]]['serveur'];
            $pathFichierDoc=  $gisement[$context[1]]['chemin'];
            $outputFilePath =$pathFichierDoc.$nomFichierDoc;    //Chemin d enregistrement du fichier document chiffre
            $logger->sendDebug("6-5-2-enregistrement l'entity outputFilePath = ".$outputFilePath); 
            $serveur = $gisement[$context[1]]['serveur'];
            $cleChiffrage= $gisement[$context[1]]['cle_chiffrage'];
            $oldPathFile=$session->get('old_path_file');
            $logger->sendDebug("6-5-3-enregistrement fichier document dans son gisement = ".$oldPathFile);
        
            //6-5-4 cryptage fichier document  
            if($serveur == "localhost"){      
                if ($gedUtilities->encryptFile($oldPathFile, $cleChiffrage, $outputFilePath)) {
                    $logger->sendDebug("6-5-4 cryptage fichier document");
                    //5-4-1-Valide les parametre du document dans la table gedDocument
                    $gedDocument->setValid(1);
                    $entityManager->persist($gedDocument);
                    $entityManager->flush();
                    //5-4-2-Supprime le fichier du dossier oldPathFile
                    unlink($oldPathFile);
                    //5-4-3-Invalide le document enregistre dans GedReservation
                    $id_gedReservation=$session->get('id_gedReservation');
                    $logger->sendDebug("5-4-3-Invalide le document enregistre dans GedReservation id_gedReservation= ".$id_gedReservation);
                    $gedReservation = $configurationPage->getIdEntity($entityManager,'ged\GedReservation',$id_gedReservation);
                    if($gedReservation){ 
                        $logger->sendDebug("5-4-3-Invalide le document enregistre dans GedReservation Debut OK"); 
                        $gedReservation->setStatus(1); 
                        $gedReservation->setValid(0); 
                        $entityManager->persist($gedReservation);
                        $entityManager->flush();
                        $logger->sendDebug("5-4-3-Invalide le document enregistre dans GedReservation Fin OK"); 
                    }
                    else{$logger->sendDebug("5-4-3-Invalide le document enregistre dans GedReservation Echec gedReservation  ");}
                    $logger->sendDebug("5-4-3-Invalide le document enregistre dans GedReservation Fin");
                }
                else{
                    //5-4-4-Message Erreur de cryptagedu fichier
                    $msgWarning = $txtLang['message_system']['warning_3_0']." ".$outputFilePath;
                    $logger->sendDebug($msgWarning);            
                }
            } 
            else {
                    //6-5-5--Serveur distant connextion SSH                    
                    $logger->sendDebug("6-5-4-3-Serveur distant connextion SSH7");  
            }
          
            //6-5-6-Response Ajax
            $logger->sendDebug("6-5-6-Response Ajax");
             $responseAjax=[];
            return new Response(json_encode( $responseAjax));  
        }    
        //7-Redirection page parent
        //20_120_1 10-Appel du template formulaire
		return $this->render('ged/enregistre_document_form.html.twig', 
            array(
                'form' =>  $this->entityForm->createView(), // C'est cette ligne qui est cruciale !->createView(), // C'est cette ligne qui est cruciale !
				'titre' => $titre, 
                'nodeCourant'=>$session->get('nodeCourant'),
                'cmdeOutils'=> [],
                'configNode'=>$configNode,
                'txtLang_texte_Form' => $txtLang['pageFormulaire'],
                'notificationsWarning'=> $notificationsWarning,
                'notificationsNotice'=> $notificationsNotice,
				'contraintes_champs_json'=> "",

            )
        );
    }


    /**
     *  node  20_120_2 descendant de 20_120_0 page principale de  gestion des ged_documents
     *  permet d'invalider le document ou tout autre action ne necessitant pas un affichage (action en backend)
     **/

    #[Route('/enregistre_document_metier', name: 'ged_enregistre_document_metier')]
    public function enregistre_document_metier(
        Request $request,
        RequestStack $requestStack,
        LoggingMessage $logger,
        ConfigurationPage $configurationPage,
		GedDocument $entity,
		GedDocumentRepository $gedDocumentRepository,
        EntityManagerInterface $entityManager
    )
    {
       // 20_120_2 0-globales
        $contextJson = $request->query->get('context');
        $crud = $request->query->get('crud'); 
 
        // On recupere le tableau de context
        $context= json_decode($contextJson);
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='commercial';
        $nomNode = 'ged_document_metier';
        $nomNodeParent = 'enregistre_document';
        $refNode= '20_120_2';

        //20_120_2 1-getter session
        $session = 			$requestStack->getSession();
        $refUser = 			$session->get('refUser');
        $refNodeParent = 	$session->get('nodeCourant');
        $lang =  	        $session->get('lang');
    
        //20_120_2 2-Configuration de la page
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNodeParent.'_'.$lang.'.yaml');
        $configNode = $configurationPage->getConfiguration($module,$nomNodeParent,$logger);
         // Recuperation des droits && routing nodes
        $groupe_users = $configurationPage->getSystemeConf('boss_groupe_users', $logger); 

		//20_120_2 4-2-Recuperation de l'entite       
		$indexCol = $configNode['indexColumn'];
		// retourne l'identifiant de l'entite
	//Debug $logger->sendDebug("20_120_2 4-2-Recuperation de l'entite ".$contextJson.", indexCol ".$indexCol);
		$id_entity = $configurationPage->getSelectionIdent($context, $indexCol);

		$gedDocument = $configurationPage->getIdEntity($entityManager,'ged\GedDocument',$id_entity);
		if(!$gedDocument){
			//20_120_2 5-error entity
			$message = $txtLang['message_system']['warning_0_0'].$id_entity;
			$logger->sendDebug($message);
			return $this->render('0-system/erreur.html.twig', ['erreur_system'=>$message]);
		}

        // Crud 5-Delete
		if($crud == "5"){
			$gedDocument->setValid(0);
			$entityManager->persist($gedDocument); $entityManager->flush();
		}

        // Crud 6-Metier
		if($crud == "6"){
			// Traitement specifique pour le contexte selectionne ( ou pour l'entité)
		}
      //9-5-Redirection page parent
			$context = [];
			//$params = [$session->get('nodeCourant'),"20_120_0",$context];
            $params = [$refNode,"20_120_0",$context];
			$paramsJson=json_encode($params);
			return $this->redirectToRoute('system_routeur',['params'=> $paramsJson ]);
    }

    /**
     *  node  20_120_3 appele par de 20_120_0 page principale de  gestion des ged_documents
     *  permet la mise a jour de la dataTable avec les documents en attente d'enregistrement dans la GED
     **/

#[Route('/enregistre_data_table_ajax', name: 'app_ged_enregistre_data_table_ajax', methods: ['GET', 'POST'])]
    public function enregistre_data_table_ajax(
        Request $request,
        LoggingMessage $logger,
        RequestStack $requestStack,
        ConfigurationPage $configurationPage,
        EntityManagerInterface $entityManager,
        PDOutilities $pdo
    )
    {
        //20_120_3 0-globales
        $paramsJson = $request->query->get('params');
        // On recupere le tableau de context
        $context = json_decode($paramsJson);
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='ged';
		$refNode = '20_120_3';
		$nomNode = 'enregistre_data_table_ajax';
        $nomNodeParent = 'enregistre_document';
		$data =[];
        
		//20_120_3 1-getter session
        $session = 	$requestStack->getSession();
        $refUser = 	        $session->get('refUser');
        $refNodeParent = 	$session->get('nodeCourant');
        $lang =  	        $session->get('lang');
        //Debug         $logger->sendDebug("20_120_3 0-globales");
        
        //20_120_3 2-Configuration de la page
        $configNode = $configurationPage->getConfiguration($module,$nomNodeParent,$logger);
        //jamais utilisé? $txtLang 

        //20_120_3 3-Recuperation de l'entite
		// Requete sql retourne le contenu de la table des fichiers mis en reserve
        $responseArray = $pdo->select("SELECT * FROM ged_reservation  WHERE valid = 1");
        $logger->sendDebug("20_120_3 3-Recuperation de l'entite");
        $dataResponse =  json_encode(array('data'=>$responseArray));
       //20_120_3 4-retour Ajax
        $returnResponse = new JsonResponse();
        $returnResponse->setJson($dataResponse);
        return $returnResponse; 
    }
//----------------------------------------------------------------
 
    
}