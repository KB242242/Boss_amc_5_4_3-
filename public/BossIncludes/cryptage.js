               function encodeChaine(chaine,clePublique){
				   t= clePublique.split(";"); 
				   A = parseInt(t[0]);
				   B = parseInt(t[1]);
                   var retour ="";
				   var code = "";
				   var valeur= 0;
                   for (var i=0; i<chaine.length; i++) {
					   valeur = chaine.charCodeAt(i);
					   code = ((valeur*A)+B).toString();
                        retour += code + ';';
                    }
                    return retour;
               }