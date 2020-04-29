<?php set_time_limit(0);
//Configuration Files:
//Username & Password:
$cpuser = "netfrie"; // Username used to login to CPanel
$cppass = "12345678"; // Password used to login to CPanel
//Domain & Skin
$domain = "netfriending.co.cc"; // Domain name where CPanel is run
$skin = "x3"; // Set to cPanel skin you use (script won't work if it doesn't match). Most people run the default x theme
//FTP Configuration:
$ftpuser = "netfrie@netfriending.co.cc"; // Username for FTP account
$ftppass = "12345678"; // Password for FTP account
$ftphost = "ftp.netfriending.co.cc"; // Full hostname or IP address for FTP host
$ftpmode = "homedir"; // FTP mode ("ftp" for active, "passiveftp" for passive)
$secure = 0; // Set to 1 for SSL (requires SSL support), otherwise will use standard HTTP
//Notification & Attachment send to:
$notifyemail = "ted_chou12@hotmail.com"; // Email address to send results
//Cron Log Debug Option:
$debug = 0; // Set to 1 to have web page result appear in your cron log
//Attachment Mail Subject:
$subject = "Backup Complete Attachment for Download";
//Attachment Mail Send E-mail:
$headers = "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
$headers .= "Reply-To: NF WebMaster <webmaster@netfriending.co.cc>" . "\r\n";
//Attachment File Path:
$filepath = "../../../";

//************Backup Script**************:
if ($secure) {$url = "ssl://$domain"; $port = 2083;}
else {$url = $domain; $port = 2082;}

$socket = fsockopen($url, $port);
if (!$socket) {echo "Failed to open socket of FTP connection... Bailing out!\n"; exit;}

//Encode authentication string
$authstr = "$cpuser:$cppass";
$pass = base64_encode($authstr);
$params = "dest=$ftpmode&email=$notifyemail&server=$ftphost&user=$ftpuser&pass=$ftppass&submit=Generate Backup";

//Make POST to cPanel
fputs($socket,"POST /frontend/$skin/backup/dofullbackup.html?$params HTTP/1.0\r\n");
fputs($socket,"Host: $domain\r\n");
fputs($socket,"Authorization: Basic $pass\r\n");
fputs($socket,"Connection: Close\r\n");
fputs($socket,"\r\n");

//Grab response even if we don't do anything with it.
while (!feof($socket)) {$response = fgets($socket, 4096);
if ($debug) {echo $response;}}

fclose($socket);
//***********Backup Script Ends*************
echo "BP Closed";
//***********Send Attachment By E-mail******
//Get File:
$connect = ftp_connect($ftphost);
ftp_login($connect, $ftpuser, $ftppass);
$contents = ftp_nlist($connect, ".");
foreach ($contents as $content) {if (end(explode(".", $content)) == "gz") {$filename = $content;}}
ftp_chmod($connect, 0644, $filename);
//Create boundary string and random hash:
$random_hash = md5(date("r", time())); 
$headers .= "Content-Type: multipart/mixed; boundary=\"PHP-mixed-$random_hash\"" . "\r\n";
//Read the atachment file contents into a string, encode it with MIME base64, and split it into smaller chunks:
$attachment = chunk_split(base64_encode(file_get_contents("$filepath$filename")));
//Define the body of the message. 
ob_start(); //Turn on output buffering
$date = date("m-d-Y");
$headers .= "--PHP-mixed-$random_hash 
Content-Type: multipart/alternative; boundary=\"PHP-alt-$random_hash\" 

--PHP-alt-$random_hash
Content-Type: text/plain; charset=\"iso-8859-1\"
Content-Transfer-Encoding: 7bit

This is an automatic cronjob generated E-mail for backup purposes!
The Backup file has been sent to you successfully!

--PHP-alt-$random_hash
Content-Type: text/html; charset=\"iso-8859-1\"
Content-Transfer-Encoding: 7bit

--PHP-alt-$random_hash-- 

--PHP-mixed-$random_hash  
Content-Type: application/zip; name=\"$date.tar.gz\"  
Content-Transfer-Encoding: base64  
Content-Disposition: attachment  

$attachment
--PHP-mixed-$random_hash-- ";//copy current buffer contents into $message variable and delete current output buffer
$message = ob_get_clean();
//send the email
$mail_sent = @mail($notifyemail, $subject, $message, $headers);
//Showing Result:
echo $mail_sent ? "Mail sent" : "Mail failed";
//Automatically deletes the backup file from the system:
ftp_delete($connect, $filename);
ftp_close($connect);?>