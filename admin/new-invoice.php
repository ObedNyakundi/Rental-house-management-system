<?php 

    //a page name
    $pgnm='Nyumbani: New Invoice';
    $error=' ';
    $timesnap=date('Y-m-d : H:i:s');

    $invoiceMonth=date('Y-m');

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
                               Request add an invoice
             ***************************************************/
                               if (isset($_POST['addInvoice'])) {
                           //admin requests to add a rental invoice. 
                                //STEPS
                        /* 1. collect the information supplied
                           2. insert to table invoice
                            */

                                //gather the data
                            $tenantIdRent=uncrack($_POST['tname']);
                            $invoiceDueDate=uncrack($_POST['ddate']);
                            $comment=uncrack($_POST['comment']);

                            //derived variables
                            $invoiceDate=date('20y-m-d');
                            $invoiceid='INV'.date('YmdHis'); //Invoice ID from invoice time
                            $tenantId=substr($tenantIdRent,0,strpos($tenantIdRent, '_'));
                            $rentAmount=substr($tenantIdRent,strpos($tenantIdRent, '_')+1,strlen($tenantIdRent));
                            $istatus='unpaid'; //invoice status

                            //get the tenant's name and Phone number for SMS
                            $sqt="SELECT `tenant_name`,`phone_number`,`account` from `tenants` where `tenantID`='$tenantId'";
                            $queryt=mysqli_query($conn,$sqt);
                            $ten_record=mysqli_fetch_array($queryt,MYSQLI_BOTH);
                            $tenantName=$ten_record['tenant_name'];
                            $firstName=substr($tenantName,0,strpos($tenantName,' '));
                            $phone=$ten_record['phone_number'];
                            $account=$ten_record['account'];

                            /*if account has money, subtract it and see if the ivoice will be completely paid. if fully paid, update invoice status and account balance*/

                            //first subtract rent amount from tenant account
                            $rentAmount-=$account;

                            if($rentAmount==0){
                                //client has cleared payment using his/her remaining account balance
                                $account=0;
                                $istatus='paid';
                            }elseif ($rentAmount<0) {
                                // client has an overpayment
                                $account=$rentAmount * -1;
                                $istatus='paid';
                            }
                            else{
                                //otherwise, user account should be credited with a debt of the rent
                                $account-=$rentAmount;
                            }

                            //SQL statement to insert to new invoices
                            $sqInvoice="INSERT into `invoices`
                            (`invoiceNumber`,`tenantID`,`dateOfInvoice`,`dateDue`,`amountDue`,`comment`,`status`)
                                VALUES
                            ('$invoiceid','$tenantId','$invoiceDate','$invoiceDueDate','$rentAmount','$comment','$istatus')";

                            //SQL to update tenant account
                            $sq_account="UPDATE `tenants` set `account`= '$account' where 
                            `tenantID`='$tenantId'";

                            //report this transaction
                                $sql_transactions="INSERT into `transactions` (`actor`,`time`,`description`)
                            VALUES ('Admin ($username)', '$timesnap','$username added a new rental invoice ($invoiceid) for tenant ($tenantName) at $timesnap.')";

                            //SQL to check if an invoice for the current month for that tenant already exists
                            $sqcheck="SELECT * from `invoices` where `tenantID`='$tenantId' and `dateOfInvoice` like '%$invoiceMonth%'";
                            $query_verify=mysqli_query($conn,$sqcheck);

                            if (mysqli_num_rows($query_verify)<1) {
                                //this is a new invoice, proceed

                                $mysqli->autocommit(FALSE);
                                 $status =true;

                                //EXECUTE QUERRIES
                                $mysqli->query($sqInvoice)?null: $status=false;
                               $mysqli->query($sq_account)?null: $status=false;
                                $mysqli->query($sql_transactions)?null: $status=false;

                                if ($status) {
                                    //commit
                                    $mysqli->commit();

                                    //send an SMS to the tenant
                                $finalmessage="Greetings ".$firstName.", This is a reminder that you're supposed to pay this month's rent of KES.".$rentAmount." by date ".$invoiceDueDate.". You can make the payment by MPESA to +254706748162.";

                                sendSMS($phone, $finalmessage);


                                //successful. report error code 5
                                header('location:invoices.php?state=5');
                                }
                                else
                                {
                                    //rollback changes all DB changes
                                    $mysqli->rollback();
                                    

                                    //technical error. report code 6
                                    header('location:new-invoice.php?state=6');
                                }
                            }
                            else
                            {
                               //an invoice for this month already exists. report error code 7, ignore Admin request.
                               header('location:new-invoice.php?state=7'); 
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
                            <li><a href="invoices.php">Invoices</a></li>
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
                            <h3 class="box-title m-b-0"><i class="fa fa-credit-card fa-3x"></i> Add A New Rental Invoice</h3>
                            <p class="text-muted m-b-30 font-13"> Fill in the form below: </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form action="new-invoice.php" method="post">
                                        
                                        <div class="form-group">
                                            <label for="tname">Choose a Tenant: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-user"></i></div>

                                                <select  required="" id="tname" name="tname" class="form-control">
                                                    <option value="">**Select a tenant**</option>
                                                    <?php
                                                        $sq1="SELECT `tenant_name`,`tenantID`, `rent_amount` from `tenantsView` where `tenantsView`.`tenantID` NOT IN (SELECT `tenantID` from `invoices` where `dateOfInvoice` like '%$invoiceMonth%') order by `tenantsView`.`tenantID` desc";
                                                        $rec=mysqli_query($conn,$sq1);
                                                        while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                            $tenant=$row['tenant_name'];
                                                            $tenid=$row['tenantID'].'_'.$row['rent_amount'];
                                                            echo "<option value=\"$tenid\"> $tenant </option> ";
                                                        }
                                                        ?>
                                                </select> 
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="ddate">Invoice Due Date: </label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="date" name="ddate" class="form-control" id="ddate" placeholder="Choose Date"> </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="comment">Comment: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-pencil"></i></div>
                                                <textarea name="comment" id="comment" cols="6" placeholder="e.g. this is the rent for Jan 2021" style="width:100%;">This is the rent invoice for this month </textarea>
                                            </div>
                                        </div>

                                        <button type="submit" name="addInvoice" class="btn btn-success btn-lg waves-effect waves-light m-r-10 center"><i class="fa fa-plus-circle fa-lg"></i> Add Invoice</button>
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
