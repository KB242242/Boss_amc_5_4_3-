<?php
		return	[
					#	module 	20-GED
					#	section 110-reserve_document
					#	node	reserve_add
					#	refNode	20-110-1

					'timeOutPage' => 1200000,

					"titre"			 =>"Formulaire enregistrement de document ",
					"sousTitre"		 =>"",
					"commentaireTitre" => "Commentaires",
					"commentaire" => "This will create a button with a rounded shape. You can also add additional classes to customize the appearance of the button, such as `btn-round-lg` for a larger size or `btn-round-secondary` for a secondary color.
									If you want to create a button with a custom shape, you can use the `btn-*` classes and then use CSS to modify the shape. For example:",

					#0_111_0 Contraintes fichiers
					"extensions_autorises" =>"txt;xlxs;png;pdf;jpeg;php",
					"taille_max_fichier" => 80000,
					#params fichier
					"hebergement" => 100,
					"gisement" => "../public/Ged/fichiersReserves/",

		];