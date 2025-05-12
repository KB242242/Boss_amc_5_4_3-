<?php
		return	[
					#	module 	20-ged
					#	section 120-enregistre_document
					#	node	enregistre_data_table_ajax
					#	refNode	20_120_2
					
					#index colonne de l'identiant du fichier dans le contexte selectionne
					"indexColumn" => 0,
					#gisement du document
					"hebergement" => 100,
					"gisement" => "../public/Ged/fichiersReserves/",

					#requete selection
					"sql_sel"	=> "SELECT * FROM ged_reservation  WHERE valid = 1",  


				];
