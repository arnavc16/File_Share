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
	<?php include 'sidebar.php'; ?>

	<div class="d-flex h-100 justify-content-center">
		<div class=" container h-100">
			<div class="row mt-5 gy-4" id="files-container">
				<?php if(isset($files)):?>
					<?php foreach($files as $file): ?>
						<div class="col-3">
							<div class="card bg-warning">
								<div class="card-header d-flex">
									<button class="text-white btn btn-warning" title="Save to My Files" onclick="saveToMyFiles(<?php echo $file['ID']; ?>)"><i class="bi bi-star-fill"></i></button>
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
</body>
<script>
	function saveToMyFiles(fid){
		$.post('controller.php', {page: 'SharedFiles', command:'SaveToMyFiles', fileID: fid}, function(data){
			data = JSON.parse(data);
			console.log(data);
			if(data.result == 'success'){
				$('.alert').removeClass('alert-danger').addClass('alert-success').html(data.message).fadeIn();
				$('#files-container').append()
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
</script>
</html>