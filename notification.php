			<?php //Notification in entries:
			//##Notifies in general:
			//No entries (htmlcodes <p></p> not included):
			$db_noentries = "<b>Sorry, there are no entries in the $noty_subject right now!</b>";
			//DB error (htmlcodes <p></p> not included):
			$db_error = "<b>Sorry, the database is temporary down, please come back later!</b>";
			//Required fields left blank:
			$requiredfields = "<p><b>Required field(s) are left blank</b></p>";
			//Textarea exceeds 5000 characters:
			$textarea_exceed = "<p><b>Sorry, your message exceeded the limit, 5000 characters.</b></p>";
			//Image Verification Failed:
			$imgverify_failed = "<p><b>The text you entered does not match the string of the verification image, please try again!</b></p>";
			
			//##Specific Notifies-Orderly:
			//For register & edit account page (Repeatition Code=1):
			//Both pages:
			$passwords_diff = "<p><b>Passwords are not the same!</b></p>";
			$password_short = "<p><b>Your password is too short, please reconsider!</b></p>";
			$emails_diff = "<p><b>E-mails are not the same!</b></p>";
			$email_invalid = "<p><b>Invalid E-mail Address!</b></p>";
			$email_inuse = "<p><b>The E-mail you entered is already in use!</b></p>";
			$birthdate_invalid = "<p><b>Your birthdate entry is invalid!</b></p>";
			$age_tooyoung = "<p><b>You must be at least 13 years old to sign up!</b></p>";
			//Only registeration page:
			$username_short = "<p><b>Your username is too short, please reconsider!</b></p>";
			$username_invalid = "<p><b>Your username contains invalid characters!</b></p>";
			$username_taken = "<p><b>The username you entered is already taken!</b></p>";
			$fname_short = "<p><b>Your first name is too short, please reconsider!</b></p>";
			$fname_invalid = "<p><b>Your first name contains invalid characters!</b></p>";
			$lname_short = "<p><b>Your last name is too short, please reconsider!</b></p>";
			$lname_invalid = "<p><b>Your last name contains invalid characters!</b></p>";
			$dname_short = "<p><b>Your display name is too short, please reconsider!</b></p>";
			$signup_successful = "<p><b>Sign up successful, please go to the activation page to verify your E-mail Address! Or you will be redirected there shortly! (Please note that all inactivated accounts are deleted within 48 hours!)</b><p>";
			$signup_unsuccessful = "<p><b>Sign up failed, sorry, but there occured an error in our database, please come back later! You will be redirected back to homepage shortly.</b></p>";
			$email_successful = "<p><b>Your activation code has been delivered to your email address!</b></p>";
			$email_unsuccessful = "<p><b>We have failed to deliver the activation code to you!</b></p>";
			
			//For friend requests page (Repeatition Code=2):
			$user_notexist = "<p><b>The username you entered does not exist!</b></p>";
			$add_yourself = "<p><b>Sorry, but you are not allowed to add yourself into your friend list!</b></p>";
			$add_duplicate = "<p><b>The user you entered is already in your friend list!</b></p>";
			$delete_notin = "<p><b>The user you entered is not in your friend list!</b></p>";
			$request_notsent = "<p><b>The user you entered didn't send you a request!</b></p>";
			
			//For photo upload page (Repeatition Code=3):
			$photoentries_exceed = "<p><b>You already have 20 maximum photos currently.</b></p>";
			$photo_extension = "<p><b>The file you uploaded does not have an image extension!</b></p>";
			$photo_toobig = "<p><b>The image you are uploading is bigger than 1mb, please resize it before uploading.</b></p>";
			$photo_upload = "<p><b>There was an error uploading the image, please try again!</b></p>";
			
			//For send pm page (Repeatition Code=4):
			$user_notexist_4 = "<p><b>The username you entered does not exist!</b></p>";
			
			//For send password page (Repeatition Code=5):
			$user_notexist_5 = "<p><b>The username you entered does not exist!</b></p>";
			$email_invalid_5 = "<p><b>Invalid E-mail Address!</b></p>";
			$email_notexist_notusername = "<p><b>The E-mail doesn't exist or doesn't belong to the uername!</b></p>";
			$email_successful_5 = "<p><b>Your new password has been successfully emailed to you! Please click <a href=\"index.php\">here</a> to login again with your new password.</b></p>";
			$email_unsuccessful_5 = "<p><b>Your password failed to be sent to you!</b></p>";
			$account_inactive = "<p><b>Sorry, but your account is not activated yet!</b></p>";
			
			//For friend lists page (Repeatition Code=6):
			$friends_noresults = "<p align=center><br /><b>Sorry, but there are no friends in your friend list now, try to get some more friends!</b><br /><br /></p>";
			
			//For friend list page (Repeatition Code=7):
			$friends_none = "<p align=center><br /><b>Sorry, but there are no friends in this friend list now.</b><br /><br /></p>";
			
			//For pm page (Repeatition Code=8):
			$pm_nomessages = "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=5 align=center><b>You have no messages in this folder!</b></td></tr>";
			
			//For search results page (Repeatition Code=9):
			$invalid_searchtype = "<p align=center><br /><br /><b>Please specify a valid search type!</b></p>";
			$no_results = "<p align=center><br /><br /><b>No Results found, try widen your search options for more results!</b></p>";
			
			//For invite friends page (Repeatition Code=10):
			$email_invalid_10 = "<p><b>Invalid E-mail Address!</b></p>";
			$email_successful_10 = "<p><b>You have successfully sent an invitation!</b></p>";
			$email_unsuccessful_10 = "<p><b>Sorry, but the invitation failed to process!</b></p>";
			
			//For support page (Repeatition Code=11):
			$email_invalid_11 = "<p><b>Invalid E-mail Address!</b></p>";
			$email_successful_11 = "<p><b>Your have successfully emailed our staff!</b></p>";
			$email_unsuccessful_11 = "<p><b>You have failed to email our staff!</b></p>";
			
			//For forum add thread (Repeatition Code=12):
			$category_none = "<p><b>No category was specified!</b></p>";
			$category_invalid = "<p><b>Category was invalid!</b></p>";
			$poll_tooless = "<p><b>More than two options are required to achieve the goal of the poll</b></p>";
			
			//For forum edit thread (Repeatition Code=13):
			$thread_notexist_notusername = "<p><b>The thread you are trying to edit does not exist or it doesn't belong to you!</b></p>";
			
			//For forum reply thread (Repeatition Code=14):
			$threadid_notexist = "<p><b>The threadid does not exist!</b></p>";
			$thread_locked = "<p><b>Sorry, but this thread is locked, and cannot accept any replies</b></p>";
			
			//For forum threads page (Repeatition Code=15):
			$threads_nothreads = "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=7 align=center><b>No Threads are found in this category!</b></td></tr>";
			
			//For forum viewthread page (Repeatition Code=16):
			$thread_none = "<tr height=\"25\"><td align=left colspan=3><b>No Records Found!</b></td></tr>";
			
			//For public search page (Repeatition Code=17):
			$query_empty = "<p align=center><br /><br /><b>Your search query was empty, please re-type it again!</b></p>";
			$no_results_17 = "<p align=center><br /><br /><b>Sorry, but there are no matching results found in the NetFriending Database Records!</b></p>";
			
			//For admin text edit page (Repeatition Code=18):
			$file_empty = "<p><b>Please use the scroll bar at the bottom to select a text file to edit.</b></p>";
			$file_notexist = "<p><b>File does not exist, please use to scrollbar to search for the correct file.</b></p>";
			$extension_invalid = "<p><b>File extension invalid for security reasons, allowed extensions: .txt</b></p>";
			$file_toobig = "<p><b>File is too big to be loaded, please find an alternative way to edit the file, maximum filesize allowed: 2MB.</b></p>";
			
			//For admin forum edit page (Repeatition Code=19):
			$integers_only = "<p><b>Please input only integers for this field.</b></p>";
			
			//For admin thread edit page (Repeatition Code=20):
			$thread_notexist = "<p><b>The thread you are trying to edit does not exist!</b></p>";
			
			//For user login page (Repeatition Code=21):
			$login_blank = "<p><b><font color=\"red\">Username or Password is blank</font></b></p>";
			$login_inactivate = "<p><b><font color=\"red\">Your account is not yet activated! <a href=\"activation.php\">Activate Now</a></font></b></p>";
			$login_invalid = "<p><b><font color=\"red\">Invalid Username or Password</font></b></p>";
			$login_logout = "<p><b>You have successfully logout from the User Panel</b></p>";
			
			//For admin webmail page (Repeatition Code=22):
			$webmail_nomessages = "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=6 align=center><b>You have no messages in this folder!</b></td></tr>";
			$login_blank_22 = "<p><b><font color=\"red\">Username or Password is blank</font></b></p>";
			$login_invalid_22 = "<p><b><font color=\"red\">Invalid Username or Password</font></b></p>";
			$login_logout_22 = "<p><b>You have successfully logout from Webmail</b></p>";
			
			//For photo gallery page (Repeatition COde=23):
			$photoid_notexist = "<p><b>The photo you are trying to comment on does not exist!</b></p>";
			
			//For blog page (Repeatition Code=24):
			$blogid_notexist = "<p><b>The blog you are trying to comment on does not exist!</b></p>";
			
			//For activation page (Repeatition Code=25):
			$user_notexist_25 = "<p><b>The username you entered does not exist!</b></p>";
			$email_notexist_25 = "<p><b>The E-mail doesn't exist!</b></p>";
			$emailactivation_noexist = "<p><b>Sorry, but you have incorrect E-mail or activation code combination!</b></p>";
			$account_activated = "<p><b>Sorry, but your account is activated already!</b></p>";
			$email_invalid_25 = "<p><b>Invalid E-mail Address!</b></p>";
			$email_successful_25 = "<p><b>Your account information has been delivered to your email address! You can now login to start filling up your account!</b></p>";
			$email_unsuccessful_25 = "<p><b>Your account information failed to deliver to your email address!</b></p>";
			$email_successful_25_1 = "<p><b>The activation code has successfully sent to your new E-mail address!</b></p>";
			$email_unsuccessful_25_1 = "<p><b>The activation code has failed to sent to your new E-mail address, please try again!</b></p>";
			$email_successful_25_2 = "<p><b>The activation code has been successfully resent to your E-mail address!</b></p>";
			$email_unsuccessful_25_2 = "<p><b>The activation code has failed to sent to your E-mail address, please try again!</b></p>";
			$email_inuse = "<p><b>The E-mail you entered is already in use!</b></p>";
			?>