<?php
namespace App\Service;
// src/Service/ConfigurationPage.php
//use App\Service\LoggingMessage;
//use Symfony\Component\HttpFoundation\RequestStack;
//use Symfony\Component\Yaml\Yaml;



class ConfigurationPage
{
	public function __construct(){}

	public function getConfiguration($module,$refNode, $logger): array
    {
        //retourne le chemin du node, reconstruit a partir des parametres 
		$configFilePath = "../config/".$module.'/'.$refNode.'.php';
        if (!file_exists($configFilePath)) {
			$logger->sendDebug("Service ConfigurationPage ---".$configFilePath);
            throw new \Exception('Configuration file not found');
        }
        return require_once $configFilePath;
    }

	public function getSystemeConf($refNode, $logger): array
    {
    //Debug $logger->sendDebug("Service ConfigurationPage  getSystemeConf: ../config/system/boss_".$refNode.".php");
		$configFilePath = '../config/system/'.$refNode.'.php';
		if (!file_exists($configFilePath)) {
			$logger->sendDebug("Service ConfigurationSystem ---".$configFilePath);
            throw new \Exception('Configuration file not found');
        }
        return require_once $configFilePath;
    }

    // ! A tester passage de dependance Yaml
    public function getCmdAllowedByUser($tabCmde,$refUser,$groupesUser,$traduction,$logger){
        // Filtre les commandes qui sont autorisees pour le user
        $tampon=[]; $isVisible=false;
		foreach ($tabCmde as $cmde){
			if($cmde[2][0] !== "tout_le_monde"){
				if($cmde[2][0] !== ""){
					// si la cmde appartient a un groupe, on recherche l'appartenance de refUser a ce groupe
					$grps = explode(';',$cmde[2][0]);
					foreach($grps as $grp) {
						if( strpos($groupesUser[$grp],$refUser) !==false){  $isVisible =true;}
					}
				}
				//if(  count($cmde[2][1]) > 0){
				if($cmde[2][1] !== ""){
					// si la cmde appartient a un utilisateur specifique
					 if( strpos($cmde[2][1],$refUser) !==false){ $isVisible =true;}
				}
			}
			else{$isVisible =true;}

            if($isVisible){
				// On traduit le libelle du bouton 
				$cmde[5]= $traduction[$cmde[5]];
				$tampon[]=$cmde; $isVisible = false;}
       	 }
        return $tampon;
    }
    //---------------------------------------------------------
    //Entity
    public function getIdEntity($entityManager,$Entity,$id_entity){
        $sql = "SELECT u FROM App\Entity\\".$Entity." u WHERE u.id = :id";
		$query = $entityManager->createQuery($sql);
        $query->setParameter('id', $id_entity);
		return  $query->getOneOrNullResult();
       }

    public function getDossierEntity($entityManager,$Entity,$dossier){
        $sql = "SELECT u FROM App\Entity\\".$Entity." u WHERE u.dossier = :dossier";
		$query = $entityManager->createQuery($sql);
        $query->setParameter('id', $dossier);
		return  $query->getResult()();
       }

    public function getSelectionIdent($context, $indexCol ){
        return $context[0][ $indexCol];
    }

    public function getListeChoix($listesChoix,$txt_lang_texte,$logger){
        // listesChoix est un tableau de liste de choix
        //'listeChoix' => [
		//			"listeChoixPays" =>['txt_1_0'=>242,'txt_2_0'=>33,'txt_3_0'=>237],
		//			"listeChoixUid" =>['txt_11_0'=>0,'txt_12_0'=>1],
		//			"listeChoixLiensCommerciaux" => ['txt_16_0'=> 0,'txt_17_0' => 1, 'txt_18_0' => 2,'txt_19_0' => 3]
        // txt_lang est un tableau d=contenant les textes suivant la langue exp: txt_1_400_0_fr.yaml
        //texte_page:
		//			txt_1_0: "Congo Brazzavile"
		//			txt_2_0: "France"
		//			txt_3_0: "Cameroun"
		//			.......
        $tamponListesChoix =[];
        foreach($listesChoix as  $keyLst =>$lst){
            $tamponChoix=[];
            foreach($lst as $key => $choix){
                $keyStr = (string)($key);
                if (strpos($keyStr,'lst_') > -1 && !is_null($choix))
                {$tamponChoix[$txt_lang_texte[$keyStr]] = $choix;}
                else{$tamponChoix[$keyStr] = $choix;}
            }
            $tamponListesChoix[$keyLst]=$tamponChoix;
        }
        return $tamponListesChoix;
    }
    function getConfContChampForm($contraintesChamps){
        //1-Configuration du Controle des Champs du Formulaire
              // 1-2 Recherhe dans le groupe contraintes_champs
            $configCCF = [];
            $configCCF['contraintes']= $contraintesChamps["contraintes_champs"];
           // $configCCF['errorsMessages']= $messageNode["errors_messages"];
            return json_encode($configCCF);
    }

    function testGedFilesReservation($file,$configNode,$txtLang,$logger){
        //Ged Test erreur de fichier en reservation
        $error = "";
        $tt = explode(".",$file["name"]);
        $ttTaille = count($tt);
        $extensionFile = $tt[($ttTaille-1)];
        // Liste des extensions autorisées
        $lstExtensions = $configNode['extensions_autorises'];
        $logger->sendDebug("lstExtensions ".$lstExtensions);
        $logger->sendDebug("extensionFile ".$extensionFile);
        if( strpos($lstExtensions,$extensionFile)< 0){$
            $logger->sendDebug("strpos($lstExtensions,extensionFile) > -1 ");
            $error = $txtLang['warning_0_0']." ".$lstExtensions;
        }
        //test taille du fichier
        $ficTaille = $file['size']; $maxTaille = $configNode['taille_max_fichier'];
        if(  $ficTaille +1 > $maxTaille){$ $error = $txtLang['warning_1_0']." ".$lstExtensions;}
            //Debug
            $msg = $error !== "" ?$error:"no error";          
            $logger->sendDebug("error ".$msg);
        return $error;
    }
    function getTitreDoc($titre){
        $tt = explode(".",$titre);
        $ttTaille = count($tt);
        $extensionFile = ".".trim($tt[($ttTaille-1)]);
        $tailleExtension = strlen($extensionFile);
        $titre=substr($titre,0,-$tailleExtension );
        return $titre;
    }
    

    function getExtensionDoc($titre){
        $tt = explode(".",$titre);
        $ttTaille = count($tt);
        return $tt[($ttTaille-1)];
    }
}
