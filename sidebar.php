<style>
	#sidebar li{
		border:none !important;
		cursor: pointer;
	}
	#sidebar{
		left:0;
	}
</style>

<ul class="list-group pt-5 pb-5 ps-5 position-fixed d-flex flex-column h-100" id="sidebar">
	<form id="my-files-form" action="controller.php" method="POST">
		<input type="hidden" name="page" value="Sidebar">
		<input type="hidden" name="command" value="ToMyFiles">
		<li id="my-files" class="list-group-item">My Files</li>
	</form>
	<form id="shared-files-form" action="controller.php" method="POST">
		<input type="hidden" name="page" value="Sidebar">
		<input type="hidden" name="command" value="ToSharedFiles">
		<li id="shared-files" class="list-group-item">Shared Files</li>
	</form>
	<form id="profile-form" action="controller.php" method="POST">
		<input type="hidden" name="page" value="Sidebar">
		<input type="hidden" name="command" value="ToProfile">
		<li id="profile" class="list-group-item">Profile</li>
	</form>
	<form id="logout-form" class="mt-auto" action="controller.php" method="POST">
		<input type="hidden" name="page" value="Sidebar">
		<input type="hidden" name="command" value="LogOut">
		<li class="list-group-item text-danger" id="log-out">Log out</li>
	</form>
</ul>
<script>
	$('#log-out').click(function(){
		$('#logout-form').submit();
	})
	$('#shared-files').click(function(){
		$('#shared-files-form').submit();
	})
	$('#my-files').click(function(){
		$('#my-files-form').submit();
	})
	$('#profile').click(function(){
		$('#profile-form').submit();
	})
</script>