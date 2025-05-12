<?php
namespace App\Service;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\LoggingMessage;
use App\Service\PDOutilities;

class SystemExpert
{
   private RequestStack $requestStack;
   private $logger;
   public function __construct(RequestStack $requestStack, LoggingMessage $logger)
   {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
   }

	 
	function parse_lix($pProg,$pAux,$indexBib){
		//Debug $this->logger->sendDebug($pProg);
		//1-Definition et initialisation des globales
		$ts = ''; 				// table des string
		$ch ='';				// tableau des caracteres constituant le nom du programme en entree 
		$nf=[]; 				//table des nom de fonctions
		$pf=[]; 				//table des params colonnes des fonctions
		$i=0;					// pointeur de fonction dans la pile des fonctions 
		$t="";					// texte decode
		$isEscapeCar = 	 false;	// 0-n est pas un caractere d'escape, 
									//1- c'est un caractere d'escape
		$isCarToEscape = false; // le caractere courant doit etre echappé c'est un 	caractere et non un separateur: ( , )
		$i_count = 0;
		$tailleRequete=0;
		$charLast ="";
		$cond ="";
		$toReturn="";
		//----------------------------------------------------------------------------------------
		//       Definitions  des cas
		$cas_0 ='(';	 
		$cas_1 ='((';	
		$cas_2 ='(,';	
		$cas_3 ='()';	
		$cas_4 =',';		
		$cas_5 =',(';	
		$cas_6 =',,';	
		$cas_7 =',)';	
		$cas_8 =')';		
		$cas_9 =')(';	
		$cas_10 ='),';	
		$cas_11 ='))';	  
		//----------------------------------------------------------------------------------------
		$ts=str_split($pProg);
		$tailleRequete = count($ts);
		foreach($ts as $ch){
			$isEscapeCar = $ch === '*';
			if(	!$isEscapeCar && !$isCarToEscape  && ($ch ==="(" || $ch === "," ||  $ch ===")" )){
				$ctrl = $charLast.$ch;
				$isFinAnalyse= $tailleRequete - ($i_count +1) == 0;
				//----------------------------------------------------------------------------------------
				//       Etudes des cas
				if($ctrl =="(" && $i == 0){$cond = "cas_0_0";}							// tt_0 fonc principale *
				elseif($ctrl =="(" && $i > 0){$cond = "cas_0_1";}						// erreur

				elseif($ctrl =="((" && $i == 0){$cond = "cas_1_0";}						// tt_2 debut fonc param *
				elseif($ctrl =="(("&& $i > 0){$cond = "cas_1_1";}						// tt_2 debut fonc param *

				elseif($ctrl == "(," && $i == 0){$cond = "cas_2_0";} 					// tt_1 ajoute param scal *
				elseif($ctrl == "(," && $i > 0){$cond = "cas_2_1";} 					// tt_1 ajoute param scal *

				elseif($ctrl == "()" && $i == 0 && $isFinAnalyse){$cond = "cas_3_0";} 	// tt_4 retVal_0 *
				elseif($ctrl == "()" && $i == 0 && !$isFinAnalyse){$cond = "cas_3_1";}	// erreur
				elseif($ctrl == "()" && $i > 0 &&  $isFinAnalyse){$cond = "cas_3_2";}	// erreur
				elseif($ctrl == "()" && $i > 0 &&  !$isFinAnalyse){$cond = "cas_3_3";}	// tt_3 fin fonc param *

				elseif($ctrl == ","){$cond = "cas_4";} 									// erreur

				elseif($ctrl == ",(" && $i > 0){$cond = "cas_5_0";} 					// tt_2 debut fonc param *
				elseif($ctrl == ",(" && $i == 0){$cond = "cas_5_1";} 					// tt_2 debut fonc param *

				elseif($ctrl == ",," && $i > 0){$cond = "cas_6_0";}						// tt_1 ajoute param scal *
				elseif($ctrl == ",," && $i == 0){$cond = "cas_6_1";} 					// tt_1 ajoute param scal *

				elseif($ctrl == ",)" && $i == 0 && $isFinAnalyse){$cond = "cas_7_0";} 	// tt_4 retVal_0 *
				elseif($ctrl == ",)" && $i == 0 && !$isFinAnalyse){$cond = "cas_7_1";}  // tt_3 fin fonc param *
				elseif($ctrl == ",)" && $i > 0 && $isFinAnalyse){$cond = "cas_7_2";} 	// erreur
				elseif($ctrl == ",)" && $i > 0 && !$isFinAnalyse){$cond = "cas_7_3";} 	// tt_3 fin fonc param *

				elseif($ctrl == ")"){$cond = "cas_8";} 									// erreur
				elseif($ctrl == ")("){$cond = "cas_9";}									// erreur

				elseif($ctrl == "),"){$cond = "cas_10";} 								//Ne fait rien

				elseif($ctrl == "))" && $i == 0 && $isFinAnalyse){$cond = "cas_11_0";} //	tt_5 retVal_1 *
				elseif($ctrl == "))" && $i == 0 && !$isFinAnalyse){$cond = "cas_11_1";}// erreur
				elseif($ctrl == "))" && $i > 0 && $isFinAnalyse){$cond = "cas_11_2";}  // Descente de fonction param
				elseif($ctrl == "))" && $i > 0 && !$isFinAnalyse){$cond = "cas_11_3";} // tt_3 fin fonc param *
				//----------------------------------------------------------------------------------------
				//       Regroupement des cas
				if( $cond == "cas_0_0"  
				){
					$tt = "tt_0";
				} // tt_0 fonc principale
				if( $cond == "cas_2_0"  
					|| $cond == "cas_2_1" 
					|| $cond == "cas_6_0" 
					|| $cond == "cas_6_1" 
				){
					$tt = "tt_1";
				} // tt_1 ajoute param scal
				if( $cond == "cas_1_0"  
					|| $cond == "cas_1_1" 
					|| $cond == "cas_5_0" 
					|| $cond == "cas_5_1" 
				){
					$tt = "tt_2";
				} // tt_2 debut fonc param
				if( $cond == "cas_3_3"  
					|| $cond == "cas_7_1" 
					|| $cond == "cas_7_3" 
					|| $cond == "cas_11_3" 
				){
					$tt = "tt_3";
				} // tt_3 fin fonc param
				if( $cond == "cas_3_0"  
					|| $cond == "cas_7_0" 
				){
					$tt = "tt_4";
				} // tt_4 retVal_0
				if( $cond == "cas_11_0"  
				){
					$tt = "tt_5";
				} // tt_5 retVal_1

				if( $cond == "cas_10"  
				){
					$tt = "tt_6";
				} // tt_6 cas 10

				if( $cond == "cas_0_1"  
					|| $cond == "cas_3_1" 
					|| $cond == "cas_3_2" 
					|| $cond == "cas_4" 
					|| $cond == "cas_7_2" 
					|| $cond == "cas_8" 
					|| $cond == "cas_9" 
					|| $cond == "cas_11_1" 
					|| $cond == "cas_11_2" 
				){
					$tt = "erreur";
				} // erreur
				//----------------------------------------------------------------------------------------
				//       Traitement des cas
				if( $tt == "tt_0" ){
					$nf[$i]= $t;
				} // tt_0 fonc principale
				if( $tt == "tt_1" ){
					$pf[$i][]= $t;
				} // tt_1 ajoute param scal
				if( $tt == "tt_2" ){
					$i++;
					$nf[$i]= $t; 
				} // tt_2 debut fonc param
				if( $tt == "tt_3" ){
					$pf[$i][]= $t; 
					for( $x = $i; $i>0; $i--){
						$returnVal =$this->execute_lix($nf[$i],$pf[$i],'',$pAux,$indexBib);
						$pf[$i-1][]= $returnVal;
						$pf[$i]=[];
					}
				} // tt_3 fin fonc param
				if( $tt == "tt_4" ){
					$pf[$i][]= $t; 
					$toReturn = $this->execute_lix($nf[$i],$pf[$i],'',$pAux,$indexBib);
				} // tt_4 retVal_0
				if( $tt == "tt_5" ){
					$toReturn = $this->execute_lix($nf[$i],$pf[$i],'',$pAux,$indexBib);
				} // tt_5 retVal_1

				if( $tt == "tt_6" ){
				} // tt_6 ne fais rien
				if( $tt == "tt_7" ){
				} // tt_7 n 
				if( $tt == "erreur" ){
				} // erreur

				//----------------------------------------------------------------------------------------
				$t="";
				$charLast = $ch;	
				if($toReturn !== ""){return $toReturn;}
			}//fin if
			$i_count++;
			//---------------------------------------------------------------------------------------
			//7- Fin Condition 1 s=0
			// soit le charactere analyse est un charactere escape (*) soit ce n'est pas un des charateres de controle
			// dans ce cas il fait parti du texte (t)en construction
			if($isCarToEscape||(!$isEscapeCar  && $ch !=='(' && $ch !==',' &&  $ch !==')')){
				// Condition 1-2-1
				$t .=$ch;
			}
			if($isCarToEscape){
			//Condition 1-2-2
				$isCarToEscape=false;
			}
			if($isEscapeCar){
			//Condition 1-2-3
				$isCarToEscape=true;
			}
		} // fin boucle foreach
	}//Fin fonction parse_lix

	function execute_lix($f,$p,$pp,$params,$indexBib)
	{
	// f fonction a executer
	// p tableau de parametres de la fonction a executer
	// pp parametres scalaires de la fonction principale
	// params parametres optionnels passe en tableau ou obbjet ou tableau d'objets
	// indexBib index de la biblibliotheque de fonctions a utiliser

		$retResult="";
		if($f == trim('titre')){ $retResult = $this->titre($p);}	
		if($f == trim('identUser')){ $retResult = $this->identUser($p);}	
		if($f == trim('logic_ou')){ $retResult = $this->identUser($p);}	

		return $retResult;
	}
	//Function executable par expert
	function titre($pt){
		$retVar="";
		$type=gettype($pt);
		if( $type == "array" || $type == "object"){
			foreach($pt as $e){
				$retVar .= $e. " ";
			}
		}
		else{$retVar = $pt;}
		return $retVar;
	}
	function identUser($pt){
		// pt est un tableau numerique classique de chaine [ "str_1", "str_2", "str_3",....]
		// la fonction, retourne une chaine des chaine séparée par des espaces
		// Fonction exemple:
	//mainTitle(Salut,userIdent(),dateDuJour()) 	
		$sql ="SELECT refClient,nom,ville,pays,checkTest1,checkTest2,checkTest3  	FROM m1s5_client WHERE RefClient =".$pt ;
		$retVar="";
		foreach($pt as $e){
			$retVar .= $e. " ";
		}
		return $retVar;
	}
	function logic_ou($pt){
		// $pt est un tableau de valeur
		$retVar="";
		$type=gettype($pt);
		if( $type == "array" || $type == "object"){
			foreach($pt as $e){
				$retVar .= $e. " ";
			}
		}
		else{$retVar = $pt;}
		return $retVar;
	}
	

} // Fin de la classe
