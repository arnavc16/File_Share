<?php
if (empty($_POST['page'])) {
    include('login.php');
    exit();
}
include 'model.php';

// When commands come from Login page
if ($_POST['page'] == 'LogIn')
{
    $command = $_POST['command'];
    switch($command) { 
        case 'LogIn':  // With username and password
            if (!user_is_valid($_POST['username'], $_POST['password'])) {
                $error_msg_username = '* Wrong username, or';
                $error_msg_password = '* Wrong password'; // Set an error message into a variable.
                                                        // This variable will used in the form in 'login.php'.
                include('login.php');
            } 
            else {
                session_start();
                $_SESSION['signed'] = 'YES';
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['userID'] = get_user_id($_POST['username']);
                $_SESSION['userEmail'] = get_user_email($_SESSION['userID']);
                $starredfiles = get_starred_files($_SESSION['userID']); 
	    		$myfiles = get_my_files($_SESSION['userID']);
	    		include('myfiles.php');
            }
            exit();
            break;

        case 'SignUp':  // With username, password, email, some other information
            if (signup_new_user($_POST['username'], $_POST['password'], $_POST['email'])) {
            	$message = ["result"=>"success", "message"=>"Sign up successfully, please log in"];
            }
            else {
               	$message = ["result"=>"failed", "message"=>"Username or email existed"];
            }
            echo json_encode($message);
            exit();
            break;
            
        default:
            echo "Unknown command from Login Page<br>";
            exit();
            break;
    }
}

else if ($_POST['page'] == 'MyFiles'){
    session_start();
    $command = $_POST['command'];
    switch($command) {
        case 'LogOut':
            session_reset();
            session_destroy();
            $display_modal_window = 'none';
            include('login.php');
            exit();
            break;
    	case 'UploadFile':
    		$target_dir = "uploads/";
			$target_file = $target_dir . basename($_FILES["uploadedFile"]["name"]);
			$filetype = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$filename = basename($_FILES["uploadedFile"]["name"], '.' . $filetype);
			$n = 0;
			while(file_exists($target_file)) {
				$n += 1;	
			    $target_file = $target_dir . $filename . "($n)." . $filetype;	
			}
			if($n > 0) 
				$filename = $filename . "($n)." . $filetype;
			else
				$filename = $filename . "." . $filetype;
			// if($n > 0) $target_file = $target_dir . $filename . "($n)" . $filetype;
			// Check file size
			if ($_FILES["uploadedFile"]["size"] > 50000000) {
				$message = ["result"=>"failed", "message"=>"File size needs to be < 50MB"];
				echo json_encode($message);
				exit();
			}
			if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_file)) {
                $fileID = register_uploaded_file($filename , $filetype, $target_file, $_SESSION['userID']);
				if($fileID){
					$message = ["result"=>"success", "message"=>htmlspecialchars($filename) . " uploaded.", "fileID" => $fileID, "path" => $target_file, "extension" => $filetype, "filename" => $filename];
				}
				else{
					$message = ["result"=>"failed", "message"=>"A database error occurred"];
				}
			} else {
				$message = ["result"=>"failed", "message"=>"An unknown error occured"];
			}
			echo json_encode($message);
			exit();
    		break;
    	case 'GetUsers':
    		$fileID = $_POST['fileID'];
    		$users = get_users_to_file($fileID);
    		echo json_encode($users);
    		exit();
    		break;
    	case 'AddUser':
    		$fileID = $_POST['fileID'];
    		$result = add_user_to_file($_POST['username'], $fileID);
    		if($result) {
            	$message = ["result"=>"success", "message"=>"Added successfully"];
            }
            else {
               	$message = ["result"=>"failed", "message"=>"No user found"];
            }
            echo json_encode($message);
            exit();
            break;
        case 'RemoveUserFromFile':
        	$fileID = $_POST['fileID'];
        	$userID = $_POST['userID'];
        	if(is_owner($fileID, $_SESSION['userID'])){
	        	$result = remove_user_from_file($fileID, $userID);
	        	if($result) {
	            	$message = ["result"=>"success", "message"=>"Removed successfully"];
	            }
	            else {
	               	$message = ["result"=>"failed", "message"=>"No user found"];
	            }	        
	        }
	        echo json_encode($message);
	        exit();
	        break;
	    case 'DeleteFile':
        	$fileID = $_POST['fileID'];
        	if(is_owner($fileID, $_SESSION['userID'])){
	        	$result = delete_file($fileID);
	        	if($result) {
	            	$message = ["result"=>"success", "message"=>"Deleted successfully"];
	            }
	            else {
	               	$message = ["result"=>"failed", "message"=>"Error"];
	            }
            }else{
                $message = ["result"=>"failed", "message"=>"Not authorized"];  
            }
            echo json_encode($message);
            exit();
            break;
        default:
            echo "Unknown command from MainPage<br>";
            exit();
            break;
    }
}
else if($_POST['page'] == 'SharedFiles'){
	session_start();
    $command = $_POST['command'];
    switch($command) {
    	case 'SaveToMyFiles':
    		$fileID = $_POST['fileID'];
    		$result = save_to_my_file($fileID, $_SESSION['userID']);
    		if($result) {
            	$message = ["result"=>"success", "message"=>"Saved successfully"];
            }
            else {
               	$message = ["result"=>"failed", "message"=>"Error"];
            }	
            echo json_encode($message);
            exit();
            break;
        case 'Unsubscribe':
        	$fileID = $_POST['fileID'];
        	$result = remove_user_from_file($fileID, $_SESSION['userID']);
        	if($result) {
            	$message = ["result"=>"success", "message"=>"Unsubscribed successfully"];
            }
            else {
               	$message = ["result"=>"failed", "message"=>"Error"];
            }	
            echo json_encode($message);
            exit();
            break;
    	default:
    		exit();
    		break;
    }
}
else if($_POST['page'] == 'Profile'){
	session_start();
	if(isset($error_msg_profile)) unset($error_msg_profile);
	if(isset($success_msg_profile)) unset($success_msg_profile);
    $command = $_POST['command'];
    switch($command) {
        case 'EditProfile':
        	$result = edit_profile($_SESSION['userID'], $_POST['username'], $_POST['email']);
        	if(!$result) 
        		$error_msg_profile = "An error has occurred";
        	else{
        		$success_msg_profile = "Success";
        		$_SESSION['username'] = $_POST['username'];
        		$_SESSION['userEmail'] = $_POST['email'];
        	}
	        include('profile.php');
            exit();
            break;
        case 'ChangePassword':
            if(!user_is_valid($_SESSION['username'], $_POST['oldPassword'])){
                $error_msg_profile = "Wrong password";
            }
            else{
                $result = set_password($_SESSION['userID'], $_POST['newPassword']);
                if(!$result) 
                    $error_msg_profile = "An error has occurred";
                else{
                    $success_msg_profile = "Success";
                }
            }
            include('profile.php');
            exit();
            break;
    }
}
else if($_POST['page'] == 'Sidebar'){
	session_start();
    $command = $_POST['command'];
    switch($command) {
		case 'LogOut':
    		session_unset();
    		session_destroy();
    		include('login.php');
    		exit();
    		break;
    	case 'ToSharedFiles':
    		$files = get_shared_files($_SESSION['userID']);
    		include('sharedfiles.php');
    		exit();
    		break;
    	case 'ToMyFiles':
    		$starredfiles = get_starred_files($_SESSION['userID']); 
    		$myfiles = get_my_files($_SESSION['userID']);
    		include('myfiles.php');
    		exit();
    		break;
    	case 'ToProfile':
    		include('profile.php');
    		exit();
    		break;	
	}
}
// Wrong
else {
    echo 'Wrong page<br>';
}
?>   