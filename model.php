<?php 
	$conn = mysqli_connect('localhost', 'achandan', 'achandan136', 'C354_achandan');

	function user_is_valid($u, $p){
		global $conn;
		$p = hash('sha256', $p);
		$sql = "select * from Users where Username = '$u' and Password = '$p'";
		$result = mysqli_query($conn, $sql);
		return mysqli_num_rows($result);
	}
	function get_user_id($u){
		global $conn;
		$sql = "select ID from Users where Username = '$u'";
		$result = mysqli_query($conn, $sql);
		$result = mysqli_fetch_assoc($result);
		if(!$result)
			return false;
		return $result['ID'];
	}
	function get_user_email($uid){
		global $conn;
		$sql = "select Email from Users where ID = $uid";
		$result = mysqli_query($conn, $sql);
		$result = mysqli_fetch_assoc($result);
		if(!$result)
			return false;
		return $result['Email'];
	}
	function signup_new_user($u, $p, $e){
		global $conn;
		$p = hash('sha256', $p);
		$sql = "insert into Users (Username, Password, Email) values ('$u', '$p', '$e')";
		$result = mysqli_query($conn, $sql);
		return $result;
	}
	function register_uploaded_file($n, $e, $p, $uid){
		global $conn;
		$sql = "insert into Files(Name, Extension, Path, OwnerID) values ('$n', '$e', '$p', $uid)";
		$result = mysqli_query($conn, $sql);
		if($result)
			return mysqli_insert_id($conn);
		return false;
	}
	function get_my_files($uid){
		global $conn;
		$sql = "select * from Files where OwnerID = $uid order by Created desc";
		$result = mysqli_query($conn, $sql);
		$result = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $result;
	}
	function get_shared_files($uid){
		global $conn;
		$sql = "select * from Files f inner join UserToFiles u on u.FileID = f.ID where u.UserID = $uid order by Created desc";
		$result = mysqli_query($conn, $sql);
		$result = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $result;
	}
	function get_starred_files($uid){
		global $conn;
		$sql = "select * from Files f inner join UserToFiles u on u.FileID = f.ID where u.UserID = $uid and Starred = 1";
		$result = mysqli_query($conn, $sql);
		$result = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $result; 
	}
	function get_users_to_file($fid){
		global $conn;
		$sql = "select Username, Email, ID from Users u inner join UserToFiles uf on uf.UserID = u.ID where uf.FileID = $fid";
		$result = mysqli_query($conn, $sql);
		$result = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $result;
	}
	function add_user_to_file($u, $fid){
		global $conn;
		$uid = get_user_id($u);
		if(!$uid)
			return false;
		$sql = "insert into UserToFiles (UserID, FileID) values ($uid, $fid)";
		$result = mysqli_query($conn, $sql);
		if($result)
			return true;
		return false;
	}
	function is_owner($fid, $uid){
		global $conn;
		$sql = "select * from Files where OwnerID = $uid and ID = $fid";
		$result = mysqli_query($conn, $sql);
		return mysqli_num_rows($result);
	}
	function remove_user_from_file($fid, $uid){
		global $conn;
		$sql = "delete from UserToFiles where FileID = $fid and UserID = $uid";
		$result = mysqli_query($conn, $sql);
		if($result)
			return true;
		return false;
	}
	function save_to_my_file($fid, $uid){
		global $conn;
		$sql = "update UserToFiles set Starred = 1 where FileID = $fid and UserID = $uid";
		$result = mysqli_query($conn, $sql);
		if($result)
			return true;
		return false;
	}
	function edit_profile($uid, $u, $e){
		global $conn;
		$sql = "update Users set Username = '$u', Email = '$e' where ID = $uid";
		$result = mysqli_query($conn, $sql);
		if($result)
			return true;
		return false;
	}
	function delete_file($fid){
		global $conn;
		$sql = "delete from Files where ID = $fid";
		$result = mysqli_query($conn, $sql);
		if($result)
			return true;
		return false;
	}
	function set_password($uid, $p){
		global $conn;
		$p = hash('sha256', $p);
		$sql = "update Users set Password = '$p'";
		$result = mysqli_query($conn, $sql);
		if($result)
			return true;
		return false;
	}
?>