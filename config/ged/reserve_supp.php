<?php
		return	[
					#	module 	20-ged
					#	section 110-enregistre_document
					#	node	reserve_supp
					#	refNode	20_110_3
					
					#index colonne de l'identiant du fichier dans le contexte selectionne
					"indexColumn" => 0,
					#requete selection
					"sql_sel"	=> " SELECT * FROM ged_reservation  WHERE ref_doc = ",  

                    #requete de suppression des donnees de la table ged_reservation
					"sql_supp"	=> " DELETE FROM ged_reservation WHERE ref_doc  = :ref_doc"
				];
