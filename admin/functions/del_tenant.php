<?php 

 
require_once "db.php";

//action to delete a tenant.
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
//Request to update a tenant record
if (isset($_POST["editTenant"])) {
  //collect the data
  $tenid = uncrack($_POST["ten_id"]);
  $tname=is_username($_POST['tname']);
  $firstName=substr($tname, 0,strpos($tname, ' ')); //first name
  $temail=is_email($_POST['temail']);
  $idnum=uncrack($_POST['idnum']);
  $phone=uncrack($_POST['phone']);
  $prof=is_username($_POST['prof']);

  $timesnap=date('Y-m-d : H:i:s');

  //format phone number to Kenyan ext. of 254
  if(substr($phone, 0,1)=='0'){
      $phone='+254'.substr($phone,1,strlen($phone));
  }

  //prepare SQL queries

  //update the tenant
  $sq_tenant="
        UPDATE `tenants` SET 
        `tenant_name` = '$tname', 
        `email` = '$temail', 
        `ID_number` = '$idnum', 
        `phone_number` = '$phone', 
        `profession` = '$prof' 
        WHERE `tenants`.`tenantID` = '$tenid'";

  //report the update
    $sql_transactions="INSERT into `transactions` (`actor`,`time`,`description`)
     VALUES ('Admin ($username)', '$timesnap','$username updated tenant details for ($tname) at $timesnap')";

  //begin transactions
      $mysqli -> autocommit(FALSE);
      $status =true;

      //EXECUTE INDIVIDUAL QUERRIES
      $mysqli->query($sq_tenant)?null: $status=false;
      $mysqli->query($sql_transactions)?null: $status=false;

      //check if successful
      if ($status) {
        $mysqli ->commit();

        //head to index and report as an error state.
        header('Location:../tenants.php?state=12');
      }
      else
      {
        //rollback changes
        $mysqli -> rollback();
        //return back to page with error state
        header('Location:../tenants.php?state=13');
      }
}
else {
	header('Location:../tenants.php?del_error');
}

	

?>