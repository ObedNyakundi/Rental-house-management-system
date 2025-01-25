<?php 

    //a page name
    $pgnm='Nyumbani: Admit a tenant';
    $error=' ';

    //start sessions 
    ob_start();

    //require a connector
    require_once "functions/db.php";

    //require the global file for errors
    require_once "functions/errors.php";


    // Initialize the session
    session_start();

    // If user is not logged in, redirect to index otherwise, allow access
     if (is_logged_in_temporary()) {
        //allow access

        //take requests & actions

             /*****************************************************
                               action add a tenant
             ***************************************************/
                               if (isset($_POST['admitTenant'])) {
                           //admin requests to admit a tenant. 
                           //This is a transaction involving table tenants and houses
                                //STEPS
                        /* 1. collect the information supplied
                           2. insert to table tenants
                           3. update table houses, set number_of_rooms -=1
                           4. if current number_of_rooms == 1 (1 room remaining), update status = occupied

                        */

                            //gather the data
                        $tname=is_username($_POST['tname']);
                        $firstName=substr($tname, 0,strpos($tname, ' ')); //first name

                        $temail=is_email($_POST['temail']);
                        $idnum=uncrack($_POST['idnum']);
                        $phone=uncrack($_POST['phone']);
                        $prof=is_username($_POST['prof']);
                        $dateAdmitted=date('20y-m-d');
                        $house=uncrack($_POST['house']); //a combination of room ID and number of rooms

                        //format phone number
                        if(substr($phone, 0,1)=='0'){
                            $phone='+254'.substr($phone,1,strlen($phone));
                        }

                        //derive house ID and number of rooms from $house variable

                        $houseid=substr($house, 0,strpos($house, '_'));
                        $noOfRooms=substr($house, strpos($house, '_')+1,strlen($house));
                        $timesnap=date('Y-m-d : H:i:s');

                        

                        $sq1="SELECT `house_name`,`rent_amount` from `houses` where `houseID`='$houseid'";
                        $rec_house=mysqli_query($conn,$sq1);
                        $rec_item=mysqli_fetch_array($rec_house,MYSQLI_BOTH);

                        $hsname=$rec_item['house_name'];
                        $rentAmount=$rec_item['rent_amount'];


                        //start by assuming that the house will remain partially vaccant, then test
                        $houseStatus="Vacant";
                        if ($noOfRooms==1) {
                            $houseStatus="Occupied";
                        }
                        //decrement the number of rentable units (rooms) by 1
                        $noOfRooms-=1;

                         //inserting the data as an atomic transaction. 

                        //start with preparing SQL statements
                        
                        //A query to add tenant
                        $sq_tenants="INSERT into `tenants` 
                            (`houseNumber`,`tenant_name`,`email`,`ID_number`,`profession`,`phone_number`,`dateAdmitted`) values('$houseid','$tname','$temail','$idnum','$prof','$phone','$dateAdmitted');";

                        //A query to update houses
                        $sq_houses="UPDATE `houses` SET `number_of_rooms`='$noOfRooms', `house_status`='$houseStatus' WHERE `houseID`='$houseid'";

                        // report this transaction
                         $sql_transactions="INSERT into `transactions` (`actor`,`time`,`description`)
                            VALUES ('Admin ($username)', '$timesnap','$username admitted a new tenant ($tname) at $timesnap')";

                        //BEGIN AN ATOMIC TRANASACTION 

                        //Start with disabling autocommit
                        $mysqli -> autocommit(FALSE);
                        //set a status flag. we shall flag it to 'false' if any of the transactions fails
                        $status =true;

                        //EXECUTE QUERRIES
                        $mysqli->query($sq_tenants)?null: $status=false;
                        $mysqli->query($sq_houses)?null: $status=false;
                        $mysqli->query($sql_transactions)?null: $status=false;


                            if ($status) {
                                #successful, commit changes
                                $mysqli ->commit();

                                //send an SMS to the new tenant
                                $finalmessage="Hello ".$firstName.", You're welcome to Nyumbani Homes. You were admitted to ".$hsname." with rent amount of KES. ".$rentAmount." per month.";
                                sendSMS($phone, $finalmessage);
                                

                                //head to tenants table and report as an error state code 3. refer errors.php
                                header('location:tenants.php?state=3');
                            }
                            else
                            {
                                //rollback changes
                                $mysqli -> rollback();
                                //return back to page with error state 4
                                header('location:new-tenant.php?state=4');
                            }
                            
                            

                       }

   
    /*******************************************************
                    introduce the admin header
    *******************************************************/
    require "admin_header0.php";


    /*******************************************************
                    Add the left panel
    *******************************************************/
    require "admin_left_panel.php";

    ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo 'Hey '.$username.'!';?></h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li><a href="index.php">Dashboard</a></li>
                            <li><a href="tenants.php">Tenants</a></li>
                            <li class="active">New</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-12">
                        <div style="">
                            <?php 
                            echo $error;
                            ?>
                        </div>
                        <div class="white-box">
                            <h3 class="box-title m-b-0"><i class="fa fa-user fa-3x"></i> Admit A New Tenant</h3>
                            <p class="text-muted m-b-30 font-13"> Fill in the form below: </p>
                            <div class="row">

                                <div class="col-sm-12 col-xs-12">
                                    <form action="new-tenant.php" method="post">
                                        
                                        <div class="form-group">
                                            <label for="hname">Tenant Name: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-pencil"></i></div>
                                                <input type="text" name="tname" class="form-control" id="hname" placeholder="Enter tenant name" required=""> </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="temail">Email: </label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-at"></i></div>
                                                <input type="email" name="temail" class="form-control" id="temail" placeholder="example@nyumbani.com"> </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="idnum">ID Number: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                                <input type="number" min="1000" required name="idnum" class="form-control" id="idnum" placeholder="ID Number..." > </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">Phone Number: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                                <input type="number" min="0" name="phone" class="form-control" id="phone" placeholder="e.g 254 712 345678" required=""> </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="prof">Profession: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-briefcase"></i></div>
                                                <input type="text" required name="prof" class="form-control" id="prof" placeholder="e.g. Teacher" required=""> </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="house">House: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-home"></i></div>
                                                <select  required="" id="house" name="house" class="form-control">
                                                    <option value="">**Select a house**</option>
                                                    <?php
                                                        $sq0="SELECT `houseID`,`house_name`,`house_status`, `number_of_rooms` FROM `houses` where `houses`.`house_status`='Vacant' order by `houseID` desc";
                                                        $rec=mysqli_query($conn,$sq0);
                                                        while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                            $house=$row['house_name'];
                                                            $hsid_rooms=$row['houseID'].'_'.$row['number_of_rooms'];
                                                            echo "<option value=\"$hsid_rooms\"> $house </option> ";
                                                        }
                                                        ?>
                                                </select>
                                                </div>
                                        </div>

                                        <button type="submit" name="admitTenant" class="btn btn-success btn-lg waves-effect waves-light m-r-10 center"><i class="fa fa-plus-circle fa-lg"></i> Admit this tenant</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
           

                </div>
                <!--./row-->
               
           
               
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul>
                                <li><b>Layout Options</b></li>
                                <li>
                                    <div class="checkbox checkbox-info">
                                        <input id="checkbox1" type="checkbox" class="fxhdr">
                                        <label for="checkbox1"> Fix Header </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="checkbox checkbox-warning">
                                        <input id="checkbox2" type="checkbox" checked="" class="fxsdr">
                                        <label for="checkbox2"> Fix Sidebar </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="checkbox checkbox-success">
                                        <input id="checkbox4" type="checkbox" class="open-close">
                                        <label for="checkbox4"> Toggle Sidebar </label>
                                    </div>
                                </li>
                            </ul>
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" theme="gray" class="yellow-theme">3</a></li>
                                <li><a href="javascript:void(0)" theme="blue" class="blue-theme working">4</a></li>
                                <li><a href="javascript:void(0)" theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" theme="megna" class="megna-theme">6</a></li>
                                <li><b>With Dark sidebar</b></li>
                                <br/>
                                <li><a href="javascript:void(0)" theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" theme="gray-dark" class="yellow-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" theme="megna-dark" class="megna-dark-theme">12</a></li>
                            </ul>
                            </div>
                    </div>
                </div>
                <!-- /.right-sidebar -->
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> 2018 &copy; Company Admin </footer>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/tether.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="js/jasny-bootstrap.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>

    <!-- Local Javascript -->
        <script type="text/javascript">
            
        </script>
    <!--END of local JS -->

</body>

</html>
<?php
}
else{
    header('location:../index.php');
}
?>
