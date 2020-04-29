			<link rel="stylesheet" href="/navmenu/menu.css" type="text/css" />
			<script type="text/javascript" src="/navmenu/menu.js"></script>
			
			<!-- START TOP NAV -->
			<?php $username = $_SESSION['nfusername'];
			require("../mysqlconnection.php");
			$result = mysql_query("SELECT userinfo.*, usercus.* FROM userinfo, usercus WHERE (userinfo.username='$username' AND usercus.username='$username')");
			$row = mysql_fetch_array($result);
			$displayname = $row['displayname'];
			$onlinetime = date("Y-m-d H:i:s");
			$insert = mysql_query("UPDATE userinfo SET onlinetime='$onlinetime' WHERE username='$username'");
			$read = mysql_query("SELECT id FROM privatemessages WHERE username='$username' AND type='inbox' AND flag='unread'");
			$newpm = mysql_num_rows($read);
			if ($newpm > 0) {$newpm1 = " ($newpm)";}
			$read = mysql_query("SELECT id FROM friendlist WHERE user='$username' AND status='pending'");
			$newrequest = mysql_num_rows($read);
			if ($newrequest > 0) {$newrequest1 = " ($newrequest)";}
			
			$user_forum = false;
			$url = parse_url($_SERVER['PHP_SELF'], PHP_URL_PATH);
			if ($url == "/forum/index.php" or $url == "/forum/forumstats.php" or $url == "/forum/threads.php" or $url == "/forum/userslocation.php" or $url == "/forum/viewthread.php" or  $url == "/forum/usersthreads.php") {$user_forum = true;
			$read = mysql_query("SELECT * FROM forum WHERE username='$username' AND (threadattribute='topthread' OR threadattribute='conthread')");
			$posts = mysql_num_rows($read);
			include("../forum/postsclassing.php");
			$read = mysql_query("SELECT * FROM forum WHERE threadattribute!='votethread' AND username='$username' ORDER BY threaddate DESC LIMIT 1");
			$lastpost = mysql_fetch_array($read);}
			if ($user_forum == true) {echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
			<tr><td align=left valign=top>";}?>
			<div class="menu">
			<ul id="nav">
				<li class="first" style="width:59px;">
					<a href="/netfriending/users/index.php">INDEX<!--[if IE 7]><!--></a><!--<![endif]-->
					<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul>
						<li><a href="/netfriending/users/profiles.php?user=<?php echo $username;?>">View My Profile</a></li>
						<li><a href="/netfriending/users/editprofile.php">Edit My Profile</a></li>
						<li><a href="/netfriending/users/editaccount.php">Edit My Account</a></li>
						<li><a href="/netfriending/users/editpreference.php">Edit Preferences</a></li>
					</ul>
					<!--[if lte IE 6]></td></tr></table></a><![endif]-->			
				</li>
				<li style="width:78px;">
					<a href="/netfriending/users/blog.php?user=<?php echo $username;?>" class="drop">MY BLOG<!--[if IE 7]><!--></a><!--<![endif]-->
					<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul>
						<li><a href="/netfriending/users/blogview.php">Blog-Admin</a></li>
					</ul>
					<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				<li style="width:120px;">
					<a href="/netfriending/users/gb.php?user=<?php echo $username;?>" class="drop">MY GUESTBOOK<!--[if IE 7]><!--></a><!--<![endif]-->
					<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul>
						<li><a href="/netfriending/users/gbview.php">Guestbook-Admin</a></li>
					</ul>
					<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				<li style="width:85px;">
					<a href="/netfriending/users/photo.php?user=<?php echo $username;?>" class="drop">MY PHOTO<!--[if IE 7]><!--></a><!--<![endif]-->
					<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul>
						<li><a href="/netfriending/users/photoview.php">Photos-Admin</a></li>
					</ul>
					<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				<li style="width:59px;">
					<a href="/netfriending/users/index.php" class="drop">EXTRA<!--[if IE 7]><!--></a><!--<![endif]-->
					<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul>
						<li><a href="/netfriending/users/friends.php">My Friends List<?php echo $newrequest1;?></a></li>
						<li><a href="/netfriending/users/pm.php">My Messages<?php echo $newpm1;?></a></li>
						<li><a href="/netfriending/users/pmsend.php">Send Messages</a></li>
						<li><a href="/netfriending/users/search.php">Search Member</a></li>
					</ul>
					<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				<li class="last" style="width:59px;">
						<a href="/forum/" class="drop">FORUM<!--[if IE 7]><!--></a><!--<![endif]-->
					<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul>
						<li><a href="/forum/usersthreads.php?user=<?php echo $username;?>">My Forum Posts</a></li>
					</ul>
					<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
			</ul>
			</div>
			<!-- END TOP NAV -->
			
			<br /><table width="470px" cellspacing="0" cellpadding="0" border="0">
			<tr class="sub_nav"><td nowrap="nowrap" align=left width="80%">
			WELCOME! <?php echo $displayname;?> (<?php echo $username;?>)
			<td nowrap="nowrap" align=right width="100%">
			<a href = "/netfriending/users/login.php?do=logout">LOGOUT</a>
			</td></tr></table>
			<br />
			<?php if ($user_forum == true) {echo "<h4>Forum Disscusion</h4></td>
			<td align=right valign=top>
			<table width=\"178\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"margin-right: 10px; margin-top: 15px;\">
			<tr height=\"20\" bgcolor=\"#B6D3F7\" style=\"background-image: url('/navmenu/nav_top_backg_off.gif');\">
			<td style=\"color: #2B449C; font-size: 11px; font-weight: bold; text-align: center;\">FORUM DETAILS</td></tr>
			<tr height=\"60\" bgcolor=\"#3873AD\" class=\"sub_nav\">
			<td valign=top align=left style=\"padding-left: 10px; padding-top: 6px;\"><font style=\"font-size: 10px; letter-spacing: 0px\">
			<b>POSTS:</b> $posts<br />
			<b>POSITION:</b> $level<br />
			<b>LATEST POST:</b> <a href=\"/forum/viewthread.php?threadid={$lastpost['threadid']}\">{$lastpost['threadtitle']}</a><br />
			</font></td></tr>
			</table>
			</td></tr></table>";}?>