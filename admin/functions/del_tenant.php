
<?php 

 
require_once "db.php";

if (isset($_POST["deleteTenant"])) {
  //collecting data
	$tenid = $_POST["tenID"];
  $numberOfRooms=$_POST["num"];
  $roomId=$_POST['hsID'];
  $hsState=$_POST["state"];

  if ($numberOfRooms==0) {
    #set house status to 'Vacant' since there will be a free renting unit
    $hsState='Vacant';
  }
  //increment number of rooms by 1
  $numberOfRooms+=1;

  //A query to update houses
   $sq_houses="UPDATE `houses` SET `number_of_rooms`='$numberOfRooms', `house_status`='$hsState' WHERE `houseID`='$roomId'";
   //A query to remove tenant
  $sq_tenants="DELETE FROM `tenants` WHERE `tenants`.`tenantID`='$tenid'";

  $mysqli ->autocommit(FALSE);
  $status =true;

      //EXECUTE QUERRIES
  $mysqli->query($sq_tenants)?null: $status=false;
  $mysqli->query($sq_houses)?null: $status=false;
	

if ($status) {
                  #successful, commit changes
                  $mysqli ->commit();

                        //head to index and report as an error state.
                   header('Location:../tenants.php?deleted');
              }
            else
              {
                      #rollback changes
                    $mysqli -> rollback();
                    //return back to page with error state
                    header('Location:../tenants.php?del_error');
              }

}
else {
	header('Location:../tenants.php?del_error');
}

	

?>