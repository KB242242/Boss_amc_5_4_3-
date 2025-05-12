<?php
		return	[
					#	module 	20-GED
					#	section 120-enregistre_document
					#	node	enregistre_document
					#	refNode	20_120_0
					#===============================================================================
					# System

					'timeOutPage' => 1200000,
					# 1-Elements du menu
					"menu_1"	 =>"txt_1_0",
					"contactsClient" =>"txt_2_0",

					# 2-notifications
					"alarm.echecDetectionAppelRouteur_0"=>"warning_1_0",
					#===============================================================================
					# 3-boutons outillage  "tout_le_monde"

					#--------------------------------------------------------------------------------
					# 4-Titre & commentaires

					"titre"			 =>"Formulaire enregistrement de document ",
					"sousTitre"		 =>"",
					"commentaireTitre" => "Commentaires",
					"commentaire" => "Un commentaire",


					#--------------------------------------------------------------------------------
					# 5-Boutons de cmde
					# "cmdePage"		=>[[id],[droits_groupe_user_1, ...;droits_user_1,... ],[color],[backColor],[txt_button]]
					# config_button: [id]
					"cmdeConnexPage"				=>[], 
					"cmdeContextGenePage"			=>[
						["reserveDocuments",[],["","104;100"],"#FFF8DC","#008080","txt_bt_3_2"],
						["enregistreDocument",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_3"],
						["adminDocument",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_4"],
						["disconnect",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_5"]
											],
					"cmdeContextParticulierPage"	=>[
												["edit_doc",[],["","100;101"],"#FFF8DC","#008080","txt_bt_4_1"],
												["gestion_doc",[],["","100;101"],"#FFF8DC","#008080","txt_bt_5_1"],
												["supp_doc",[],["","100;101"],"#FFF8DC","#008080","txt_bt_6_1"]
														],
					#------------------------------------------------------------------------------
					#
					"cmdeContextEditPage"			=>[
						["reserveDocuments",[],["","104;100"],"#FFF8DC","#008080","txt_bt_3_2"],
						["enregistreDocument",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_3"],
						["adminDocument",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_4"],
						["disconnect",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_5"]
											],
					"cmdeEditPage"	=>[
							["downLoad",[],["","100;101"],"#FFF8DC","#008080","txt_bt_7_1"],
							["retour",[],["","100;101"],"#FFF8DC","#008080","txt_bt_9_1"]

									],

					#===============================================================================
					# 6-Formulaire	
						#recup entite dans le context
					'indexColumn' =>"0",	
						#listes de choix
					'listeChoix' => [],
					#===============================================================================
					# 6-DataTable
					"nbColumns"						=> 5,

					# Contraintes extension fichiers
					 #"contraintes_fichiers" =>["txt;xlxs;png;pdf;jpeg","80000"],

					# Label colonnes
					"dataTableColumns"	 			=>["txt_col_1","txt_col_3","txt_col_5","txt_col_6"],
					"maxSel"       					=>5,

					#Acces page refusé
					"erreur_notAutorizedUser"	=>"warning_0_0",

					# Appel ajax dataTable
					"dataTableAjax" 			=> "20_130_3",


		];