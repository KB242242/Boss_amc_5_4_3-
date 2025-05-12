<?php
namespace App\Service;

use App\Service\LoggingMessage;
use Symfony\Component\Yaml\Yaml;

class SystemUtilities
{
	private $groupe_users=[];
    private $logger;
	public function __construct()
	   {

	   }
 	//------------------------------------------------------------
	//Utilitaires config
    /**
	*	function cmdAllowedByUser($tabCmde,$refUser,$groupesUser,$logger){
    *    $tampon=[]; $isVisible=false;
    *    foreach ($tabCmde as $cmde){
    *        $logger->sendDebug(' Page utilities:cmde: '.$cmde[0]);
    *        if($cmde[2][0] !== ""){
    *             // si la cmde appartient a un groupe, on recherche l'appartenance de refUser a ce groupe
    *            $logger->sendDebug(' 3-boutons outillage grp:'.$cmde[0]);
    *            $grps = explode(';',$cmde[2][0]);
    *            foreach($grps as $grp) {
    *                if( strpos($groupesUser[$grp],$refUser) !==false){  $isVisible =true; $logger->sendDebug(' Page utilities:cond_1_ok: '.$cmde[0]);}
    *            }
    *        }
    *        //if(  count($cmde[2][1]) > 0){
    *        if($cmde[2][1] !== ""){
    *            // si la cmde appartient a un utilisateur specifique
    *            $logger->sendDebug(' 3-boutons outillage spec:'.$cmde[0].'specific users:'.$cmde[2][1]);
    *            if( strpos($cmde[2][1],$refUser) !==false){ $isVisible =true; $logger->sendDebug(' Page utilities:cond_2_ok: '.$cmde[0]);}
    *        }
    *        if($isVisible){$tampon[]=$cmde; $isVisible = false; $logger->sendDebug(' Page utilities:isVisible:'.$cmde[0]);}
    *    }
    *    return $tampon;
	*	}
	**/

		function isNodeDroitUser($groupe_users,$droitsDuNode,$refUser,$logger){
		// users = [GrpAyantDroit, GrpUserSpecifique ] = [ 'gad_0, gad_1, ....', 'user_0, user_1, ....']
		// gad_0 = 'user_i, user_j, ....' 
		// ----------------------		
		// 1-recherche des droits dans l'ensemble des users specifique	'user_0, user_1, ....']	
		$usersSpec = $droitsDuNode[1];
		//test refUser appartient a l'ensemble des specifiques ou contient 'tout_le_monde'
		$isMembre = false;
		if(strpos($usersSpec,$refUser)>-1 ||  strpos($usersSpec,'tout_le_monde')>-1){
			$logger->sendDebug("isNodeDroitUser usersSpec = ".$usersSpec.", refUser = ".$refUser);
			$isMembre = true;
		}
		// ----------------------
		// 2-recherche des droits dans l'ensemble des groupes ayant droits grpAyantDroit 'gad_0, gad_1, ....'
		$ensembleGrpAyantDroit = $droitsDuNode[0];
		// separateur ','
		// 3-Si l ensemble des gad est non vide, on cree le tableau des gad 
		if($ensembleGrpAyantDroit!= ""){
			$grpAyantDroit= explode(',',$ensembleGrpAyantDroit);
			// 3-test si grpAyantDroit est un tableau de gad ['gad_0, gad_1, ....] non vide
			if(gettype($grpAyantDroit) == "array"){
				// 4-On test si l'ayant droit est tout le monde
				if($grpAyantDroit[0] == "tout_le_monde"){
					$logger->sendDebug("isNodeDroitUser-1 grpAyantDroit = ".$grpAyantDroit[0]);
					return true;
				}
				// 5-Pour chaque groupe de user (gad-groupe ayant droits) on recherche si refUser en fait partie
				$logger->sendDebug("isNodeDroitUser-2 usersSpec = ".$usersSpec.", refUser = ".$refUser);
				foreach($grpAyantDroit as $gad) {
						if (array_key_exists($gad, $groupe_users)) { 
							// 6-gad est bien une cle du tableau grpAyantDroit
							$membresGad = $groupe_users[$gad];
							if(strpos($membresGad,$refUser)>-1 ){
								$logger->sendDebug("isNodeDroitUser-3 isMembre = Vrai ");
								return true;}
						}
						else{
							// 7-gad n est pas une cle du tableau grpAyantDroit
						}
				}
			}
		}

		// fonction return
		$isMembreVrai=$isMembre?"Vrai":"Faux";
		$logger->sendDebug("isNodeDroitUser-4 isMembre =  ".$isMembreVrai);
		return $isMembre;
	}

	function isRequeteAjax($requestAppelant,$requestAppele){
		// 1-$requestAppelant 0_400_0, $requestAppele 0_400_x -> is requete Ajax true
		$isAjax =false;
		$appelant = explode("_",$requestAppelant);
		$appele = explode("_",$requestAppele);
		if( $appelant[0] == $appele[0] && $appelant[1] == $appele[1]){ $isAjax = true;}
		return $isAjax;
	}
	
}