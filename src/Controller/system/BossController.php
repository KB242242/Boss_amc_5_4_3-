<?php 
namespace App\Controller\system;


use App\Service\LoggingMessage;
use App\Service\ConfigurationPage;
use App\Service\SystemUtilities;
use App\Service\PDOutilities;

use App\Entity\system\User;
use App\Repository\UserRepository;

use PHPUnit\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;

class BossController extends AbstractController
{
    /**
     * Module de gestion du systeme
     * Fonction de connexion appele par l'adresse
     *  IP: https://127.0.0.1:8000/boss_connexion?lang='fr'
     */

    #[Route('/', name: 'system_connexion')]
    public function connexion( 
        Request $request,
        EntityManagerInterface $entityManager,
        LoggingMessage $logger,
        User $user, 
        ConfigurationPage $configurationPage,
        RequestStack $requestStack,
        PDOutilities $pdo )
    {
        // 0_100_1 0-Recuperation config connexion https://127.0.0.1:8000/boss_connexion?lang=fr
        $lang = $request->getLanguages();
        $lang = $lang[0];
        $configNode = $configurationPage->getSystemeConf('boss_connexion', $logger);
        //$configNode = '../config/system/boss_connexion.php';
        $txtLang = Yaml::parseFile( '../config/system/connexion_'.$lang.'.yaml');
        //--------------------------------
        // 0_100_1 1-globales
        $notificationsWarning =[];
        $notificationsNotice =[];
        $retErrorPage = false;
        $cas = 0;
        $nbErrConnAllowed = $configNode['max_tentative_connexion']; 
        $nbErrRestantes = 0;
        $dateConn = 0;
        $err = false;
        //--------------------------------
        // 0_100_1 2-Déclaration du formulaire de connexion
        $user = new user();
        $userForm = $this->createFormBuilder($user)
            ->add('pseudo',TextType::class, array('required'=> false))
            ->add('pwd',PasswordType::class, array('required'=> false))
            ->add('retContext',CheckboxType::class, array('required'=> false))
            //envoie formulaire
            ->getForm();
        //--------------------------------
        // 0_100_1 3-retour formulaire
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // 0_100_1 3-1 Recuperation des données du formulaire
            $pseudoForm =        $userForm->getData()->getPseudo();
            $pwdForm =           $userForm->getData()->getPwd();
            $recupContextForm =  $userForm->getData()->getRetContext(); 



            //0_100_1-3-2-Lecture  donnees entite User sur la base de donnees
			$sql =   "SELECT usr FROM App\Entity\system\User usr WHERE usr.valid = 1 AND usr.pseudo =  '".$pseudoForm."'";
			//$sql =   "SELECT usr FROM App\Entity\system\User usr WHERE usr.valid = 1 AND usr.pseudo = 'admin'";
            try{
    			$query= $entityManager->createQuery($sql);
                $user = $query->getOneOrNullResult();
            }
            catch (\Doctrine\DBAL\Exception\ConnectionException $e) { 
                //Debug
                $logger->sendDebug("0_100_1-3-2-Erreur de connexion à la base de données : " . $e->getMessage()); 
            }
            if($user){
                $pwdBd = $user->getPwd();
                $refUser = $user->getId();
                $nomUser = $user->getNom();
                $prenomUser = $user->getPrenom();
                $nbEchecConn = $user->getNbEchecConn();
                $isBlackList = $user->getBlackList();
                $dateConn = $user->getDateConn();
                $contextJson = $user->getContext();
                $ip_contrainte  = $user->getIpContrainte();
                //Debug
                $logger->sendDebug("/0_100_1-3-2-Lecture  donnees entite refUser =".$refUser." pwdBd=".$pwdBd." pwdForm=".$pwdForm);               

                // 0_100_1 3-Test identifiation client
                if(($pwdBd !== $pwdForm) && ($pwdBd !=='') && (!$isBlackList)){$cas = 1;
                    // Debug pour avoir le pass hache dans le fichier log
                    $pwdForm_hach = password_hash($pwdForm, PASSWORD_DEFAULT);
                   $logger->sendDebug("0_100_1 3-3-Test identifiation client- pwdForm_hach ".$pwdForm_hach);
                }
                if(($pwdBd == $pwdForm) && ($pwdBd !=='') && (!$isBlackList)){$cas = 2;}
                //--------------------------------
                // 0_100_1 3-Test cas 1
                if($cas === 1){
                    if(!password_verify($pwdForm, $pwdBd)) {
                        //0_100_1 3-Erreur identification
                        // Debug $logger->sendDebug("0_100_1 -Test cas 1- pwdForm: ".$pwdForm.",pwdBd:  ".$pwdBd); 
                        $err = true;
                        $logger->sendDebug("0_100_1 -Test cas 1- Erreur identification ".$refUser);
                        //0_100_1 3-On enregistre un echec supplementaire
                        $nbEchecConn++;
                        $user->setNbEchecConn($nbEchecConn);
                        $entityManager->persist($user);
                        $entityManager->flush();
                        //0_100_1 3-Notification
                        $nbErrRestantes= $nbErrConnAllowed - $nbEchecConn;
                        $msg_0 = $txtLang['message_system']['warning_0_0'];
                        $msg_1 = $txtLang['message_system']['warning_1_0'];
                        $msg_2 = $txtLang['message_system']['warning_1_1'];

                        $msg =  $msg_0." ".$msg_1." ".$nbErrRestantes." ".$msg_2;
                        $notificationsWarning[]= $msg;
                        $notificationsNotice[]= "Message de test a supprimer lgn 128 ";
                       $logger->sendDebug("0_100_1 Erreur identification cas 1 ".$refUser);
                    }
                }
                //--------------------------------
                // 0_100_1 4-Test cas 2
                if($cas === 2){
                    // Dans ce cas, le mot de passe n'est pas hache est il correspond, mais il faut le changer 
                    $notificationsWarning[]= $txtLang['message_system']['warning_4_0'];
                }
                //--------------------------------
                //   0_100_1 5-1- test IP autorise
                    $ipClient= $request->getClientIp();
                    //Debug
                   $logger->sendDebug("0_100_1 3-1- test IP autorise- ipClient".$ipClient);
                   //test de contraintes sur l'adresse IP 
                    $c0=strpos($ip_contrainte,$ipClient)>-1?true:false;    // Seules les adresses notées sont autorisees
                    $c1=strpos($ip_contrainte,'xxxx.x.x.x')>-1?true:false; // Toutes les adresses sont autorisees
                    $isIpContrainte = $c0 || $c1;
                    if(!$c0 && !$c1) {
                        $retErrorPage = $txtLang['message_system']['warning_7_0'];
                       $logger->sendDebug("0_100_1 3-1- test IP autorise- test IP autorise error ".$ipClient);
                    }
                    // 	0_100_1 5-2- test isBlackListed
                    if($isBlackList){
                        $logger->sendDebug("0_100_1 3-2- test isBlackListed -isBlackList ");
                        $err = true;
                        $retErrorPage = $configNode['message_system']['warning_7_0'];
                    }
                    // 	0_100_1 5-3- si le nombre d'erreur est superieur a ['max_tentative_connexion']
                    if($nbErrRestantes < 0){
                        $logger->sendDebug("0_100_1 3-2- test isBlackListed -setBlackList ");
                        $user->setBlackList(1);
                        $entityManager->persist($user);
                        $entityManager->flush();
                        $retErrorPage = $configNode['message_system']['warning_7_0'];
                    }
              
                    if($retErrorPage){
                        //0_100_1 6-Redirection sur la page d'erreur, retErrorPage contient un message d'erreur
                            return $this->render(
                                    'system/erreur.html.twig',['erreur_system'=>$retErrorPage]
                            );
                        }
                    //0_100_1 7-Test si user est deja connecte en tant que pseudo 
                    if((float)$dateConn > 0){
                        $err = true;
                        $msg =  $configNode['message_system']['warning_3_0'];
                        $logger->sendDebug('0_100_1 5-Erreur User '. $msg);
                        // Notification
                        $notifications["warning"][]=  $msg;
                    }
    
                    if(!$err){
                        //0_100_1 7-Ouverture de session *voir (https://symfony.com/doc/current/session.html)
                        $session = $requestStack->getSession();
                        //On enregistre les params de connexion ipUser pour s'assurer qu'il s'agit toujours du meme user pendant la session
                        $session->set('IpUser',$ipClient);   
                        $session->set('refUser',$refUser);
                        $session->set('utilisateur_nom',$nomUser);
                        $session->set('utilisateur_prenom',$prenomUser);
                        $session->set('lang',$lang);


                        $session->set('context',$notificationsNotice);        

                        $session->set('session_id',session_id());
                        $session->set('dateConnexion',$dateConn);   
                        $session->set('notificationsWarning',$notificationsWarning); 
                        $session->set('notificationsNotice',$notificationsNotice); 
                        // 0_100_1 8-Appel contexte
                        if($recupContextForm =='1'){
                            //0_100_1 8-1-Récuperation du dernier contexte sur la base
                            $context = json_decode($contextJson);
                           $logger->sendDebug("0_100_1 3-6-Appel contexte: ".$context[0]);
                            //0_100_1 8-1-exclus les nodes systemes
                            $nodesExclus=",0_100_1,0_110_0,";
                            if(!strpos($nodesExclus,$context[0])){
                                $node_suivant = $context[0];
                               $logger->sendDebug("0_100_1 3-6-Appel contexte - nodeSuivant exclus: ".$context[0]);
                            } 
                            else{$node_suivant = "0_130_0";}
                        }
                        else{
                            // 0_100_1 4-Appel node suivant 0_130_0  menu_principal par defaut
                            $node_suivant = "0_130_0";
                        }
                        // 0_100_1 9 Note heure de connexion (time() stamp depuis 1 janvier 1970)
                        // !! UNIQUEMENT DANS LA VERSION DEVELLOPPEMENT
                        //                    $user->setDateConn( time());
                        //                    $entityManager->persist($user);
                        //                    $entityManager->flush();
                        // 0-100-10-Appel node suivant
                        $context = [];
                        // params = [nodeCourant, nodeAppele,crud, contexte[]]
                        // 0-100-11-Appel menu principal
                        $params = ["0_100_1",$node_suivant,"0",$context];
                        $paramsJson=json_encode($params);
						// !Passage de parametres uniquement pour un appel routeur
                        $notificationsWarning[]="Test notification warning";
                        return $this->redirectToRoute('system_routeur',['params'=> $paramsJson ]);
                    }
            }
            else
            {
                // 0_100_1 5-Erreur User
                $msg =  $txtLang['message_system']['warning_0_0'];
                $logger->sendDebug('0_100_1 5-Erreur User '. $msg);
                // Notification
                $notificationsWarning[]=  $msg;
            }
        }
        // 0_100_1 6-Appel Template Connexion
        return $this->render(
                                'system/connexion.html.twig',
                                [
                                    'form'=> $userForm->createView(),
                                    'texte_page'=>$txtLang["texte_page"],
                                    'notificationsWarning'=>$notificationsWarning,
                                    'notificationsNotice'=>$notificationsNotice,
                                ]
                            );
    }

        #[Route('/boss_chg_pwd', name: 'system_chg_pwd')]
        
        public function changePassword(
            Request $request,
            EntityManagerInterface $entityManager,
            LoggingMessage $logger,
        ): Response {
            // 0_100_3 0-Récupérer l'utilisateur connecté depuis la session
            $session = $request->getSession();
            $lang = $session->get('lang');
            $refUser = $session->get('refUser');
            $fichier_log = $session->get('fichier_log');

            // 0_100_3 3-Configuration de la page
            $txtLang = Yaml::parseFile('../config/system/chg_pwd_'.$lang.'.yaml');
            $logger->sendDebug("0_100_3 0-Récupérer l'utilisateur connecté :".$refUser);

            // Vérifier si l'utilisateur est connecté
            if (!$refUser) {
                $msg = $txtLang['message_system']['warning_1_0'];
                $this->addFlash('error', $msg);
                return $this->redirectToRoute('app_boss_connexion');
            }

            // Récupérer l'utilisateur depuis la base de données
            $user = $entityManager->getRepository(User::class)->find($refUser);
            if (!$user) {
                $msg = $txtLang['message_system']['warning_2_0'];
                $this->addFlash('error', $msg);
                return $this->redirectToRoute('app_boss_connexion');
            }

            // Création du formulaire de changement de mot de passe
            $form = $this->createFormBuilder()
                ->add('oldPassword', TextType::class, ['required' => true])
                ->add('newPassword', TextType::class, ['required' => true])
                ->add('confirmPassword', TextType::class, ['required' => true])
                ->getForm();

            // Gestion de la soumission du formulaire
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                // Vérification de l'ancien mot de passe (en clair si non haché)
                $currentPassword = $user->getPwd();
                if (!password_verify($data['oldPassword'], $currentPassword) && $data['oldPassword'] !== $currentPassword) {
                    $msg = $txtLang['message_system']['warning_3_0'];
                    $logger->sendDebug("Ancien mot de passe incorrect : ".$data['oldPassword']);
                    $this->addFlash('error', "Ancien mot de passe incorrect");
                    return $this->redirectToRoute('app_boss_chg_pwd');
                }

                // Vérification que les nouveaux mots de passe correspondent
                if ($data['newPassword'] !== $data['confirmPassword']) {
                    $msg = $txtLang['message_system']['warning_4_0'];
                    $this->addFlash('error', $msg);
                    return $this->redirectToRoute('app_boss_chg_pwd');
                }

                try {
                    // Hacher le nouveau mot de passe et le sauvegarder
                    $hashedPassword = password_hash($data['newPassword'], PASSWORD_DEFAULT);
                    $user->setPwd($hashedPassword);
                    $entityManager->flush();

                    $msg = $txtLang['message_system']['notice_0_0'];
                    $logger->sendDebug("Mot de passe modifié avec succès pour l'utilisateur : ".$refUser);
                    $this->addFlash('success', $msg);
                    return $this->redirectToRoute('boss_menu_principal');
                    
                } catch (\Exception $e) {
                    $msg = $txtLang['message_system']['warning_5_0'];
                    $logger->sendDebug("Erreur lors de la mise à jour du mot de passe : ".$e->getMessage());
                    // return $this->render('system/erreur.html.twig', ['erreur_system' => $msg]); 
                    return $this->redirectToRoute('system_connexion');
                }
            }

            // Affichage du formulaire de changement de mot de passe
            return $this->render('system/chg_pwd.html.twig', [
                'form' => $form->createView(),
                'texte_page' => $txtLang["texte_page"],
                'texte_Form' => $txtLang["texte_Form"]
            ]);
        }




    #[Route('/boss_routeur', name: 'system_routeur')]
    public function routeur(
        Request $request,
        EntityManagerInterface $entityManager, 
        LoggingMessage $logger,
        User $user,
        ConfigurationPage $configurationPage,
        SystemUtilities $systemUtilities,
        RequestStack $requestStack)
    {
        //110_0 0-globales
        $notificationsWarning =[];
        $notificationsNotice =[];
        $error = false;
        $droitUser = false;

        //0_110_0 1-Lecture et décodage des parametres  
        $paramsJson = $request->query->get('params');
        $params = json_decode($paramsJson);
        // params = [nodeCourant, nodeAppele, selection_page_appelante[]]
        $requestAppelant= $params[0];
        $requestAppele  = $params[1];
        $requestCrud = $params[2];
        if(isset($params[3])){$context = $params[3];} else {$context =[];}
    
        //0_110_0 2- getter session
        $session = $requestStack->getSession();
        //Recuperation de la session ouverte a la connexion
        $session_ouverte = $session->get('session_id');  
        $refUser =  $session->get('refUser');
        $lang =  $session->get('lang');
        $dateConnexion = $session->get('dateConnexion');
        $fichie0_r_log = $session->get('fichier_log');
    //Debug          
    $logger->sendDebug("0_110_0 0Lecture et décodage des parametres".$paramsJson);

        //0_110_0 3-get_notifiations Si l'appelant est le node  0_100_1 connexion, on recupere les notifiations
        if($requestAppelant == '0_100_1'){
            $notificationsWarning= $session->get('notificationsWarning');
            $notificationsNotice= $session->get('notificationsNotice');
        }
    
        //0_110_0 4-Recuperation config routeur
        $configNode = $configurationPage->getSystemeConf('boss_routeur', $logger);
        $txtLang = Yaml::parseFile( '../config/system/routeur_'.$lang.'.yaml');
 
        //0_110_0 5-Recuperation des droits && routing 
    $logger->sendDebug("0_110_0 5-Recuperation des droits && routing");
        $groupe_users = $configurationPage->getSystemeConf('boss_groupe_users', $logger); 
        $droits_nodes = $configurationPage->getSystemeConf('boss_droits_nodes', $logger); 
        $nodes_routing = $configurationPage->getSystemeConf('boss_routing', $logger); 

        //0_110_0 6-Test des droits utilisateur sur le node
            //$droitsNode represente l'ensemble des groupes qui ont une autorisation sur le node
            // pour chacun de ces groupes on recherche si refUser appartient a l'un d'entre eux 
            // $grpUsers = ["tout_le_monde",""]
            //* $grpUsers= explode(",", $configDroitsNodes['droits_nodes'][$requestAppele]);
            // $ $grpUsers[0]= $gpu = 'tout_le_monde';
    $logger->sendDebug("0_110_0 6-Test des droits utilisateur sur le node");
        $droitsDuNode = $droits_nodes[$requestAppele];
        //Test droit au node pour l'utilisateur par le service systemUtilities->isNodeDroitUser
        if(!$systemUtilities->isNodeDroitUser($groupe_users,$droitsDuNode, $refUser, $logger)){
       // if(!$systemUtilities->isNodeDroitUser($groupe_users,$logger,$droitsDuNode,$refUser)){
            // Debug $logger->sendDebug("0_110_0 3-Test des droits utilisateur sur le node non autorized: ".json_encode($droitsDuNode));
            $msg = $txtLang['message_system']['warning_4_0'].$requestAppele;
            $logger->sendDebug("0_110_0 3-Droits utilisateur sur le node:".$msg);
            
            $notificationsWarning[]= $msg;
            $session->set('notificationsWarning',$notificationsWarning);
            //0_110_0 3-1-Rappel du contexte precedent (page precedente)
            $route = $nodes_routing[$requestAppelant];
            return $this->redirectToRoute($route);
        }
     
        // 0_110_0 7-is requete Ajax 
    $logger->sendDebug("0_110_0 7-is requete Ajax");
            //Dans le cas ou requestAppelant && requestAppele ont le meme prefixe (x_xx_?),  le node appele est une requete ajax
	        if( $systemUtilities->isRequeteAjax($requestAppelant,$requestAppele)){
             // Debug 
            $logger->sendDebug("0_110_0 3-is requete Ajax: true ".$requestAppele);
            $logger->sendDebug(" Service SystemUtilities-isRequeteAjax requestAppelant =".$requestAppelant.", requestAppele = ".$requestAppele );        
             //7-1-Recuperation du contexte pour avoir l'identifiant de selection
            $contextStr = gettype ( $context) == 'array'? $contextStr = json_encode( $context,true): $contextStr = $context;
        $logger->sendDebug("0_110_0 3-is contextStr ".$contextStr);
    
            $route = $nodes_routing[$requestAppele];
             //7-2-Appel node suivant
        $logger->sendDebug("7-2-Appel node suivant");
            $session->set('nodeCourant',$requestAppele);
            $session->set('nodeAppelant',$requestAppelant);
            $session->set('crud',$requestCrud);
            $session->set('context',$context);

        $logger->sendDebug(" Service SystemUtilities-isRequeteAjax route = ".$route);
              return $this->redirectToRoute($route,[ "context" => $contextStr, "crud" => $requestCrud]);
        }
 
        //0_110_0 8-enregistrement du contexte utilisateur user
            //8-1-appel entite user pour mise a jour du contexte de l'utilisateur
        $logger->sendDebug("0_110_0 8-enregistrement du contexte utilisateur user");

            $user = $entityManager->getRepository('App\Entity\system\User')->findOneBy(['id' => $refUser]);
            $user->setContext($paramsJson);
            $entityManager->persist($user);
            $entityManager->flush();

                //8-2-Deconnexion
            if($requestAppele == "-1"){
        $logger->sendDebug("8-2-Deconnexion");
                $error = true;
                // 8-2-1 Suppression session
                    session_destroy();
                // 8-2-2 RAZ l'heure de connexion dans la table User
                    $dateConn = $user->getDateConn();
                    $user->setDateConn(""); 
                    $entityManager->persist($user); $entityManager->flush();   
                // 8-2-3 logger Duree de connexion user  (pour stat)
                    $dureeConnexion =  time()- (integer)$dateConn ;
                    $logger->sendDebug("/4-3 logger Duree de connexion user ");
                    $logger->sendDebug("refUser: ".$refUser.", dateConn:".$dateConnexion.", \n\r dureeConnexion: " . $dureeConnexion);
                    $logger->sendDebug($txtLang['message_system']['notice_0_0']) ;
                // 8-2-4 Redirection sur la page de connexion
                    $route = 'system_connexion';
                    return $this->redirectToRoute($route);
            }
    
        //0_110_0 10-Controle session courante 
            //controle si session_ouverte est celle ouverte a la connexion 
            // *voir 0_100_1 3-5-Ouverture de session https://symfony.com/doc/current/session.html
    $logger->sendDebug("0_110_0 10-Controle session courante");

        $session_courante = session_id();
        if(!$error && ($session_ouverte !== $session_courante)) 
        {
            $error = true; 
            $msg = $txtLang['message_system']['warning_1_0'];
            $logger->sendDebug("0_110_0 6-Controle session courante :".$msg);
            return $this->render('system/erreur.html.twig', ['erreur_system'=>$msg]); 
        }   
        //0_110_0 11-Appel node suivant
    $logger->sendDebug("0_110_0 11-Appel node suivant requestAppelant = ".$requestAppelant.", requestAppele = ".$requestAppele );
        $session->set('nodeCourant',$requestAppele);
        $session->set('nodeAppelant',$requestAppelant);

        $session->set('crud',$requestCrud);
        $session->set('context',$context);

        // 11-1-test Si la route n'est pas definie 
        if (array_key_exists($requestAppele, $nodes_routing)) {
            $logger->sendDebug("11-1-test Si la route est definie " );
             $route = $nodes_routing[$requestAppele];
             $logger->sendDebug("11-1-test Si la route est definie route = ".$route );

        } 
        else{
            $logger->sendDebug($txtLang['message_system']['warning_2_0'].$txtLang['message_system']['warning_2_1']) ;
        }
        if(!isset($route)){
            $error = true;
            $msg =$txtLang['message_system']['warning_3_0'];
            $logger->sendDebug("/0_110_0 11-Appel node suivant: ".$msg);
            //La route n'est pas définie Appel de la page d'erreur
            return $this->render('system/erreur.html.twig', ['erreur_system'=>$msg]);
        }
        else{
            try{
                // 11-2-Redirection sur la route appelee "routeAppelle"
                //$logger->sendDebug("0_110_0 8-Redirection sur la route appelee: route ".$route.", Crud".$requestCrud);
                return $this->redirectToRoute($route);
            }
            catch(Exception $exception)
            {
                //0_110_0 8-Appel de la page d'erreur inconnue
                $msg = "Error routing ".$route;
                $logger->sendDebug($msg);
                return $this->render('system/erreur.html.twig', ['erreur_system'=>$exception->getMessage()]);
            }
        }
    }   

    #[Route('/boss_menu_principal', name: 'system_menu_principal')]
    public function menu_principal(
            Request $request,
            EntityManagerInterface $entityManager, 
            LoggingMessage $logger,
            ConfigurationPage $configurationPage,
            RequestStack $requestStack ):Response

    {
        //0_130_0 0-globales
        $notificationsWarning =[];
        $notificationsNotice =[];
       
        //0_130_0 1- getter session
        $session = $requestStack->getSession();
        $refUser = $session->get('refUser');
        $nodeCourant = $session->get('nodeCourant');
        $lang =  $session->get('lang');
        $notificationsWarning  = $session->get('notificationsWarning');
        $notificationsNotice = $session->get('notificationsNotice');
        
        //0_130_0 2-Recuperation des messages  du menu principal
        $configNode = $configurationPage->getSystemeConf('boss_menu_principal', $logger);
        $txtLang = Yaml::parseFile( '../config/system/menu_principal_'.$lang.'.yaml');
        //0_130_0 3-Recuperation groupes users
        $groupesUser = $configurationPage->getSystemeConf('boss_groupe_users', $logger);

        //0_130_0 4 Boutons outillage
            // Pour chaque commande du tableau on ne  copie la commande que si elle est autorise pour refUser
            $cmdeOutilsPage = $configNode["cmdeOutilsPage"];
            $cmdeOutilsPage =  $configurationPage->getCmdAllowedByUser($cmdeOutilsPage,$refUser,$groupesUser,$txtLang['texte_page'],$logger);

        //0_130_0 5-Appel du menu principal
        return $this->render('system/menu_principal.html.twig', [
            'nodeCourant' => $nodeCourant,
            'configNode' =>$configNode,
            'configPage' =>$txtLang,
            'cmdeOutils' =>$cmdeOutilsPage,
            'notificationsWarning' =>$notificationsWarning,
            'notificationsNotice' =>$notificationsNotice,
        ]);
    }}