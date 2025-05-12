    //declaration des variables globales
	
	function controle_saisies (champ,format){
		// caracteres exotiques predefinis
		var exotiques =" \" + - ° ' & ? / \ { } ! § ¤ $ £ µ : ; < > [ ] # ~"
		var alphabet = "a b c d e f g h i j k l m o p q r s t u v w x y z A B C D E F G H I J K L M N O P Q R S T U V W X Y Z  
		var retour=0;
		var trimchamp = champ.trim();
		var trimformat= format.trim();
		var f= trimformat.split(";");
		var message;
		var msg = new Array();
		
		switch(f[0]) {
			case "":
			// pas de definition de format
			message='0';
			break;
			case "string":
				// (string;exclus;max;isNull) //chaine de caracteres (tous), taille max 
				// si le mot clé 'exotiques' est contenu dans la chaine d'exlusion, on remplace le mots clés parl'ensemble
				// des caracteres exotiques predefinis.
				exclusion = inExclus("exotiques",f[1])
				exclusion = inExclus("alphabet",f[1])
				max=f[2];
				nullable = f[3].trim();
				
				msg[0]= '0';
				msg[1] = "Le champ ne doit pas être vide !";
				msg[2] = "les caracteres exotiques " + exotique + " ne sont pas admis!"
				msg[3] = "Le champ ne doit pas contenir plus de "+ max + " caractères!";
				
				// prepa des variables
				isNotNull = chaineEgale(nullable,"vide")? false:true;
				taille = trimchamp.length;
				// test is null
				retour = (taille==0 && isNotNull)?1:0;
				if (isNotNull){
					// test exclusion des caracteres exotiques
					retour = (retour==0 && isInclus(exclusion,trimchamp) )?2:retour;
					// test taille
					retour = (retour==0 && (taille > max) )?3:retour;				
				}
				
				message=msg[retour];
			break;
			case "integer":
			// nombre entier compris entre 2 valeurs 
			(integer;min;max;isVide)
				min = f[1];
				max = f[2];
				nullable = f[3];
				
				msg[0]= '0';
				msg[1] = "Le champ ne doit pas être vide !";
				msg[2] = "Le champ doit être un nombre entier !";
				msg[3] = "La valeur doit être comprise entre (" + min + " et "+  max +")"
				
				// prepa des variables
				isNotNull = chaineEgale(nullable.trim(),"vide")? false:true;
				taille = trimchamp.length;
				// test is null
				retour = (taille==0 && isNotNull)?1:0;
				if (isNotNull){
					//test integer
					retour = (retour=0 && validateInteger(trimchamp))?2:retour;
					//test valeur
					retour = (retour=0 && (trimchamp > max || trimchamp < min)?3:retour;
				}
				message=msg[retour];
			break;
			case "float":
			//float; min; max; isVide
				min = f[1];
				max = f[2];
				nullable = f[3];
				
				msg[0]= '0';
				msg[1] = "Le champ ne doit pas être vide !"; 
				msg[2] = "Le champ doit être decimal avec un point pour separateur !";
				msg[3] = "La valeur doit être comprise entre (" + min + " et "+  max +")"
				
				// test is null
				isNotNull = chaineEgale(nullable.trim(),"vide")? false:true;
				taille = trimchamp.length;
				retour = taille==0 && isNotNull ?1:0;
				// test is float
				if(retour==0 && taille > 0 ){retour = (validateFloat(trimchamp)&& !validateInteger(trimchamp))?0:2;}
				message=msg[retour];
			
			break;
			case "phone":
				nullable = f[1];
				
				msg[0]= '0';
				msg[1] = "Le champ ne doit pas être vide !"; 				
				msg[2] = "Le format telephone n'est pas valide!";

				// test is null
				isNotNull = chaineEgale(nullable.trim(),"vide")? false:true;
				taille = trimchamp.length;
				retour = taille==0 && isNotNull ?1:0;
				// test is phone
				if(retour==0 && taille > 0){retour = validatePhoneCongo(trimchamp)?0:2;}
				message=msg[retour];
			break;
			case "mail":
				nullable = f[1];

				msg[0]= '0';
				msg[1] = "Le champ ne doit pas être vide !"; 								
				msg[2] = "Le format mail n'est pas valide!";
				// test is null
				isNotNull = chaineEgale(nullable,"null")? false:true;
				taille = trimchamp.length;
				retour = taille==0 && isNotNull ?1:0;
				// test is mail
				
				if(retour==0 && taille > 0){retour = validateEmail(trimchamp)?0:2;}
				message=msg[retour];
			break;
			case "date":
				nullable = f[1];

				msg[0]= '0';
				msg[1] = "Le champ ne doit pas être vide !"; 								
				msg[2] = "Le format date n'est pas valide!";
				// test is null
				isNotNull = chaineEgale(nullable.trim(),"vide")? false:true;
				taille = trimchamp.length;
				retour = taille==0 && isNotNull ?1:0;
				// test is date
				if(retour==0 && taille > 0){retour = validateDate(trimchamp)?0:2;}
				message=msg[retour];
			break;
				case "hh:mn":
				nullable = f[1];

				msg[0]= '0';
				msg[1] = "Le champ ne doit pas être vide !"; 								
				msg[2] = "Le format heure n'est pas valide!";
				// test is null
				isNotNull = chaineEgale(nullable.trim(),"vide")? false:true;
				taille = trimchamp.length;
				retour = taille==0 && isNotNull ?1:0;
				// test is heure
				if(retour==0 && taille > 0){retour = validateHHMN(trimchamp)?0:2;}
				message=msg[retour];
			break;
		}
	
		return message;
	}
	
	function inExclus(exclus, chaine){
		// ajoute l'ensemble des caracteres exotiques dans la chaine d'exlusion si le mot exotiques est dans la chaine d'exclusion
		var f= exclus.split(" ");
		var retour = "";
		for (var i = 0; i < f.length; i++) {
				xx = (chaineEgale("exotiques", f[i]))? exotiques:(f[i]);
				retour += " " + xx
		}	
		// supprime le premier espace
		retour = retour.trim()
		return retour;
	}
	function isInclus(exclus, chaine){
		// retour true si un caractere exclus est present dans la chaine, sinon false
		// les caracteres ou les mots exlus sont separes par des espaces
		// si un des caracteres appartient a la chaine, la reponse est true
		var f= exclus.split(" ");
		var retour = false;
		for (var i = 0; i < f.length; i++) {
				xx= f[i];
				yy = chaine.indexOf(xx,0);
				if(yy > -1){
					retour = true;
					break;
				}
		}
		return retour;
	}	
	function teste_inclus(Inclus, chaine){
		// chaque caratere ou mot séparés par ";" doit être inclus dans la chaine
		// si un des mots n'appartient pas a la chaine, la reponse est false

		var f= Inclus.split(";");
		var retour = true;
		for (var i = 0; i < f.length; i++) {
				xx= f[i];
				yy = chaine.indexOf(xx,0);
				if(yy == -1){
					retour = false ;
					break;
				}
		}
		return retour;
	}

	function chaineEgale(chaine1, chaine2){
		// teste l egalite de deux chaine 
		var retour = true;
		var tailleChaine1 = chaine1.length;
		var tailleChaine2 = chaine2.length;
		retour = (tailleChaine1 == tailleChaine2)?retour:false;
		if (retour){
			// si meme taille 
			for (i=0; i<tailleChaine1 ; i++){
				a =chaine1.charCodeAt(i);
				b=chaine2.charCodeAt(i);
				retour= (a == b)?retour:false;
				if (!retour)
					break;
			}
		}
		
		return retour;
	}
	function validateInteger(nombre) {
		var re = /^[+|-]?\d+$/ ; 
		return re.test(nombre);
	}
	 
	function validateFloat(nombre) {
		var re = /^[+|-]?[0-9]+(\.[0-9][0-9]?)?/;  
		return re.test(nombre);
		///^[0-9]+(.[0-9]+)?$/ ;
	}
	function validatePhoneCongo(phone) {
		var re = /^([0-9]{2}\s){3}[0-9]{3}$/; // ^([0-9]{2}){3} [0-9]{3} /;///^(\([0-9]{3}\) |[0-9]{3}-)[0-9]{3}-[0-9]{4}/;
		return re.test(phone);
	}
	function validateEmail(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	
	function validateDate(theDate) {
		var re = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
		return re.test(theDate);
	}
	function validateHHMN(hhmn) {
		var f= hhmn.split(":");
		retour = (f[0].length >0) && (f[0].length <3) && (f[1].length >0) && (f[1].length <3)?true:false;
		if(retour) {retour = (f[0]< 24)&& (f[1]< 60)? true:false;}
		return retour;
	}
	
	
