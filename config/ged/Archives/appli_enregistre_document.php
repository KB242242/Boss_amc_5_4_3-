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
					"cmdeConnexPage"		=>[],
					"cmdeContextGenePage"	=>[
						["reserveDocuments",[],["","104;100"],"#FFF8DC","#008080","txt_bt_3_2"],
						["rechercheDocument",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_3"],
						["adminDocument",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_4"],
						["disconnect",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_5"]
											],
					"cmdeContextParticulierPage"	=>[],
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
					"dataTableColumns"	 			=>["txt_col_1","txt_col_2","txt_col_3","txt_col_4","txt_col_5"],
					"maxSel"       					=>5,

					#Acces page refusé
					"erreur_notAutorizedUser"	=>"warning_0_0",

					# Appel ajax dataTable
					"dataTableAjax" 			=> "20_120_2",


		];