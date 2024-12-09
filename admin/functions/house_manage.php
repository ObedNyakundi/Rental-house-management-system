<?php 

require_once "db.php";

//process house related requests

if (isset($_POST['editHouse'])) {
    // user requests to edit house details.

    //1. collect data
    $hname = @uncrack($_POST['tname']);
    $numberOfRooms = @uncrack($_POST['tnum']);
    $rent_amount = @uncrack($_POST['rent']);
    $status = @uncrack($_POST['oftype']);
    $hsid = @uncrack($_POST['hsid']);

    $sqmain="
        UPDATE `houses` SET 
        `house_name`='$hname',
        `number_of_rooms`='$numberOfRooms',
        `rent_amount`='$rent_amount',
        `house_status`='$status' WHERE `houseID`='$hsid'
    ";

    if (mysqli_query($conn,$sqmain)) {
          //success
          header('location:../houses.php?updated'); exit();

        }else{
          //failed
          header('location:../houses.php?update_error'); exit();

        }


}

if (isset($_POST["id"])) {

	$id = $_POST["id"];

	$sql = "DELETE FROM admin WHERE id=?";

$stmt = $db->prepare($sql);


    try {
      $stmt->execute([$id]);
      header('Location:../users.php?deleted');

      }

     catch (Exception $e) {
        $e->getMessage();
        echo "Error";
    }

}
else {
	header('Location:../users.php?del_error');
}

	

?>