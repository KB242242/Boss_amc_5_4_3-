<?php
		return	[
					#	module 	30_activity
					#	section 100_tache
					#	refNode 30_100_0
					#	node	tache
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
					#regle de visu non_def
					# droits_groupe_user = "tout_le_monde"
					"cmdeOutilsPage"	=>[
											["ged",[],["grp_dg",""],"#FFF8DC","#34495E","txt_bt_1"],
											["note",[],["grp_dg",""],"#FFF8DC","#34495E","txt_bt_2"],
											["activite",[],["","100;101"],"#FFF8DC","#34495E","txt_bt_3"],
											["kpi",[],["tout_le_monde","102;101"],"#FFF8DC","#34495E","txt_bt_4"],
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
					#"cmdeConnexPage"		=>[[id],[regle de visu],[droits_groupe_user_1, ...;droits_user_1,... ],[color],[backColor],[txt_button]]
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
					#--------------------------------------------------------------------------------
					#-Recuperation de l'entite
					# element de la ligne de contetexte qui contient l'identifiant de l'entite
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
					# nbColums utitilisé dans node.js = n+1 a confirmer
					"nbColumns"					=> 10,

					# Label colonnes
					# titre des colonnes
					"dataTableColumns"	 		=>["txt_col_1","txt_col_2","txt_col_3","txt_col_4","txt_col_5","txt_col_6","txt_col_7","txt_col_8","txt_col_9"],
					"maxSel"       				=>5,

					#Acces page refusé
					"erreur_notAutorizedUser"	=>"warning_0_0",

					# Appel ajax dataTable
					"dataTableAjax" 			=> "1_400_5",
				];
