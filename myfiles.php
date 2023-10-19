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

	.card-img-top{
		object-fit: cover;
		max-height:200px;
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
	<div class="d-flex h-100 justify-content-center">
		<?php include 'sidebar.php'; ?>
		<div class=" container h-100">
			<form id="upload-form" enctype="multipart/form-data">
				<!-- <input type="hidden" name="page" value="MyFiles"> -->
				<!-- <input type="hidden" name="command" value="UploadFile"> -->
				<label id="upload-file" class="btn btn-success mt-5">Upload file
					<input type="file" name="uploadedFile" class="d-none" onchange="uploadFile()">
				</label>
			</form>
			<div class="row mt-3 gy-4" id="files-container">
				<?php if(isset($myfiles)):?>
					<?php foreach($myfiles as $file): ?>
						<div class="col-3">
							<div class="card">
								<div class="card-header d-flex">
									<button class="text-white btn btn-success" title="Add user to file" onclick="getUsers(<?php echo $file['ID']; ?>)" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-person-plus"></i></button>
									<a class="ms-1 text-white btn btn-primary" title="Download" href="<?php echo $file['Path']; ?>" download><i class="bi bi-download" style="vertical-align: baseline;"></i></a>
									<button class="ms-auto text-white btn btn-danger" onclick="deleteFile(<?php echo $file['ID']; ?>, this)" title="Delete file"><i class="ms-auto bi bi-trash-fill"></i></button>
								</div>
								<?php if($file['Extension'] == 'png' || $file['Extension'] == 'jpg' || $file['Extension'] == 'jpeg' || $file['Extension'] == 'jfif' || $file['Extension'] == 'gif'): ?>
									<img src="<?php echo $file['Path']; ?>" class="card-img-top" alt="...">
								<?php else: ?>
									<img src="uploads/otherfiletypes.png" class="card-img-top" alt="...">
								<?php endif; ?>
								<div class="card-body d-flex">
									<p class="card-text"><?php echo $file['Name']; ?></p>
								</div>
							</div>
						</div>
					<?php endforeach;?>
				<?php endif; ?>
				<?php if(isset($starredfiles)): ?>
					<?php foreach($starredfiles as $file): ?>
						<div class="col-3">
							<div class="card bg-warning">
								<div class="card-header d-flex">
									<!-- <button class="text-white btn btn-danger" title="Save to My Files" onclick="saveToMyFiles(<?php echo $file['ID']; ?>)"><i class="bi bi-star-fill"></i></button> -->
									<button class="text-white btn btn-danger" title="Unsubscribe" onclick="unsubscribe(<?php echo $file['ID']; ?>, this)"><i class="bi bi-file-earmark-minus-fill"></i></button>
									<a class="ms-1 text-white btn btn-primary" title="Download" href="<?php echo $file['Path']; ?>" download><i class="bi bi-download" style="vertical-align: baseline;"></i></a>
									<!-- <button class="ms-auto text-white btn btn-danger"><i class="ms-auto bi bi-trash-fill"></i></button> -->
								</div>
								<?php if($file['Extension'] == 'png' || $file['Extension'] == 'jpg' || $file['Extension'] == 'jpeg' || $file['Extension'] == 'jfif' || $file['Extension'] == 'gif'): ?>
									<img src="<?php echo $file['Path']; ?>" class="card-img-top" alt="...">
								<?php else: ?>
									<img src="uploads/otherfiletypes.png" class="card-img-top" alt="...">
								<?php endif; ?>
								<div class="card-body d-flex">
									<p class="card-text"><?php echo $file['Name']; ?></p>
								</div>
							</div>
						</div>
					<?php endforeach;?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="alert" role="alert">
	</div>

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Edit users</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label>Existing users</label>
					<div id="existing-users" style="min-height:200px; overflow-y: scroll;">
						
					</div>
					<form id="add-user-form">
						<input type="hidden" name="fileID" value="">
						<label>Add new user</label>
						<input type="text" class="form-control" name="username">
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button id="save-changes" type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
	function uploadFile(){
		var formData = new FormData(document.getElementById('upload-form'))
		formData.append('page', 'MyFiles');
		formData.append('command', 'UploadFile');
		$.ajax({
			url: 'controller.php',
			data: formData,
			cache: false,
			processData: false,
			contentType: false,
			type: 'POST',
			success: function(data){
				console.log(data);
				data = JSON.parse(data);
				if(data.result == 'success'){
					$('.alert').removeClass('alert-danger').addClass('alert-success').html(data.message).fadeIn();
					var file = `<div class="col-3">
							<div class="card">
								<div class="card-header d-flex">
									<button class="text-white btn btn-success" title="Add user to file" onclick="getUsers(${data.fileID})" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-person-plus"></i></button>
									<a class="ms-1 text-white btn btn-primary" title="Download" href="${data.path}" download><i class="bi bi-download" style="vertical-align: baseline;"></i></a>
									<button class="ms-auto text-white btn btn-danger" onclick="deleteFile(${data.fileID}, this)" title="Delete file"><i class="ms-auto bi bi-trash-fill"></i></button>
								</div>
									<img src="${(data.extension == 'png' || data.extension == 'jpg' || data.extension == 'jpeg' || data.extension == 'jfif' || data.extension == 'gif') ? data.path :  'uploads/otherfiletypes.png'}" class="card-img-top" alt="...">
								<div class="card-body d-flex">
									<p class="card-text">${data.filename}</p>
								</div>
							</div>
						</div>`;
					$('#files-container').prepend(file);
					$('input[name=uploadedFile]').val('');
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
			}
		})
	}
	function getUsers(fileID){
		$('#add-user-form input[name=fileID]').val(fileID);
		$.post('controller.php', {page: "MyFiles", command: "GetUsers", fileID: fileID}, function(data){
			data = JSON.parse(data);
			$('#existing-users').html('');
			for(let u of data){
				var user = `
				<div class="card pt-2 d-flex flex-row">
					<div class="d-flex flex-column col-10">
						<span class="fw-bold">${u.Username}</span><p>${u.Email}</p>
					</div>
					<div class="col-2">
						<button class="text-white btn btn-danger" onclick="unshareToUser(${fileID}, ${u.ID}, this)"><i class="ms-auto bi bi-trash-fill"></i></button>
					</div>
				</div>`;
				$('#existing-users').append(user);
			}
		})
	}
	$('#save-changes').click(function(){
		var fileID = $('#add-user-form input[name=fileID]').val();
		$.post('controller.php', {page: "MyFiles", command: "AddUser", username: $('#add-user-form input[name=username]').val(), fileID: fileID} , function(data){
			console.log(data);
			data = JSON.parse(data);
			if(data.result == 'success'){
				$('.alert').removeClass('alert-danger').addClass('alert-success').html(data.message).fadeIn();
				$('#add-user-form input[name=username]').val('');
				setTimeout(function(){
					$('.alert').fadeOut();
					getUsers(fileID);
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
	function unshareToUser(fid, uid, el){
		$.post('controller.php', {page: 'MyFiles', command: 'RemoveUserFromFile', fileID: fid, userID: uid}, function(data){
			data = JSON.parse(data);
			if(data.result == 'success'){
				$('.alert').removeClass('alert-danger').addClass('alert-success').html(data.message).fadeIn();
				$(el).closest('.card').remove();
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
	}
	function unsubscribe(fid, el){
		$.post('controller.php', {page: 'SharedFiles', command:'Unsubscribe', fileID: fid}, function(data){
			data = JSON.parse(data);
			console.log(data);
			if(data.result == 'success'){
				$('.alert').removeClass('alert-danger').addClass('alert-success').html(data.message).fadeIn();
				$(el).closest('.col-3').remove();
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
	}
	function deleteFile(fid, el){
		$.post('controller.php', {page: 'MyFiles', command:'DeleteFile', fileID: fid}, function(data){
			data = JSON.parse(data);
			console.log(data);
			if(data.result == 'success'){
				$('.alert').removeClass('alert-danger').addClass('alert-success').html(data.message).fadeIn();
				$(el).closest('.col-3').remove();
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

	}
</script>
</html>