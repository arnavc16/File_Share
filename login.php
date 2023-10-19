<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<style>
	#move-to-log-in, #move-to-sign-up{
		cursor: pointer;
		color: blue !important;
	}
	.alert{
		position:fixed;
		bottom:50px;
		right:50px;
		width:350px;
		display:none;
	}
</style>
<body>
	<div class="container h-100 d-flex align-items-center">
		<div class="card text-center col-md-4 m-auto" id="login">
			<form action="controller.php" method="POST">
				<div class="card-header">
					<span>Log in</span>
				</div>
				<div class="card-body">
					<div class="form-floating mb-3">
						<input name="username" type="text" class="form-control" id="floatingInput" placeholder="username">
						<label for="floatingInput">Username</label>
					</div>
					<?php if(isset($error_msg_username)) echo '<span class="text-danger">' . $error_msg_username . '</span>'; ?>
					<div class="form-floating">
						<input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
						<label for="floatingPassword">Password</label>
					</div>
					<?php if(isset($error_msg_password)) echo '<span class="text-danger">' . $error_msg_password . '</span>'; ?>
				</div>
				<input type="hidden" name="page" value="LogIn">
				<input type="hidden" name="command" value="LogIn">
				<div class="card-footer">
					<p>Don't have an account?<a id="move-to-sign-up"> Sign up </a></p>
					<button type="submit" class="btn btn-primary">Log in</button>
				</div>
			</form>
		</div>
		<div class="card text-center col-md-4 m-auto" id="signup" style="display:none">
			<form id="signup-form" action="controller.php" method="POST">
				<div class="card-header">
					<span>Sign up</span>
				</div>
				<div class="card-body">
					<div class="form-floating mb-3">
						<input name="username" type="text" class="form-control" id="floatingName" placeholder="username">
						<label for="floatingName">Name</label>
					</div>
					<div class="form-floating mb-3">
						<input name="email" type="email" class="form-control" id="signupEmail" placeholder="name@example.com">
						<label for="signupEmail">Email address</label>
					</div>
					<div class="form-floating">
						<input name="password" type="password" class="form-control" id="signupPassword" placeholder="Password">
						<label for="signupPassword">Password</label>
					</div>
				</div>
				<input type="hidden" name="page" value="LogIn">
				<input type="hidden" name="command" value="SignUp">
				<div class="card-footer">
					<p>Already have an account?<a id="move-to-log-in"> Login </a></p>
					<button id="signup-button" type="button" class="btn btn-primary">Sign up</button>
				</div>
			</form>
		</div>
	</div>
<div class="alert" role="alert">
	</div>
</body>
<script>
	$('#move-to-sign-up').click(function(){
		$("#signup").show();
		$('#login').hide();
	})
	$('#move-to-log-in').click(function(){
		$("#signup").hide();
		$('#login').show();
	})
	$('#signup-button').click(function(){
		$.post('controller.php', $('#signup-form').serialize(), function(data){
			data = JSON.parse(data);
			if(data.result == 'success'){
				$('.alert').removeClass('alert-danger').addClass('alert-success').html(data.message).fadeIn();
				setTimeout(function(){
					$('.alert').fadeOut();
				}, 1700);
			}
			else{
				$('.alert').removeClass('alert-success').addClass('alert-danger').html(data.message).fadeIn();
				setTimeout(function(){
					$('.alert').fadeOut();
				}, 1700);
			}
		})
	})
</script>
</html>