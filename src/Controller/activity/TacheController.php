<?php
	/**
	 * D'abord faire le remplacement puis recopier dans le controller et supprimer ces instructions
     *   __Module__		activity
     *  __section__		tache
     *  __refNode__		30_100
	 *	__nomNode__		tache
	 *  __Entity__		Tache
	 *  __entity__		tache
	 *	__classe__		TacheController
	 *  __template__	tache
	 * 	//modifier  requete sql_data_table_ajax
     */
namespace App\Controller\activity;

use PDO;
use PHPUnit\Exception;
use App\Service\ConfigurationPage;
use App\Service\SystemExpert;
use App\Service\PageUtilities;
use App\Service\LoggingMessage;
use App\Service\RouteurUtilities;
use App\Service\PDOutilities;

use App\Entity\activity\Tache;
use App\Repository\activity\TacheRepository;
use App\Form\activity\TacheType;

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

class TacheController extends AbstractController
{
 
    #[Route('/tache', name: 'activity_tache')]
    public function tache(
            Request $request,
            EntityManagerInterface $entityManager,
            LoggingMessage $logger, 
            RequestStack $requestStack,
            ConfigurationPage $configurationPage,
			TacheType $tacheType,
            SystemExpert $expert
    )
	{
        //30_100_0   0-globale
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='activity';
        $nomNode = 'tache';
		$context_json="[]";

       //30_100_0   1-getter session
        $session = 	$requestStack->getSession();
        $refUser = 	$session->get('refUser');
        $refNode = 	$session->get('nodeCourant');
        $context= 	$session->get('context');
		$crud= 		$session->get('crud'); 
        $lang =  	$session->get('lang');
        $notificationsWarning  =   $session->get('notificationsWarning');
        $notificationsNotice =     $session->get('notificationsNotice');

        //30_100_0 2-Configuration de la page
        $groupesUser = $configurationPage->getSystemeConf('boss_groupe_users', $logger);
        $configNode = $configurationPage->getConfiguration($module,$nomNode,$logger);
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNode.'_'.$lang.'.yaml');

        //30_100_0 3-Crud 0-Read 
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
            
        //30_100_0 4-Crud 1-Select
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

		//30_100_0 5-Boutons outillage
            // Pour chaque commande du tableau on copie la commande si elle est autorise pour refUser
		$cmdeOutilsPage =  $configurationPage->getCmdAllowedByUser($cmdeOutilsPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
        //30_100_0 6-Boutons de cmde 
        //  cmdeConnexPage              (module)
        $cmdeConnexPage =  $configurationPage->getCmdAllowedByUser($cmdeConnexPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
        //  cmdeContextGenePage         (section)
        $cmdeContextGenePage =  $configurationPage->getCmdAllowedByUser($cmdeContextGenePage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);
        //  cmdeContextParticulierPage  (selection)
        $cmdeContextParticulierPage =  $configurationPage->getCmdAllowedByUser($cmdeContextParticulierPage,$refUser,$groupesUser,$txtLang['pageParent'],$logger);

        //30_100_0 10 -Appel Template
               $template = 'activity/tache.html.twig';
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
     *  node  30_100_1 descendant de 30_100_0 page principale de  gestion des tache
     *  permet la reation et la  modification d'un tache
     **/

    #[Route('/tache_form', name: 'activity_tache_form')]
    public function tache_form(
        Request $request,
        RequestStack $requestStack,
        LoggingMessage $logger,
        ConfigurationPage $configurationPage,
		Tache $entity,
		TacheType $tacheType,
		TacheRepository $tacheRepository,
        EntityManagerInterface $entityManager
    )
    {
        // 30_100_1 0-globales
        $contextJson = $request->query->get('context');
        $crud = $request->query->get('crud'); // 1-Creat 0-Read 2-Update 3-Select 4-Delete 5-Metier
        // On recupere le tableau de context
        $context= json_decode($contextJson);
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='activity';
        $nomNode = 'tache_form';
        $nomNodeParent = 'tache';
        $refNode= '30_100_1';

        //30_100_1 1-getter session
        $session = 			$requestStack->getSession();
        $refUser = 			$session->get('refUser');
        $refNodeParent = 	$session->get('nodeCourant');
        $lang =  	        $session->get('lang');
       
        //30_100_1 2-Configuration de la page
        $groupe_users = $configurationPage->getSystemeConf('boss_groupe_users', $logger); 
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNodeParent.'_'.$lang.'.yaml');
        $configNode = $configurationPage->getConfiguration($module,$nomNodeParent,$logger);


        
		//30_100_1 3-Boutons outillage
        $cmdeOutilsPage = $configNode["cmdeOutilsPage"];
        // Pour chaque commande du tableau on copie la commande si elle est autorise pour refUser
        $cmdeOutils =  $configurationPage->getCmdAllowedByUser($cmdeOutilsPage,$refUser,$groupe_users,$txtLang['pageParent'],$logger);

		//30_100_1 4-Titre et commentaires
                //(!Titres et commentaires peuvent faire appel a des fonctions du system expert)
		$titre = $txtLang['pageFormulaire']['txt_trt_1'];
        $configNode["titre"] = $txtLang['pageFormulaire']['txt_trt_1'];
        $configNode["sousTitre"] = $txtLang['pageFormulaire']['txt_trt_2'];
        $configNode["commentaireTitre"] = $txtLang['pageFormulaire']['txt_trt_3'];
        $configNode["commentaire"] = $txtLang['pageFormulaire']['txt_comm_0'];


	//30_100_1 5-Crud 1-Creat
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

	//30_100_1 6-Crud 2-Update
		if($crud == "2"){
			//30_100_1 6-1-Recuperation de l'entite       
			$indexCol = $configNode['indexColumn'];
			// retourne l'identifiant de l'entite
			$id_entity = $configurationPage->getSelectionIdent($context, $indexCol);
			$entity = $configurationPage->getIdEntity($entityManager,'activity\Tache',$id_entity);
		    if(!$entity){
				//30_100_1 6-2-error entity
				$message = $txtLang['message_system']['warning_0_0'].$id_entity;
				$logger->sendDebug($message);
				return $this->render('0-system/erreur.html.twig', ['erreur_system'=>$message]);
			}
		}

	    //30_100_1 7-Definition des listes de choix
        $listesChoix =$configNode['listeChoix'];
        // retourne la table des liste de choix dans la langue
        $listeChoix =  $configurationPage->getListeChoix($listesChoix,$txtLang['pageFormulaire'], $logger); 

	    //30_100_1 8-Creation du formulaire avec ../src/Form/tacheType & createForm
		$tacheType->createTacheType($txtLang, $listeChoix);
        $entityForm = $this->createForm(TacheType::class, $entity);

	    //30_100_1 9-capture event the form has been submitted
		$entityForm->handleRequest($request);
		//---------------------------------------------------------------
	    //30_100_1 7-Enregistrement formulaire
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
			//$params = [$session->get('nodeCourant'),"30_100_0",$context];
            $params = [$refNode,"30_100_0","0",$context];
			$paramsJson=json_encode($params);
			return $this->redirectToRoute('system_routeur',['params'=> $paramsJson ]);
        }
		//---------------------------------------------------------------

        //30_100_1 10-Appel du template formulaire modele_modif
            // modif du npm du template activity/tache_add_modif.html.twig
		return $this->render('activity/tache_form.html.twig', 
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

    #[Route('/tache_metier', name: 'activity_tache_metier')]
    public function tache_metier(
        Request $request,
        RequestStack $requestStack,
        LoggingMessage $logger,
        ConfigurationPage $configurationPage,
		Tache $entity,
		TacheType $tacheType,
		TacheRepository $tacheRepository,
        EntityManagerInterface $entityManager
    )
    {
       // 30_100_2 0-globales
        $contextJson = $request->query->get('context');
        $crud = $request->query->get('crud'); // 1-Creat 0-Read 2-Update 3-Select 4-Delete 5-Metier
        // On recupere le tableau de context
        $context= json_decode($contextJson);
        $notificationsWarning =[];
        $notificationsNotice =[];
        $module='activity';
        $nomNode = 'tache_metier';
        $nomNodeParent = '__section__';
        $refNode= '30_100_2';

        //30_100_2 1-getter session
        $session = 			$requestStack->getSession();
        $refUser = 			$session->get('refUser');
        $refNodeParent = 	$session->get('nodeCourant');
        $lang =  	        $session->get('lang');
        $fichier_log =      $session->get('fichier_log');
    
        //30_100_2 2-Configuration de la page
		$groupe_users = $configurationPage->getSystemeConf('boss_groupe_users', $logger); 
        $txtLang = Yaml::parseFile( '../config/'.$module.'/'.$nomNodeParent.'_'.$lang.'.yaml');
        $configNode = $configurationPage->getConfiguration($module,$nomNodeParent,$logger);
 
		//30_100_2 3-Recuperation de l'entite       
		$indexCol = $configNode['indexColumn'];
		// retourne l'identifiant de l'entite
	//Debug $logger->sendDebug("30_100_2 4-2-Recuperation de l'entite ".$contextJson.", indexCol ".$indexCol);
		$id_entity = $configurationPage->getSelectionIdent($context, $indexCol);
		$entity = $configurationPage->getIdEntity($entityManager,'activity\Tache',$id_entity);
		if(!$entity){
			//30_100_2 4-error entity
			$message = $txtLang['message_system']['warning_0_0'].$id_entity;
			$logger->sendDebug($message);
			return $this->render('0-system/erreur.html.twig', ['erreur_system'=>$message]);
		}

        // 30_100_2 5-Crud 0-Delete
		if($crud == "0"){
			//5-1-Invalide l'enregistrement
			$entity->setValid(0);
			$entityManager->persist($entity); $entityManager->flush();
		}

        // 30_100_2 6-Metier Crud 1-traitement particulier
		if($crud == "1"){
			// Traitement specifique pour le contexte selectionne ( ou pour l'entité)
		}
      //7-Redirection page parent
			$context = [];
			//$params = [$session->get('nodeCourant'),"30_100_0",$context];
            $params = [$refNode,"30_100_0","0",$context];
			$paramsJson=json_encode($params);
			return $this->redirectToRoute('system_routeur',['params'=> $paramsJson ]);
    }


#[Route('/tache_data_table_ajax', name: 'activity_tache_data_table_ajax', methods: ['GET', 'POST'])]
    public function tache_data_table_ajax(
        Request $request,
        LoggingMessage $logger,
        RequestStack $requestStack,
        ConfigurationPage $configurationPage,
        EntityManagerInterface $entityManager,

    )
    {
        //30_100_5 0-globales
        //30_100_5 1-getter session
        $paramsJson = $request->query->get('params');
        $session = 			$requestStack->getSession();
        $refUser = 			$session->get('refUser');
        $refNodeParent = 	$session->get('nodeCourant');
        $lang =  	        $session->get('lang');
        $fichier_log =      $session->get('fichier_log');
//----------------------------------------------------------------
        //30_100_5 0-Construction de la requête avec QueryBuilder (EntityManager)
        // C'est l'équivalent en Doctrine de l'ancienne requête SQL en PDO
		$qb = $entityManager->createQueryBuilder();
		//modifier  requete sql_data_table_ajax
        $qb->select('a')
            ->from('App\Entity\activity\Tache', 'a')
            ->where('a.valid = :valid')
            ->setParameter('valid', 1)     // Filtre sur les enregistrements valides
            ->andWhere('a.--sql_champ IN (:--sql_intervalle--)')
            ->setParameter('--sql_intervalle--', [1, 3])
            ->orderBy('a.nom', 'ASC');     // Tri par nom croissant 

        
        //30_100_5 1-Exécution de la requête et récupération des résultats
        // Remplace le $pdo->select($sql) de l'ancienne version
        $results = $qb->getQuery()->getResult();
        
        //30_100_5 2-Transformation des entités en tableau pour DataTables
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
        
        //30_100_5 3-Retour au format attendu par DataTables: { "data": [...] }
        return new JsonResponse(['data' => $data]);
//----------------------------------------------------------------
 
    }
}