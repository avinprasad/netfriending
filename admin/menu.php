			<link rel="stylesheet" href="/navmenu/menu.css" type="text/css" />
			<script type="text/javascript" src="/navmenu/menu.js"></script>
			
			<!-- START TOP NAV -->
			<div class="menu">
			<ul id="nav">
				<li class="first" style="width:64px;">
					<a href="index.php">INDEX</a>			
				</li>
				<li style="width:140px;">
					<a href="index.php" class="drop">PUBLIC VISITORS<!--[if IE 7]><!--></a><!--<![endif]-->
					<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul>
						<li><a href="newsentry.php">News Entry</a></li>
						<li><a href="faqlist.php">FAQ Manager</a></li>
						<li><a href="searchdb.php">Search DB</a></li>
					</ul>
					<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				<li style="width:125px;">
					<a href="index.php" class="drop">PRIVATE USERS<!--[if IE 7]><!--></a><!--<![endif]-->
					<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul>
						<li><a href="usersstats.php">User Statistics</a></li>
					</ul>
					<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				<li style="width:68px;">
					<a href="index.php" class="drop">OTHERS<!--[if IE 7]><!--></a><!--<![endif]-->
					<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul>
						<li><a href="textedit.php">Text Editor</a></li>
						<li><a href="webmail.php">Webmail Manager</a></li>
					</ul>
					<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				<li class="last" style="width:65px;">
					<a href="index.php" class="drop">FORUM<!--[if IE 7]><!--></a><!--<![endif]-->
					<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul>
						<li><a href="forumedit.php">Thread Editor</a></li>
					</ul>
					<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
			</ul>
			</div>
			<!-- END TOP NAV -->
			
			<br /><table width="470" cellspacing="0" cellpadding="0" border="0">
			<tr class="sub_nav"><td nowrap="nowrap" align=left width="80%">
			WELCOME! NF Admin (<?php echo $_SERVER['PHP_AUTH_USER'];?>)
			<td nowrap="nowrap" align=right width="100%">
			<a href = "/">HOME</a>
			</td></tr></table>
			<br />
			<a name="Admin Panel"></a>
			<h1>Admin Panel</h1>