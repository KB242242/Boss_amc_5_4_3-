<?php
		return	[
					#	module 	20-ged
					#	section 110-enregistre_document
					#	node	reserve_data_table_ajax
					#	refNode	20_110_2

                    "sql_20_110_0"			=> " SELECT 
											id,
											dat_res,
											titre,
											extension,
											rem 
										FROM ged_reservation  
										WHERE valid = 1 AND status = 0  
										ORDER BY dat_res;"
				];
