<?php

require_once "functions/db.php";

	if (isset($_GET["act"])) {
		//collect data
		$data=uncrack($_GET["q"]);
		$option_type=$_GET["act"];

		//for deleting notifications
		if ($option_type=='notifications') {
			$sq="UPDATE `transactions` SET `seen`='YES' where `id`='$data'";
			mysqli_query($conn,$sq);
		}
	}


?>