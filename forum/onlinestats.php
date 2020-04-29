			<?php //get page anyways.
			$page = $_SERVER['PHP_SELF'];
			$url1 = explode("/", $page);
			if ($url1[2] == "threads.php") {$category1 = $_GET['cat']; $page = "threads.php?cat=$category1";}
			elseif ($url1[2] == "viewthread.php") {$threadid1 = $_GET['threadid']; $page = "viewthread.php?threadid=$threadid1";}
			else {$page = $url1[2];}
			
			//if username is not empty.
			if ($username != "") {
			$users = file("onlinestats/usernameusers.txt");
			$datetime = date("Y-m-d H:i:s");
			foreach ($users as $user) {$data = explode(",", $user);
			$userlist[] = $data[0];
			if ($username == $data[0]) $userid = "exist";}
			if ($userid == "exist") {$line = array_keys($userlist, $username);
			$users[$line[0]] = "$username,$datetime,$page,\n";
			$handle = fopen("onlinestats/usernameusers.txt", "w");
			foreach ($users as $val) {$write = fwrite($handle, $val);}
			fclose($handle);}
			else {$file = fopen("onlinestats/usernameusers.txt","a");
			$write = fwrite($file,"$username,$datetime,$page,\n");
			fclose($file);}}
			//if username is empty, then guest record.
			else {
			$ips = file("onlinestats/ipguests.txt");
			$guestip = $_SERVER['REMOTE_ADDR'];
			$datetime = date("Y-m-d H:i:s");
			foreach ($ips as $ip) {$data = explode(",", $ip);
			$iplist[] = $data[0];
			if ($guestip == $data[0]) $guest = "old";}
			if ($guest == "old") {$line = array_keys($iplist, $guestip);
			$ips[$line[0]] = "$guestip,$datetime,$page,\n";
			$handle = fopen("onlinestats/ipguests.txt", "w");
			foreach ($ips as $val) {$write = fwrite($handle, $val);}
			fclose($handle);}
			else {$file = fopen("onlinestats/ipguests.txt","a");
			$write = fwrite($file,"$guestip,$datetime,$page,\n");
			fclose($file);}}
			?>