<?php
		return	[
					#	module 	20-ged
					#	section 120-enregistre_document
					#	node	enregistre_prepar_form_ajax
					#	refNode	20_120_1
					
					#index colonne de l'identiant du fichier dans le contexte selectionne
					"indexColumn" => 0,
					#params document
					"hebergement" => 100,
					"gisement" => "../public/Ged/fichiersReserves/",
					
					#requete selection
					"sql_sel"	=> " SELECT * FROM ged_reservation  WHERE ref_doc = ",  

                    #requete de suppression des donnees de la table ged_reservation
					"sql_supp"	=> " DELETE FROM ged_reservation WHERE ref_doc  = :ref_doc"
				];
