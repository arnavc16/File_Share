<?php 
	if(!isset($_SESSION['userID'])){
		include('controller.php');
	}
?>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<style>
	.alert{
		position:fixed;
		bottom:50px;
		right:50px;
		width:350px;
		display:none;
	}
</style>

<body>
	<div class="d-flex h-100 justify-content-center">
		<?php include 'sidebar.php'; ?>
		<div class="pt-5 container h-100">
			<span class="fw-bold">Username:</span><h1 class="d-inline-block ms-2" style="vertical-align: baseline;"><?php echo $_SESSION['username']; ?></h1>
			<p><span class="fw-bold">Email:</span><span class="ms-2"><?php echo $_SESSION['userEmail']; ?></span></p>
			<button class="text-white btn btn-warning" title="Edit profile" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-pencil-square me-3"></i>Edit Profile</button>
			<button class="text-white btn btn-primary" title="Change password" data-bs-toggle="modal" data-bs-target="#exampleModal2"><i class="bi bi-pencil-square me-3"></i>Change Password</button>
		</div>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Edit profile</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="edit-profile-form" action="controller.php" method="POST">
						<input type="hidden" name="page" value="Profile">
						<input type="hidden" name="command" value="EditProfile">
						<label>Username</label>
						<input type="text" class="form-control" name="username" value="<?php echo $_SESSION['username']; ?>">

						<label>Email</label>
						<input type="email" class="form-control" name="email" value="<?php echo $_SESSION['userEmail']; ?>">
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button onclick="$('#edit-profile-form').submit()" id="save-chages" type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal 2-->
	<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Edit profile</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="change-password-form" action="controller.php" method="POST">
						<input type="hidden" name="page" value="Profile">
						<input type="hidden" name="command" value="ChangePassword">
						<label>Enter old password:</label>
						<input type="password" name="oldPassword" class="form-control">

						<label>Enter new password:</label>
						<input type="password" name="newPassword" class="form-control">
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button onclick="$('#change-password-form').submit()" id="change-password" type="button" class="btn btn-primary">Change Password</button>
				</div>
			</div>
		</div>
	</div>
	<?php if(isset($success_msg_profile)): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
		  <?php echo $success_msg_profile; ?>
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<script>
			$('.alert').show();
		</script>
	<?php endif; ?>
	<?php if(isset($error_msg_profile)): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
		  <?php echo $error_msg_profile; ?>
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<script>
			$('.alert').show();
		</script>
	<?php endif; ?>
</body>
</html>