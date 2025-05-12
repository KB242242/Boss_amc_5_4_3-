<?php
		return	[
					#	module 	20-ged
					#	section 100-menu_principal
					#	refNode 20_100_0

					"timeOutPage"	=> 1200000,
					# 1-Config page

					# 1-Elements du menu
					# 2-notifications

					# 3-boutons outillage  "tout_le_monde"
					# 4-Titre & commentaires
					# 5-Boutons de cmde
					"cmdeConnexPage"		=>[],
					"cmdeContextGenePage"	=>[
						["reserveDocuments",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_1"],
						["enregistreDocument",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_2"],
						["rechercheDocument",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_3"],
						["adminDocument",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_4"],
						["disconnect",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_5"]
											],
					"cmdeContextParticulierPage"	=>[],
					# 6-DataTable
					"nbColumns"					=> 0,

					# Label colonnes
					"dataTableColumns"	 		=>[],
					"maxSel"       				=>0,

					#Acces page refusé
					"erreur_notAutorizedUser"	=>"",

					# Appel ajax dataTable
					"dataTableAjax" 			=> "",
				];
