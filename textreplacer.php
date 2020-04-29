			<?php 
			//text lines replacing
			if ($textrcondition == 0) {$entrycontent = str_replace(";seDpNL#", "<br />", $entrycontent);}
			elseif ($textrcondition == 1) {$entrycontent = nl2br($entrycontent);}
			
			//smilies code replacing
			$smilies = array(array(":)", "smiley", "Smiley"),
							 array(";)", "wink", "Wink"),
							 array(":D", "cheesy", "Cheesy"),
							 array(";D", "grin", "Grin"),
							 array(";(", "angry", "Angry"),
							 array(":(", "sad", "Sad"),
							 array(":o", "shocked", "Shocked"),
							 array("8)", "cool", "Cool"),
							 array("???", "huh", "Huh"),
							 array(":-)", "rolleyes", "Roll Eyes"),
							 array(":P", "tongue", "Tongue"),
							 array(":-[", "embarrassed", "Embarrassed"),
							 array(":-X", "lipsrsealed", "Lips Sealed"),
							 array(":-/", "undecided", "Undecided"),
							 array(":-*", "kiss", "Kiss"),
							 array(":*(", "cry", "Cry"));
			foreach ($smilies as $smily) {
			$entrycontent = str_replace($smily[0], "<img src=\"/users/smilies/{$smily[1]}.gif\" alt=\"{$smily[2]}\">", $entrycontent);}
			
			//email smilies full url:
			if ($emailcondition == true) {$entrycontent = preg_replace("`<img src=\"/users/smilies/(.+?)\.gif\" alt=\"(.+?)\">`is", "<img src=\"http://netfriending.co.cc/users/smilies/\\1.gif\" alt=\"\\2\">", $entrycontent);}
			
			//bbcodes replacing below
			$bbcodes = array('`\[b\](.+?)\[/b\]`is', 
							 '`\[i\](.+?)\[/i\]`is', 
							 '`\[u\](.+?)\[/u\]`is', 
							 '`\[s\](.+?)\[/s\]`is', 
							 '`\[color=#([a-z0-9]{6})\](.+?)\[/color\]`is', 
							 '`\[email\](.+?)\[/email\]`is', 
							 '`\[img\](.+?)\[/img\]`is', 
							 '`\[url=([a-z0-9]+://)([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\](.*?)\[/url\]`si', 
							 '`\[url\]([a-z0-9]+?://){1}([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)\[/url\]`si', 
							 '`\[url\]((www|ftp)\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\[/url\]`si', 
							 '`\[flash=([0-9]+),([0-9]+)\](.+?)\[/flash\]`is', 
							 '`\[quote\](.+?)\[/quote\]`is', 
							 '`\[size=([1-6]+)\](.+?)\[/size\]`is'); 
			
			$htmlcodes = array('<b>\\1</b>', 
							   '<i>\\1</i>', 
							   '<u>\\1</u>', 
							   '<strike>\\1</strike>', 
							   '<font color="\1">\2</font>', 
							   '<a href="mailto:\1">\1</a>', 
							   '<img src="\1" alt="" style="border:0px; max-width: 240px; width: expression(this.width > 240 ? 240: true); max-height: 600px; height: expression(this.height > 600 ? 600: true);" />', 
							   '<a href="\1\2" target="_blank">\6</a>', 
							   '<a href="\1\2" target="_blank">\1\2</a>', 
							   '<a href="http://\1">\1</a>', 
							   '<object width="\1" height="\2"><param name="movie" value="\3" /><embed src="\3" width="\1" height="\2"></embed></object>', 
							   '<strong>Quote:</strong><div style="margin:0px 0px; padding:5px; background-color:#f7f7f7; border:1px dotted #cccccc; width:90%;"><i>\1</i></div>', 
							   '<font size="\1">\2</font>');
			
			$entrycontent = preg_replace($bbcodes, $htmlcodes, $entrycontent);
			?>