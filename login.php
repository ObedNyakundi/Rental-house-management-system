<?php 
	//connection
	require "connection.php";
	$pgnm="Login";
	$error=" ";
	$attempt=0;

	if (is_logged_in()) {
		header("location:stats.php");
	}
	else
	{

	if (isset($_GET['attempt'])) {
		$attempt=$_GET['attempt'];
	}

	//a user should be thrown off after a failed third attempt.
	if ($attempt>=3 || is_banned()) {
		
		setcookie("banned",'true',time()+86400*2,"/","",0); //user gets banned
		header("location:https://tri.go.ke");
	}

	if (isset($_POST["logU"])) {
		//collect data
		$memberid=strtolower(uncrack($_POST["memberid"]));
		$passwd=uncrack($_POST["pass0"]);
		$attempt=$_POST['attempt'] + 1;

		$sqst="select * from `admins` where `userid`='$memberid' and `password`=SHA('$passwd')";
		$queryresult=mysqli_query($conn,$sqst);
		$arr=mysqli_fetch_array(mysqli_query($conn,$sqst), MYSQLI_BOTH);

		if (mysqli_num_rows($queryresult) == 1) {
				
			//True if the member exists. false otherwise.
			$user=$arr["userid"];
			$uname=$arr["name"];
			$type=$arr["role"]; 

			//set cookies to remember the user for 2 days
			setcookie("name",$uname,time()+86400*2,"/","",0);
			setcookie("tsc",$user,time()+86400*2,"/","",0);
			setcookie("type",$type,time()+86400*2,"/","",0);
			//direct user to statistics page.
			

			header("location:stats.php");
			}
			else
			{				
				//$attempt+=1;
				header("location:alogin.php?err=1&attempt=$attempt");
			}
	}

//definition of errors
	if (isset($_GET['err'])) {
		#get the error code
		$err=$_GET['err'];
		if ($err==1) 
		{
			$remaining_attempts=3-$attempt;
			if ($remaining_attempts==1) {
				$error="<div class='alert w3-card-4 w3-yellow center fade in'> 
				<strong style='color:red'>This is your last attempt!!</strong> <br> 
				Are you sure you want to proceed?<br>
				Kindly note that this is your final attempt, and if you fail to login, you won't be able to login for the next two days. Please, contact the system administrator if you got a problem with your account. 
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
				</div>";
			}
			else
			{
			$error="<div class='alert w3-card-4 w3-yellow center fade in'> 
				<strong>Invalid Login details...</strong> <br> 
				The credentials you supplied are invalid. <br> You've got <strong>$remaining_attempts</strong> remaining attemps to try again.
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
				</div>";
			}
		}
	}
	

		//include the header

		require "header.php";
?>

<section height="auto">
	<div class="container works clearfix">
		<div class="row" style="padding: 0.5em;">

			<div style="max-width: 600px; margin-right: auto; margin-left: auto;">
					<?php
					//this section shows all errors 
							echo $error;
						?>

			</div>


			<div class="login-panel center">
			<div class="index-bg" style="max-width: 500px; min-height: 500px;">
				<div>
					<img src="img/g.png" class="img-responsive center" alt="TRI Logo" style="width:100px; height:auto;">
				</div>
					<div class="bg-overlay center" style="width:80%;">
						
						<div style="height:100%; color:white">
							<h1>Login <img src="./img/icons/Lock.png"></h1><br>

							<div class="text-center">
													
								
								<form method="POST" action="alogin.php">
									<div  class="login-element">
											<label for="email">Member ID:</label>
											<input type="text" name="attempt" readonly="true" hidden="true" value="<?php echo $attempt; ?>">
											<input type="email" autofocus required id="email" name="memberid" class="form-control" placeholder="Member Id e.g QW**XX87" title="Please enter your membership ID" style="color: white; text-align: center;">
									</div>

									<div  class="login-element">
											<label for="passwd">Password:</label>
											<input type="password" required id="passwd" name="pass0" class="form-control" placeholder="Enter password" title="The password is required" style="color: white; text-align: center;">
									</div>
									
											<input type="submit" name="logU" class="btn w3-yellow pull-center" value="Login">
										</form>
							</div>
						</div>
					</div>
				
			</div>
			
	

			</div>
		</div>
		</section>

	</div>
	

<?php
//inculde the footer
		require "footer0.php";
	}

?>