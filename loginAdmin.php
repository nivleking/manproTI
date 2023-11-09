<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Login</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles" />
</head>

<body>
	<section class="vh-100 ">
		<div class="container-fluid mx-auto">
			<div class="row">
				<div class="col-sm-12 text-black">
					<div class="d-flex justify-content-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

						<form style="width: 23rem;" method="POST" action="#" class="">

							<h3 class="fw-normal mt-3 d-flex justify-content-center" style="letter-spacing: 0.5px; font-style:italic; font-size:38px;">
								Cargo Master<br>
								<h4 class="d-flex justify-content-center" style="font-style: italic;">Admin Login</h4>
								<?php //if (isset($_GET['error'])) { 
								?>
								<!-- <p class="d-flex justify-content-center" style="color:red; font-weight:bold;"><?php //echo $_GET['error']; 
																													?></p> -->
								<?php //} 
								?>
							</h3>

							<div class="form-outline mb-4">
								<label class="form-label" for="form2Example18">Username</label>
								<input type="text" id="form2Example18" class="form-control form-control-lg" name="usernameADM" required />
							</div>

							<div class="form-outline mb-4">
								<label class="form-label" for="form2Example28">Password</label>
								<input type="password" id="form2Example28" class="form-control form-control-lg" name="passwordADM" required />
							</div>

							<div class="pt-1 mb-4">
								<button class="btn btn-info btn-lg btn-block" type="submit" name="save">Login</button>
							</div>

							<p class="small d-flex justify-content-center">
								<a class="text-muted" href="forgotPasswordAdmin.php">Forgot password?</a>
							</p>
							<!-- <p class="d-flex justify-content-center">
						Don't have an account?&nbsp
						<a href="registerAdmin.php" class="link-info"> Register here</a>
					</p> -->
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- <div class = "container">
		<div class = "row">
			<div class = "col-9 mx-auto">
				<div class="card center" style="width: 25rem;margin-top: 80px">
				  <div class="card-body">
				    <div class = "text-center" id = "title">
				    	<h3>WELCOME!</h3>
				    </div>
				    <form action="adminDoLogin.php" method="post">
				    	<label>Username</label>
				    	<input type="text" class = "form-control" name="username">
				    	<label>Password</label>
				    	<input type="password" class = "form-control" name="password">
				    	<br>
				    	<br>
				    	<button type="submit" name = "save">Login</button>
				    </form>
				  </div>
				</div>
			</div>	
		</div>
	</div> -->
	<?php

	require 'connect.php';
	if (isset($_POST['save'])) {
		$username = $_POST['usernameADM'];
		$password = $_POST['passwordADM'];
		$sql = mysqli_query($con, "SELECT * FROM admin where id_admin='$username' and password='$password'");
		$row  = mysqli_fetch_array($sql);

		if (is_array($row)) {
			$_SESSION["usernameADM"] = $row['id_admin'];
			$_SESSION["passwordADM"] = $row['password'];
			echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful',
                    text: 'Welcome back, {$row['id_admin']}!',
                    showConfirmButton: false,
                    timer: 2500
                }).then(function() {
                    window.location.href = 'activity.php';
                });
            </script>";
		} else {
			// header("Location: loginAdmin.php?error=Invalid username or password");
			echo "<script>
			    Swal.fire({
			        icon: 'error',
			        title: 'Login Failed',
			        text: 'Invalid username or password'
			    });
			</script>";
			// echo ("<script LANGUAGE='JavaScript'>
			// window.alert('Invalid Username/Password');
			// window.location.href='loginAdmin.php';
			// </script>");
		}
	}
	?>
</body>

</html>