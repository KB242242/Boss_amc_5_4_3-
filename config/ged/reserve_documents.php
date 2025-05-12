<?php
		return	[
					#	module 	20-GED
					#	section 110-reserve_document
					#	node	reserve_documents
					#	refNode	20_110_0

					'timeOutPage' => 1200000,

					"titre"			 =>"Formulaire enregistrement de document ",
					"sousTitre"		 =>"",
					"commentaireTitre" => "Commentaires",
					"commentaire" => "This will create a button with a rounded shape. You can also add additional classes to customize the appearance of the button, such as `btn-round-lg` for a larger size or `btn-round-secondary` for a secondary color.
									If you want to create a button with a custom shape, you can use the `btn-*` classes and then use CSS to modify the shape. For example:",

					#20_110_0 Contraintes fichiers
					"contraintes_fichiers" =>["txt;xlxs;png;pdf;jpeg","80000"],

					# 5-Boutons de cmde
					"cmdeConnexPage"		=>[],
					"cmdeContextGenePage"	=>[
						["enregistreDocument",[],["","104;100"],"#FFF8DC","#008080","txt_bt_3_2"],
						["rechercheDocument",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_3"],
						["adminDocument",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_4"],
						["disconnect",[],["","100;101"],"#FFF8DC","#008080","txt_bt_3_5"]
											],
					"cmdeContextParticulierPage"	=>[],

					# 6-DataTable
					"nbColumns"						=> 5,

					# Label colonnes
					"dataTableColumns"	 			=>["txt_col_1","txt_col_2","txt_col_3","txt_col_4","txt_col_5"],
					"maxSel"       				=>5,

					#Acces page refusé
					"erreur_notAutorizedUser"	=>"warning_0_0",

					# Appel ajax dataTable
					"dataTableAjax" 			=> "20_110_2",


		];