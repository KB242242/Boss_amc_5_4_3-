
/***
//--------------------------------------------------------------------
** A-Boss_4/Application/System/js_utilities.js
//--------------------------------------------------------------------
** Module System
//--------------------------------------------------------------------
** B-Objectif
//--------------------------------------------------------------------
** Bibliotheque de fonctions standards js  executable chez le client
//--------------------------------------------------------------------
** version 240127-BG
** CRA:
***/

//====================================================================================================================================
//methodes pour  dataTable
class selectionLigne {
	selTable = [];	// table des lignes (i) colonnes (j) des selection de la table 
	MAX_SEL = 0;

	isFirstSel = true;	// Flag premiere selection
	selsMemo = []; 		//Enregistreent des num lignes selectionnees
	indexRowSel = 0;		//
	couleurLigne = "";
	isMaxSel = false;
	isMaxSelAlarm = false; //
	isToAdd = true;
	// Pour notification (pending)
	ligneAffichage = "";
	msgHeader = "";		// Entete du message de selection
	listColMsg = "";	// Liste des colonnes selectionnees
	// Pour memoire
	//COLOR_SEL = "";		//"rgb(242,255,71)"
	//COLOR_NOT_SEL = ""; //"rgb(222,204,204)"



	constructor(MAX_SEL, msgHeader, listColMsg) {
		this.MAX_SEL = MAX_SEL;

		this.msgHeader = msgHeader;
		this.listColMsg = listColMsg;
	}
	// 2-Enregistrement de la selection
	getRowSelection(tSelection, indexRowSel) {
		// tSelection Contient la selection courante & indexRowSel contient le numero de ligne selectionnee
		// On enregistre les colonnes (j) de la lgn (i) selectionnee dans les tables:
		// sels(i)(j) tab de toutes les lignes selectionnees

		//1-Test  !isFirstSel && MAX_SEL > 1
		//Cas ou MAX_SEL ==1
		if (!this.isFirstSel && this.MAX_SEL > 0) {
			//Init couleurLigne
			this.couleurLigne = "";
			//2-1 Recherche de indexRowSel dans selsMemo. Dans ce cas il faut supprimer la selection
			if (this.selsMemo.includes(indexRowSel)) {
				//La ligne est deja enregistree dans selsMemo, elle doit etre supprimee 
				//Recherche de la position de indexRowSel
				let i_lgn = this.selsMemo.indexOf(indexRowSel);
				//2-2 Supp la seletion
				//La selection index i_lgn est a supprimer dans selsMemo
				this.selsMemo.splice(i_lgn, 1);

				// La selection index i_lgn est a supprimer dans selTable
				this.selTable.splice(i_lgn, 1);


				this.isToAdd = false;
				//La ligne prend la couleur COLOR_NOT_SEL
				this.couleurLigne = "rgb(222,204,204)";
				//isMaxSel devient faux dans tout les cas
				this.isMaxSel = false;
			}
		}
		//	On attend que isMaxSel soit vrai avant de declencher l'alarme
		if (this.isMaxSel) { this.isMaxSelAlarm = true; this.couleurLigne = ""; }
		else {
			this.isMaxSelAlarm = false;
			//3-Ajouter
			if ((this.isFirstSel || this.isToAdd) && (this.MAX_SEL > 0)) {
				// isFirstSel devient faux
				this.isFirstSel = false;
				//3-1 On ajoute la ligne selectionnee dans selTable
				this.selTable.push(tSelection);
				console.log("2" + this.selTable);
				//3-2 On rajoute la selection dans selsMemo
				this.selsMemo.push(indexRowSel);
				//3-3 changement couleur lgn COLOR_SEL
				this.couleurLigne = "rgb(242,255,71)";
				//Notification liges selectionnees
				// .....
				//3-4 Test isMaxSel
				this.isMaxSel = this.MAX_SEL < this.selsMemo.length + 1;
			}
		}
		// 3-6 Test selsMemo.length
		if (this.selsMemo.length == 0) { this.isFirstSel = true; }
		if (!this.isMaxSel) { this.isToAdd = true; }
	} // Fin getRowSelection()
	///////////////////////////////////////////////////////////////
	// 4- Getters
	//4-1 retourne toutes les selections
	getSelDataTable() { return this.selTable; }
	// 4-2 Retourne la couleur de ligne de la selection ourante
	getCouleurLigne() { return this.couleurLigne; }
	// 4-3 Retourne si un changement de couleur a eu  lieu  
	getChangeColor() { if (this.couleurLigne == "") { return false; } else { return true; } }
	// 4-4 Retourne la visibilite du bouton de la zone contextPart
	getBoutonContextView() {
		let isContextCmd = this.selsMemo.length > 0;
		return isContextCmd;
	}
	// 4-5 Retourne si maxSel est atteint
	getMaxSelect() { return this.isMaxSelAlarm; }
	// 4-6 retourne isOneSel si au moins une selection est active 
	getIsOneSel() { if (this.selTable.length == 1) { return true; } else { return false; } }
	// 4-7 Retourne visibilite de tous les boutons de service
	getVisibiliteCmde(pageCmde, tSelection) {
		// pageCmde = [[idCmde_0,tConditionVis_0,droitUser_0], [idCmde_1,tConditionVis_1,droitUser_1], .....];
		//Retourne un tableau de visualisation des commandes
		// visCmde = [[idCmde_0,tConditionVis_0], .....];
		let visCmde = [];
		let idCmde = "";
		let isDroitUser = false;
		let test = false;
		let isVisi = false;
		for (let i = 0; i < pageCmde.length; i++) {
			visCmde[i] = [];
			test = this.getCorrespondance(tSelection, pageCmde[i][1]);
			idCmde = pageCmde[i][0];
			visCmde[i][0] = idCmde;
			// getCorrespondance(tSelection, pageCmde[i][1]) retourne true si les conditions de pageCmde[i][1], sont respectees
			// pageCmde[i][2] == true ,si userDdroit = true
			isDroitUser = pageCmde[i][2];
			isVisi = test && isDroitUser;
			visCmde[i][1] = isVisi;
		}
		return visCmde;
	}

	getCorrespondance(tSelection, tCondition) {
		// tSelection Contient la selection courante au format
		// tSelection = [col_0,col_1,col_2,col_3,...]; 
		// tCondition = [ [col_k,val_k],....[col_i,val_i]]

		//recherche si la correspondance est respectee sur la selection 
		//let tsel = tSelection.split("!!!");
		let cond = true;
		for (let i = 0; i < tCondition.length; i++) {
			if (tSelection[tCondition[i][0]] !== tCondition[i][1]) {
				// if tSelection(col_k)== val_k
				cond = false;
				break;
			}
		}
		return cond;
	}
	getIsFirstSel() {
		return this.isFirstSel;
	}
}

class controleSaisie {
	tConstraint = [];
	tValeur = [];
	tMessages = [];
	tError = { message: '', nomChamp: '' };
	constructor(tConstraint, tMessages, tValeur) {
		this.tConstraint = tConstraint;
		this.tMessages = tMessages;
		this.tValeur = tValeur;
	}

	controlChampsForm() {
		//Pour chaque champVal on controle le respect des contraintes
		var valeur = null;
		var constraints = []; //[null,type(string,integer,float),....les contraintes suivantes dependent du type......]
		//type string: constraints 	= [`nom_du_champ`,`notNull`,`string`, minLength,maxLength,format(`#Expression rationnelle#`), excluded['chaine_1',...,'chaine_n]
		//type integer: constraints = [`nom_du_champ`,`notNull`,`integer`, min,max]
		//type float: constraints 	= [`nom_du_champ`,`notNull`,`float`, min,max]
		var errorMessage = "";
		for (var index_val = 0; index_val < this.tValeur.length; index_val++) {
			//type : "undefined","boolean","number", "bigint", "string", "object"
			valeur = this.tValeur[index_val];
			constraints = this.tConstraint[index_val];
			// 1-test_isNotNull
			if (constraints) { errorMessage = this.test_isNotNull(valeur, constraints, index_val) }
			// 2-test_isNumber
			if (constraints && errorMessage == "") { errorMessage = this.test_isNumber(valeur, constraints, index_val) }
			// 3-test_isString
			if (constraints && errorMessage == "") { errorMessage = this.test_isString(valeur, constraints, index_val) }

			if (errorMessage !== "") { return this.tError; }
		}
		return this.tError;
	}
	test_isNotNull(valeur, constraint, index_val) {
		let errorMessage = "";
		let valeurIsNull = (typeof (valeur) == null || typeof (valeur) == "undefined" || valeur == "");
		//1-text IsNull
		if (constraint[1]) { errorMessage = (constraint[1] == "notNull" && !valeurIsNull) || (constraint[1] !== "notNull") ? "" : this.tMessages["error_IsNull"]; }
		//2-Formater le message
		if (errorMessage !== "") {
			this.tError.message = errorMessage = this.tMessages["error_0"] + " " + constraint[0] + " " + index_val.toString() + " " + errorMessage;
			this.tError.nomChamp = "form_" + constraint[0];
		}
		return errorMessage;
	}
	test_isString(valeur, constraint, index_val) {
		let errorMessage = "";
		let excludedSystem = ["!!!", "§§§"];
		if (constraint[2] === "string") {
			if (Number(valeur)) { errorMessage = this.tMessages["error_isNotString"]; }
			// no error la variable est une chaine 
			//1-test minLength
			if (constraint[3] && errorMessage == "") { errorMessage = valeur.length < constraint[3] ? this.tMessages["error_IsLenthMin"] + " " + constraint[3].toString() : ""; }
			//2-test maxLength
			if (constraint[4] && errorMessage == "") { errorMessage = valeur.length > constraint[4] ? this.tMessages["error_IsLenthMax"] + " " + constraint[4].toString() : ""; }
			//3-test format constraint[5] 
			if (constraint[5] && errorMessage == "") { let regex = new RegExp(constraint[5]); errorMessage = regex.test(valeur) ? "" : this.tMessages["error_format"] + " " + constraint[5]; }

			//4-test exclu [] 
			if (constraint[6] && errorMessage == "") {
				for (let j = 0; j < constraint[6].length; j++) {
					if (constraint[6][j] && errorMessage == "") { errorMessage = valeur.indexOf(constraint[6][j]) == -1 ? "" : this.tMessages["error_excluded"] + constraint[6][j]; }
				}
			}
			//5-test excludedSystem
			if (errorMessage == "") {
				for (let j = 0; j < excludedSystem.length; j++) {
					if (excludedSystem[j] && errorMessage == "") { errorMessage = (valeur.indexOf(excludedSystem[j]) == -1) ? "" : this.tMessages["error_excludedSystem"] + excludedSystem[j]; }
				}
			}
			//6-Formater le message
			if (errorMessage !== "") {
				this.tError.message = errorMessage = this.tMessages["error_0"] + " " + constraint[0] + " " + index_val.toString() + " " + errorMessage;
				this.tError.nomChamp = "form_" + constraint[0];
			}
		}
		return errorMessage;
	}
	test_isNumber(valeur, constraint, index_val) {
		let errorMessage = "";
		if (constraint[2] === "number") {
			if (!Number(valeur)) { errorMessage = this.tMessages["error_IsNotNumber"]; }
			//1-test numberIsMin
			if (constraint[3] && errorMessage == "") { errorMessage = valeur < constraint[3] ? this.tMessages["error_numberIsMin"] + " constraint " + constraint[3].toString() : ""; }
			//2-test numberIsMax
			if (constraint[4] && errorMessage == "") { errorMessage = valeur > constraint[4] ? this.tMessages["error_numberIsMax"] + " constraint " + constraint[4].toString() : ""; }
			//3-Formater le message
			if (errorMessage !== "") {
				this.tError.message = errorMessage = this.tMessages["error_0"] + " " + constraint[0] + " " + index_val.toString() + " " + errorMessage;
				this.tError.nomChamp = "form_" + constraint[0];
			}
		}
		return errorMessage;
	}

}





