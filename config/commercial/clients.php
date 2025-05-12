<?php
		return	[
					#	module 	1-commercial
					#	section 400-clients
					#	refNode 1_400_0
					#===============================================================================
					# System
					"timeOutPage"	=> 1200000,
					# 1-Config page

					# 1-Elements du menu
					"menu_1"	 =>"txt_1_0",
					"contactsClient" =>"txt_2_0",

					# 2-notifications
					"alarm.echecDetectionAppelRouteur_0"=>"warning_1_0",
					#===============================================================================
					# 3-boutons outillage  "tout_le_monde"
					#"cmdeConnexPage"		=>[[id],[regle de visu],[droits_groupe_user_1, ...;droits_user_1,... ],[color],[backColor],[txt_button]]
					#regle de visu non_def, droits_groupe_user_1 = "grp_dg"; droits_user_1="102,101"
					# droits_groupe_user = "tout_le_monde"
					"cmdeOutilsPage"	=>[
											["ged",[],["grp_dg",""],"#FFF8DC","#34495E","txt_bt_1"],
											["note",[],["","100"],"#FFF8DC","#34495E","txt_bt_2"],
											["activite",[],["","100;101"],"#FFF8DC","#34495E","txt_bt_3"],
											["kpi",[],["",""],"#FFF8DC","#34495E","txt_bt_4"],
											["annots",[],["grp_codir;grp_comm",""],"#000000","#FFC300","txt_bt_5"]
										],
					#--------------------------------------------------------------------------------
					# 4-Titre & commentaires
					"titre" 			=> "",
					"sousTitre"			=>"titre(Bonjour,identUser())",
					"commentaireTitre" 	=> "",
					"commentaire" 		=> "",
					#--------------------------------------------------------------------------------
					# 5-Boutons de cmde
					"cmdeConnexPage"		=>[
												["client_select",[],["","100;101" ], "#FFF8DC", "#0E6655","txt_bt_6"],
												["client_foo",	 [],["","100" ],  "#074C3C", "#E74C3C","txt_bt_7"]
											],
					"cmdeContextGenePage"	=>[
												["client_add",[],["","100"],"#FFF8DC","#2E8B57","txt_bt_8"]
											],
					"cmdeContextParticulierPage"	=>[
												["client_modif",[],["","100;101"],"#FFF8DC","#008080","txt_bt_9"],
												["client_supp",[],["","100;101"],"#FFF8DC","#008080","txt_bt_10"],
												["info_client",[],["","100;101"],"#FFF8DC","#008080","txt_bt_11"]
													],

					"cmdeConnexSelect"		=>[
												["client_select",[],["","100;101" ], "#FFF8DC", "#0E6655","txt_bt_6"]
											],

					"cmdeContextParticulierSelect"	=>[
												["client_sel",[],["","100;101"],"#FFF8DC","#008080","txt_bt_12"],
													],

					#--------------------------------------------------------------------------------
					#-Recuperation de l'entite depuis le contexte
					"indexColumn" => 0,
					#--------------------------------------------------------------------------------
					#-formulaire	listes de choix
					'listeChoix' => [
					"listeChoixPays" =>['lst_1_0'=>242,'lst_1_1'=>33,'lst_1_2'=>237],
					"listeChoixUid" =>['lst_2_0'=>0,'lst_2_1'=>1],
					"listeChoixLiensCommerciaux" => ['lst_3_0'=> 0,'lst_3_1' => 1, 'lst_3_2' => 2,'lst_3_3' => 3]],

					# Contraintes champs du formulaire
					# !! A confirmer
					"contraintes_champs" =>[
												["nom","null","string","","","",""],
												["ville","null","string","","","",""],
												["pays","null","string","","","",""],
												["addPostale","null","string","","","",""],
												["siteInternet","null","string","","","",""],
												["statusEntreprise","null","string","","","",""],
												["rem","null","string","","","",""],
											],

					#--------------------------------------------------------------------------------
					# 6-DataTable
					"nbColumns"					=> 1,
					"parentNbColumns"			=> 1,
					"selectNbColumns"			=> 10,

					# Label colonnes
					"dataTableColumns"	 		=>["txt_col_1","txt_col_2","txt_col_3","txt_col_4","txt_col_5","txt_col_6","txt_col_7","txt_col_8","txt_col_9"],
					"maxSel"       				=>5,

					#Acces page refusé
					"erreur_notAutorizedUser"	=>"warning_0_0",

					# Appel ajax dataTable
					"dataTableAjax" 			=> "1_400_5",

					# Requete SQL ajax dataTable
					"sql"			=> " SELECT 
											id,
											nom,
											add_postale,
											ville,
											pays,
											site_internet,
											uid_val,
											activite,
											client_fourniss,
											rem 
										FROM annuaire_entreprise aent 
										WHERE valid = 1 AND (client_fourniss = '1' OR client_fourniss = '3') 
										ORDER BY nom;"
					#--------------------------------------------------------------------------------


				];
