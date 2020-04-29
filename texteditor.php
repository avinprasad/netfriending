			<?php //maximum number reached: 6
			foreach ($entrycontent as $entryarray) {
			list($textecondition, $text) = $entryarray;
			//for mysql_db only:
			if ($textecondition == 2 or $textecondition == 3) {
			
			//addslashes:
			$text = addslashes($text);}
			
			//for txtfiles and email fields:
			if ($textecondition == 0 or $textecondition == 1 or $textecondition == 6) {
			
			//stripslashes:
			$text = stripslashes($text);
			
			//only for txtfiles:
			if ($textecondition == 0 or $textecondition == 1) {
			//textfile newline, separate:
			$text = str_replace(";seDp#",";sedp#",$text);
			//only for txtfiles messages:
			if ($textecondition == 1) {
			$text = str_replace(";seDpNL#",";sedpnl#",$text);
			$text = str_replace("\r\n", ";seDpNL#", $text);}}}
			
			//close extra tags, only for messages:
			if ($textecondition == 1 or $textecondition == 3) {
			$tags = array("[b]" => "[/b]", 
						  "[i]" => "[/i]", 
						  "[u]" => "[/u]", 
						  "[s]" => "[/s]", 
						  "[email]" => "[/email]", 
						  "[img]" => "[/img]", 
						  "[url]" => "[/url]", 
						  "[quote]" => "[/quote]");
			foreach ($tags as $stag => $etag) {
			while (substr_count($text, $stag) > substr_count($text, $etag)) {
			$text = $text.$etag;}}}
			
			//if special fields, username, first name or last name:
			if ($textecondition == 4 or $textecondition == 5) {
			$text = strtolower($text);
			if ($textecondition == 5) {
			$text = ucwords($text);}}
			
			//html special characters to normal characters, for all:
			$text = htmlspecialchars($text);
			
			$finalcontent[] = $text;}
			?>