<?php
namespace App\Controller\commercial;

use PDO;
use PHPUnit\Exception;
use App\Service\ConfigurationPage;
use App\Service\SystemExpert;
use App\Service\PageUtilities;
use App\Service\LoggingMessage;
use App\Service\RouteurUtilities;
use App\Service\PDOutilities;

use App\Entity\administratif\AnnuaireEntreprise;
use App\Repository\administratif\AnnuaireEntrepriseRepository;
use App\Form\administratif\AnnuaireEntrepriseType;

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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\console;

class ClientController extends AbstractController
{
    /**
     * Module 1-Commercial 
     * Section 150-clients
     * 
     */
    #[Route('/clients', name: 'commercial_clients')]
    public function clients(
            Request $request,
            EntityManagerInterface $entityManager,
            LoggingMessage $logger, 
            RequestStack $requestStack,
            ConfigurationPage $configurationPage,
			AnnuaireEntrepriseType $annuaireEntrepriseType,
            SystemExpert $expert
    )
	{
        //1_400_0   0-globale
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='commercial';
        $nomNode = 'clients';
		$context_json="[]";

       //1_400_0   1-getter session
        $session = 	$requestStack->getSession();
        $refUser = 	$session->get('refUser');
        $refNode = 	$session->get('nodeCourant');
        $context= 	$session->get('context');
		$crud= 		$session->get('crud'); 
        $lang =  	$session->get('lang');
        $notificationsWarning  =   $session->get('notificationsWarning');
        $notificationsNotice =     $session->get('notificationsNotice');

        //1_400_0 2-Configuration de la page
        $groupesUser = $configurationPage->getSystemeConf('boss_groupe_users', $logger);
        $configNode = $configurationPage->getConfiguration($module,$nomNode,$logger);
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNode.'_'.$lang.'.yaml');

        //1_400_0 3-Crud 0-Read 
		if($crud == "0"){
            //3-1 Boutons outillage creat
				$cmdeOutilsPage = $configNode["cmdeOutilsPage"];

            //3-2 Titre & commentaires creat
				$titre = $txtLang['pageFormulaire']['txt_trt_1'];
				$sousTitre = $configNode["sousTitre"];
				$nodeTitre = $txtLang['pageParent']['txt_1_0'];
				// evaluer par expert en test
				$nodeSousTitre = $txtLang['pageParent']['txt_2_0'];

            //3-3-Boutons de cmde 
                //-cmdeConnexPage              (module)
                $cmdeConnexPage = $configNode["cmdeConnexPage"];
                //cmdeContextGenePage         (section)
                $cmdeContextGenePage = $configNode["cmdeContextGenePage"];
                //cmdeContextParticulierPage  (selection)
                $cmdeContextParticulierPage = $configNode["cmdeContextParticulierPage"];  

                // 	!Si view est vrai, on affiche le menu dans le template	{% if isView %}
                $isView = true;
		}
            
        //1_400_0 4-Crud 1-Select
		if($crud == "1"){
                //4-1 Boutons outillage Select
			
                //4-2 Titre & commentaires Select
                $nodeTitre = $txtLang['pageSelect']['txt_1_0'];

                //4-3-Boutons de cmde Select
                //cmdeConnexPage              (module)
				$cmdeConnexPage = $configNode["cmdeConnexSelect"];
                //cmdeContextGenePage         (section)
				$cmdeContextGenePage=[];
                //cmdeContextParticulierPage  (selection)
                $cmdeContextParticulierPage = $configNode["cmdeContextParticulierSelect"];
                
                // 	!Si view est vrai, on affiche le menu dans le template	{% if isView %} 				
                $isView = false;
		}

		//1_400_0 5-Boutons outillage
            // Pour chaque commande du tableau on copie la commande si elle est autorise pour refUser
		$cmdeOutilsPage =  $configurationPage->getCmdAllowedByUser($cmdeOutilsPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
        //1_400_0 6-Boutons de cmde 
        //  cmdeConnexPage              (module)
        $cmdeConnexPage =  $configurationPage->getCmdAllowedByUser($cmdeConnexPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
        //  cmdeContextGenePage         (section)
        $cmdeContextGenePage =  $configurationPage->getCmdAllowedByUser($cmdeContextGenePage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
        //  cmdeContextParticulierPage  (selection)
        $cmdeContextParticulierPage =  $configurationPage->getCmdAllowedByUser($cmdeContextParticulierPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);

        //1_400_0 10 -Appel Template
               $template = 'commercial/clients.html.twig';
				$options = [
                    
                            "isView"=> $isView,
							"nodeTitre"=> $nodeTitre,
							"nodeCourant"=> $refNode,
							"cmdeOutils"=> $cmdeOutilsPage,
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
     *  node  1_400_1 descendant de 1_400_0 page principale de  gestion des clients
     *  permet la reation et la  modification d'un client
     **/

    #[Route('/client_form', name: 'commercial_client_form')]
    public function client_form(
        Request $request,
        RequestStack $requestStack,
        LoggingMessage $logger,
        ConfigurationPage $configurationPage,
		AnnuaireEntreprise $entity,
		AnnuaireEntrepriseType $annuaireEntrepriseType,
		AnnuaireEntrepriseRepository $annuaireEntrepriseRepository,
        EntityManagerInterface $entityManager
    )
    {
        // 1_400_1 0-globales
        $contextJson = $request->query->get('context');
        $crud = $request->query->get('crud'); // 1-Creat 0-Read 2-Update 3-Select 4-Delete 5-Metier
        // On recupere le tableau de context
        $context= json_decode($contextJson);
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='commercial';
        $nomNode = 'client_form';
        $nomNodeParent = 'clients';
        $refNode= '1_400_1';

        //1_400_1 1-getter session
        $session = 			$requestStack->getSession();
        $refUser = 			$session->get('refUser');
        $refNodeParent = 	$session->get('nodeCourant');
        $lang =  	        $session->get('lang');
       
        //1_400_1 2-Configuration de la page
        $groupe_users = $configurationPage->getSystemeConf('boss_groupe_users', $logger); 
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNodeParent.'_'.$lang.'.yaml');
        $configNode = $configurationPage->getConfiguration($module,$nomNodeParent,$logger);


        
		//1_400_1 3-Boutons outillage
        $cmdeOutilsPage = $configNode["cmdeOutilsPage"];
        // Pour chaque commande du tableau on copie la commande si elle est autorise pour refUser
        $cmdeOutils =  $configurationPage->getCmdAllowedByUser($cmdeOutilsPage,$refUser,$groupe_users,$txtLang['pageParent'],$logger);

		//1_400_1 4-Titre et commentaires
                //(!Titres et commentaires peuvent faire appel a des fonctions du system expert)
		$titre = $txtLang['pageFormulaire']['txt_trt_1'];
        $configNode["titre"] = $txtLang['pageFormulaire']['txt_trt_1'];
        $configNode["sousTitre"] = $txtLang['pageFormulaire']['txt_trt_2'];
        $configNode["commentaireTitre"] = $txtLang['pageFormulaire']['txt_trt_3'];
        $configNode["commentaire"] = $txtLang['pageFormulaire']['txt_comm_0'];


	//1_400_1 5-Crud 1-Creat
		if($crud == "1"){
			//5-1-Creation de l'entite 
			$repository = $entityManager->getRepository('App\Entity\administratif\AnnuaireEntreprise');
			$qb = $repository->createQueryBuilder('u');
			$qb->select('MAX(u.id) as maxId');
			$result = $qb->getQuery()->getOneOrNullResult();
			$idEntity = $result ? $result['maxId'] + 1 : 100;  

				//$idEntity = $entityManager->getRepository('App\Entity\administratif\AnnuaireEntreprise')->getNextAvailableId();
			$entity = new AnnuaireEntreprise();
			$entity->setId($idEntity);
			$entity->setValid(1);
		}

	//1_400_1 6-Crud 2-Update
		if($crud == "2"){
			//1_400_1 6-1-Recuperation de l'entite       
			$indexCol = $configNode['indexColumn'];
			// retourne l'identifiant de l'entite
			$id_entity = $configurationPage->getSelectionIdent($context, $indexCol);
			$entityName = 'administratif\AnnuaireEntreprise';
			$entity = $configurationPage->getIdEntity($entityManager,$entityName,$id_entity);
		    if(!$entity){
				//1_400_1 6-2-error entity
				$message = $txtLang['message_system']['warning_0_0'].$id_entity;
				$logger->sendDebug($message);
				return $this->render('0-system/erreur.html.twig', ['erreur_system'=>$message]);
			}
		}

	    //1_400_1 7-Definition des listes de choix
        $listesChoix =$configNode['listeChoix'];
        // retourne la table des liste de choix dans la langue
        $listeChoix =  $configurationPage->getListeChoix($listesChoix,$txtLang['pageFormulaire'], $logger); 

	    //1_400_1 8-Creation du formulaire avec ../src/Form/annuaireEntrepriseType & createForm
		$annuaireEntrepriseType->createAnnuaireEntrepriseType($txtLang, $listeChoix);
        $entityForm = $this->createForm(AnnuaireEntrepriseType::class, $entity);

	    //1_400_1 9-capture event the form has been submitted
		$entityForm->handleRequest($request);
		//---------------------------------------------------------------
	    //1_400_1 7-Enregistrement formulaire
        if ($entityForm->isSubmitted() && $entityForm->isValid()) {
            //7-1-Hydratation entity enregistrement
            $entity = $entityForm->getData();
            //7-2-Verification des contraintes sur les donnees
            //7-3-Enregistrement des donnees
            try{
                $entityManager->persist($entity); $entityManager->flush();
            }
            catch (Exception $ex){
            //7-4-Erreur enregistrement des donnees
            }
            //7-5-Redirection page parent
			$context = [];
			//$params = [$session->get('nodeCourant'),"1_400_0",$context];
            $params = [$refNode,"1_400_0","0",$context];
			$paramsJson=json_encode($params);
			return $this->redirectToRoute('system_routeur',['params'=> $paramsJson ]);
        }
		//---------------------------------------------------------------

        //1_400_1 10-Appel du template formulaire modele_modif
            // modif du npm du template commercial/client_add_modif.html.twig
		return $this->render('commercial/client_form.html.twig', 
            array('form'=>  $entityForm->createView(),
				'titre' => $titre, 
                'nodeCourant'=>$session->get('nodeCourant'),
                'cmdeOutils'=> $cmdeOutils,
                'configNode'=>$configNode,
                'txtLang_texte_Form' => $txtLang['pageFormulaire'],
                'notificationsWarning'=> $notificationsWarning,
                'notificationsNotice'=> $notificationsNotice,
                'contraintes_champs_json'=> json_encode($configNode["contraintes_champs"]),

            )
        );
    }

    #[Route('/client_metier', name: 'commercial_client_metier')]
    public function client_metier(
        Request $request,
        RequestStack $requestStack,
        LoggingMessage $logger,
        ConfigurationPage $configurationPage,
		AnnuaireEntreprise $entity,
		AnnuaireEntrepriseType $annuaireEntrepriseType,
		AnnuaireEntrepriseRepository $annuaireEntrepriseRepository,
        EntityManagerInterface $entityManager
    )
    {
       // 1_400_2 0-globales
        $contextJson = $request->query->get('context');
        $crud = $request->query->get('crud'); // 1-Creat 0-Read 2-Update 3-Select 4-Delete 5-Metier
        // On recupere le tableau de context
        $context= json_decode($contextJson);
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='commercial';
        $nomNode = 'client_metier';
        $nomNodeParent = 'clients';
        $refNode= '1_400_2';

        //1_400_2 1-getter session
        $session = 			$requestStack->getSession();
        $refUser = 			$session->get('refUser');
        $refNodeParent = 	$session->get('nodeCourant');
        $lang =  	        $session->get('lang');
        $fichier_log =      $session->get('fichier_log');
    
        //1_400_2 2-Configuration de la page
		$groupe_users = $configurationPage->getSystemeConf('boss_groupe_users', $logger); 
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNodeParent.'_'.$lang.'.yaml');
        $configNode = $configurationPage->getConfiguration($module,$nomNodeParent,$logger);
 
		//1_400_2 3-Recuperation de l'entite       
		$indexCol = $configNode['indexColumn'];
		// retourne l'identifiant de l'entite
	//Debug $logger->sendDebug("1_400_2 4-2-Recuperation de l'entite ".$contextJson.", indexCol ".$indexCol);
		$id_entity = $configurationPage->getSelectionIdent($context, $indexCol);
		$entityName = 'administratif\AnnuaireEntreprise';
		$entity = $configurationPage->getIdEntity($entityManager,$entityName,$id_entity);
		if(!$entity){
			//1_400_2 4-error entity
			$message = $txtLang['message_system']['warning_0_0'].$id_entity;
			$logger->sendDebug($message);
			return $this->render('0-system/erreur.html.twig', ['erreur_system'=>$message]);
		}

        // 1_400_2 5-Crud 0-Delete
		if($crud == "0"){
			//5-1-Invalide l'enregistrement
			$entity->setValid(0);
			$entityManager->persist($entity); $entityManager->flush();
            $logger->sendDebug("5-1-Invalide l'enregistrement");
		}

        // 1_400_2 6-Metier Crud 1-traitement particulier
		if($crud == "1"){
			// Traitement specifique pour le contexte selectionne ( ou pour l'entité)
		}
      //7-Redirection page parent
			$context = [];
			//$params = [$session->get('nodeCourant'),"1_400_0",$context];
            $params = [$refNode,"1_400_0","0",$context];
			$paramsJson=json_encode($params);
			return $this->redirectToRoute('system_routeur',['params'=> $paramsJson ]);
    }


#[Route('/clients_data_table_ajax', name: 'commercial_clients_data_table_ajax', methods: ['GET', 'POST'])]
    public function clients_data_table_ajax(
        Request $request,
        LoggingMessage $logger,
        RequestStack $requestStack,
        ConfigurationPage $configurationPage,
        EntityManagerInterface $entityManager,

    )
    {
        //1_400_5 0-globales
        //1_400_5 1-getter session
        $paramsJson = $request->query->get('params');
        $session = 			$requestStack->getSession();
        $refUser = 			$session->get('refUser');
        $refNodeParent = 	$session->get('nodeCourant');
        $lang =  	        $session->get('lang');
        $fichier_log =      $session->get('fichier_log');
//----------------------------------------------------------------
        //1_400_5 0-Construction de la requête avec QueryBuilder (EntityManager)
        // C'est l'équivalent en Doctrine de l'ancienne requête SQL en PDO
		$qb = $entityManager->createQueryBuilder();
        $qb->select('a')
            ->from('App\Entity\administratif\AnnuaireEntreprise', 'a')
            ->where('a.valid = :valid')
            ->setParameter('valid', 1)     // Filtre sur les enregistrements valides
            ->andWhere('a.clientFourniss IN (:isClient)')
            ->setParameter('isClient', [1, 3])
            ->orderBy('a.nom', 'ASC');     // Tri par nom croissant 

        
        //1_400_5 1-Exécution de la requête et récupération des résultats
        // Remplace le $pdo->select($sql) de l'ancienne version
        $results = $qb->getQuery()->getResult();
        
        //1_400_5 2-Transformation des entités en tableau pour DataTables
        // Les index numériques (0,1,2...) correspondent aux colonnes de la DataTable
        $data = []; $i_count=0;

        foreach ($results as $entity) {
            $data[] = [
                0 => $entity->getId(),                    // Colonne 1 : Référence
                1 => $entity->getNom(),                   // Colonne 2 : Nom
                2 => $entity->getAddPostale(),            // Colonne 3 : Adresse postale
                3 => $entity->getVille(),                 // Colonne 4 : Ville
                4 => $entity->getPays(),                  // Colonne 5 : Pays
                5 => $entity->getSiteInternet(),          // Colonne 6 : Site internet
                6 => $entity->getUidVal(),                // Colonne 7 : NIU
                7 => $entity->getActivite(),              // Colonne 8 : Importance
                8 => $entity->getRem()                    // Colonne 9 : Remarques
            ];
        }
        
        //1_400_5 3-Retour au format attendu par DataTables: { "data": [...] }
        return new JsonResponse(['data' => $data]);
//----------------------------------------------------------------
 
    }
}