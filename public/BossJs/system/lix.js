
class SystemExpert {
	constructor() { }
	parse_lix(pProg, pAux, indexBib) {
		this.logger.sendDebug(pProg);
		// 1-Definition et initialisation des globales
		const ts = []; 	// tableau des caracteres definissant la requete a executer
		let ch = ''; 	// un element du tableau de cara
		const nf = []; // table des nom de fonctions
		const pf = []; // table des params colonnes des fonctions
		const i = 0; // pointeur de fonction dans la pile des fonctions
		const t = ''; // texte
		let isEscapeCar = false; // 0-n est pas un caractere d'escape, 1- c'est un caractere d'escape
		let isCarToEscape = false; // le caractere courant doit etre echappé c'est un caractere et non un separateur: ( , )
		let i_count = 0; //compteur du tableau d'analyse des characteres ts
		const tailleRequete = 0; // longueur de la requete
		let charLast = ''; // Dernier caracter scanne avant le caracter courant (ch)
		let cond = ''; // condition
		let toReturn = ''; // valeur a renvoyer
		//----------------------------------------------------------------------------------------
		//       Definitions  des cas
		const cas_0 = '(';
		const cas_1 = '((';
		const cas_2 = '(,';
		const cas_3 = '()';
		const cas_4 = ',';
		const cas_5 = ',(';
		const cas_6 = ',,';
		const cas_7 = ',)';
		const cas_8 = ')';
		const cas_9 = ')(';
		const cas_10 = '),';
		const cas_11 = '))';
		//----------------------------------------------------------------------------------------

		// parse_lix
		ts = pProg.split('');
		tailleRequete = ts.length;
		for (i_count; i_count < tailleRequete; i_count++) {
			const ch = ts[i_count];
			if (!isEscapeCar && !isCarToEscape && (ch === '(' || ch === ',' || ch === ')')) {
				ctrl = charLast + ch;
				isFinAnalyse = tailleRequete - (i_count + 1) == 0;
				//----------------------------------------------------------------------------------------
				//       Etudes des cas
				if (ctrl === '(' && i === 0) { cond = 'cas_0_0'; }					// tt_0 fonc principale
				else if (ctrl === '(' && i > 0) { cond = 'cas_0_1'; }					// erreur
				else if (ctrl == "((" && i == 0) { cond = "cas_1_0"; }						// tt_2 debut fonc param
				else if (ctrl == "((" && i > 0) { cond = "cas_1_1"; }						// tt_2 debut fonc param

				else if (ctrl == "(," && i == 0) { cond = "cas_2_0"; } 					// tt_1 ajoute param scal
				else if (ctrl == "(," && i > 0) { cond = "cas_2_1"; } 					// tt_1 ajoute param scal

				else if (ctrl == "()" && i == 0 && isFinAnalyse) { cond = "cas_3_0"; } 	// tt_4 retVal_0
				else if (ctrl == "()" && i == 0 && !isFinAnalyse) { cond = "cas_3_1"; }	// erreur
				else if (ctrl == "()" && i > 0 && isFinAnalyse) { cond = "cas_3_2"; }	// erreur
				else if (ctrl == "()" && i > 0 && !isFinAnalyse) { cond = "cas_3_3"; }	// tt_3 fin fonc param

				else if (ctrl == ",") { cond = "cas_4"; } 									// erreur

				else if (ctrl == ",(" && i > 0) { cond = "cas_5_0"; } 					// tt_2 debut fonc param
				else if (ctrl == ",(" && i == 0) { cond = "cas_5_1"; } 					// tt_2 debut fonc param

				else if (ctrl == ",," && i > 0) { cond = "cas_6_0"; }						// tt_1 ajoute param scal
				else if (ctrl == ",," && i == 0) { cond = "cas_6_1"; } 					// tt_1 ajoute param scal

				else if (ctrl == ",)" && i == 0 && isFinAnalyse) { cond = "cas_7_0"; } 	// tt_4 retVal_0
				else if (ctrl == ",)" && i == 0 && !isFinAnalyse) { cond = "cas_7_1"; }  // tt_3 fin fonc param
				else if (ctrl == ",)" && i > 0 && isFinAnalyse) { cond = "cas_7_2"; } 	// erreur
				else if (ctrl == ",)" && i > 0 && !isFinAnalyse) { cond = "cas_7_3"; } 	// tt_3 fin fonc param

				else if (ctrl == ")") { cond = "cas_8"; } 									// erreur
				else if (ctrl == ")(") { cond = "cas_9"; }									// erreur

				else if (ctrl == "),") { cond = "cas_10"; } 								//Ne fait rien

				else if (ctrl == "))" && i == 0 && isFinAnalyse) { cond = "cas_11_0"; } //	tt_5 retVal_1
				else if (ctrl == "))" && i == 0 && !isFinAnalyse) { cond = "cas_11_1"; }// erreur
				else if (ctrl == "))" && i > 0 && isFinAnalyse) { cond = "cas_11_2"; }  // Descente de fonction param
				else if (ctrl == "))" && i > 0 && !isFinAnalyse) { cond = "cas_11_3"; } // tt_3 fin fonc param
				//----------------------------------------------------------------------------------------
				//       Regroupement des cas
				if (cond == "cas_0_0"
				) {
					tt = "tt_0";
				} // tt_0 fonc principale
				if (cond == "cas_2_0"
					|| cond == "cas_2_1"
					|| cond == "cas_6_0"
					|| cond == "cas_6_1"
				) {
					tt = "tt_1";
				} // tt_1 ajoute param scal
				if (cond == "cas_1_0"
					|| cond == "cas_1_1"
					|| cond == "cas_5_0"
					|| cond == "cas_5_1"
				) {
					tt = "tt_2";
				} // tt_2 debut fonc param
				if (cond == "cas_3_3"
					|| cond == "cas_7_1"
					|| cond == "cas_7_3"
					|| cond == "cas_11_3"
				) {
					tt = "tt_3";
				} // tt_3 fin fonc param
				if (cond == "cas_3_0"
					|| cond == "cas_7_0"
				) {
					tt = "tt_4";
				} // tt_4 retVal_0
				if (cond == "cas_11_0"
				) {
					tt = "tt_5";
				} // tt_5 retVal_1

				if (cond == "cas_10"
				) {
					tt = "tt_6";
				} // tt_6 cas 10

				if (cond == "cas_0_1"
					|| cond == "cas_3_1"
					|| cond == "cas_3_2"
					|| cond == "cas_4"
					|| cond == "cas_7_2"
					|| cond == "cas_8"
					|| cond == "cas_9"
					|| cond == "cas_11_1"
					|| cond == "cas_11_2"
				) {
					tt = "erreur";
				} // erreur

				//----------------------------------------------------------------------------------------
				// Conditional statements
				if (tt === 'tt_0') {
					nf[i] = t;
				} else if (tt === 'tt_1') {
					pf[i] = [t];
				} else if (tt === 'tt_2') {
					i++;
					nf[i] = t;
				} else if (tt === 'tt_3') {
					pf[i] = [t];
					for (let x = i; x > 0; x--) {
						returnVal = this.execute_lix(nf[x], pf[x], '', pAux, indexBib);
						pf[x - 1] = [returnVal];
						pf[x] = [];
					}
				} else if (tt === 'tt_4') {
					pf[i] = [t];
					returnVal = this.execute_lix(nf[i], pf[i], '', pAux, indexBib);
				} else if (tt === 'tt_5') {
					returnVal = this.execute_lix(nf[i], pf[i], '', pAux, indexBib);
				} else if (tt === 'tt_6') {
					// Todo: Implement the logic for tt_6
				} else if (tt === 'tt_7') {
					// Todo: Implement the logic for tt_7
				} else if (tt === 'erreur') {
					// Todo: Implement the logic for erreur
				}
				//----------------------------------------------------------------------------------------
				t = "";
				charLast = ch;
				if (toReturn !== "") { return toReturn; }
			}//fin if

			if (isCarToEscape || (!isEscapeCar && ch !== '(' && ch !== ',' && ch !== ')')) {
				// Condition 1-2-1
				t = t + ch;
			}
			if (isCarToEscape) {
				//Condition 1-2-2
				isCarToEscape = false;
			}
			if (isEscapeCar) {
				//Condition 1-2-3
				isCarToEscape = true;
			}

		}  // fin boucle for
		//---------------------------------------------------------------------------------------
	}//Fin fonction parse_lix
}  // Fin de la classe