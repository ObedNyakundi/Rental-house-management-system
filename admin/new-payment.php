<?php 

    //a page name
    $pgnm='Nyumbani: New Payment';
    $error=' ';
    $timesnap=date('Y-m-d : H:i:s');

    //start sessions 
    ob_start();

    //require a connector
    require_once "functions/db.php";

    //require the global file for errors
    require_once "functions/errors.php";


    // Initialize the session
    session_start();

    // If current user is not logged in, redirect to index otherwise, allow access
     if (is_logged_in_temporary()) {
        //allow access

        //take requests & actions

             /*****************************************************
                               action add a new payment
             ***************************************************/
                               if (isset($_POST['newPayment'])) {
                           //admin requests to add a rental invoice payment.
                           //this transaction affects the invoice,payments, and transaction tables 
                                //STEPS
                        /* 1. collect the information supplied
                           2. prepare SQL statements
                           3. set an attomic transaction to update tables
                            */

                                //Step 1: gathering data
                            $tenantId=uncrack($_POST['tenID']);
                            $invoiceNumber=uncrack($_POST['invoiceNumber']);
                            $amountExpected=uncrack($_POST['amountDue']);
                            $amountPaid=uncrack($_POST['paidAmount']);
                            $mpesaCode=uncrack($_POST['mpesa']);
                            $comment=uncrack($_POST['comment']);

                            //derived variables
                            $paymentDate=date('Y-m-d');
                            $timesnap=date('Y-m-d : H:i:s');
                            $balance=$amountExpected-$amountPaid;

                            //generate account balance
                            $sqtenant="SELECT `account`,`tenant_name`,`phone_number` from `tenants` where `tenantID`='$tenantId'";
                            $tenquer=mysqli_query($conn,$sqtenant);
                            $rec=mysqli_fetch_array($tenquer,MYSQLI_BOTH);
                            $account=$rec['account']; //the user account balance
                            $tnm=$rec['tenant_name']; //tenant name
                            $firstName=substr($tnm,0,strpos($tnm, " "));
                            $phone=$rec['phone_number'];//tenant's phone number

                            //let's assume that the status shall remain 'unpaid,
                            //proceed to test and update if client clears his/her pay
                            $status='unpaid';

                            if ($balance<1) {
                                $status='paid';

                                if ($balance<0) 
                                {
                                //client has overpaid. let's keep it in client's account
                                $account+=($balance * -1); //make it positive, then store it
                                }
                            }else{
                                $account+=$amountPaid;
                            }

                            //Step2: sql statements
                            $sql_inv="UPDATE `invoices` set `amountDue`='$balance', `status`='$status' where `invoiceNumber`='$invoiceNumber'"; //invoice table

                            $sql_ten="UPDATE `tenants` set `account`='$account' where `tenantID`='$tenantId'"; //update tenant account balance

                            $sql_payment="INSERT INTO `payments`
                                (`tenantID`, `invoiceNumber`, `expectedAmount`, `amountPaid`, `balance`, `mpesaCode`, `dateofPayment`, `comment`) 
                                VALUES ('$tenantId','$invoiceNumber','$amountExpected','$amountPaid','$balance','$mpesaCode','$paymentDate','$comment')";//inserts a new payment

                            $sql_transactions="INSERT into `transactions` (`actor`,`time`,`description`)
                            VALUES ('Admin ($username)', '$timesnap','$username added payment of $amountPaid for $tnm, under invoice ID: $invoiceNumber')";

                            //Step 3: Running an atomic transactionto effect the three tables
                                $mysqli->autocommit(FALSE);

                                $state=true;

                                $mysqli->query($sql_inv)?null: $state=false;
                                $mysqli->query($sql_ten)?null: $state=false;
                                $mysqli->query($sql_payment)?null: $state=false;
                                $mysqli->query($sql_transactions)?null: $state=false;


                            if ($state) 
                            {
                                //success. report error state 8
                                $mysqli -> commit();

                                     //send an SMS
                                $tophonenumber=$phone;
                                $finalmessage="Greetings ".$firstName.", This is a confirmation that your rent payment of KES. ".$amountPaid." has been received and updated. Remaining balance to pay is KES. ".$balance.". Thank you.";

                                    @sendSMS($tophonenumber, $finalmessage);
                                //end of sending SMS

                                header("location:payments.php?state=8");
                            }
                            else
                            {
                                //rollback changes
                                $mysqli -> rollback();
                                //failed. report error state 9
                               header("location:new-payment.php?state=9");

                                
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
                        <h4 class="page-title"><?php echo 'Howdy, '.$username.'!';?></h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li><a href="index.php">Dashboard</a></li>
                            <li><a href="payments.php">Payments</a></li>
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
                            <h3 class="box-title m-b-0"><i class="fa fa-money fa-3x"></i> Add A New Rent Payment</h3>
                            <p class="text-muted m-b-30 font-13"> Fill in the form below: </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form action="new-payment.php" method="post">
                                        
                                        <div class="form-group">
                                            <label for="tname">Choose a Tenant: <span style="color:red">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-user"></i></div>

                                                <select  required="" id="tname" name="invoiceNumber" class="form-control" onchange="requestInvoice(this.value);">
                                                    <option value="">**Select a tenant**</option>
                                                    <?php
                                                        $sq1="SELECT `invoiceNumber`,`tenant_name`,`tenantID` from `invoicesView` where `status`='unpaid' order by `tenantID` desc";
                                                        $rec=mysqli_query($conn,$sq1);
                                                        while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                            $tenant=$row['tenant_name'];
                                                            $tenid=$row['invoiceNumber'];
                                                            echo "<option value='$tenid'> $tenant (<i> $tenid </i>) </option> ";
                                                        }
                                                        ?>
                                                </select> 
                                            </div>
                                        </div>

                                        <div id="txtInvoice">
                                            
                                        </div>

                                        <div class="form-group">
                                            <label for="paidAmount">Amount Paid: <span style="color:red">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-usd"></i></div>
                                                <input type="number" required="" min="0" name="paidAmount" class="form-control" id="paidAmount" placeholder="Enter Amount"> </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="mpesa">MPESA CODE (Optional): </label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-usd"></i></div>
                                                <input type="text" min="0" name="mpesa" class="form-control" id="mpesa" placeholder="Mpesa code e.g. XX00XXYY" style=" background-color: #000; color: #fff; font-weight: 700;text-transform: uppercase;"> </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="comment">Comment: * </label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-pencil"></i></div>
                                                <textarea required name="comment" id="comment" cols="6" placeholder="e.g. This is the rent for Jan 2021" style="width:100%;"></textarea>
                                            </div>
                                        </div>

                                        <button type="submit" name="newPayment" class="btn btn-success btn-lg waves-effect waves-light m-r-10 center"><i class="fa fa-plus-circle fa-lg"></i> Update this Payment</button>
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
            //ajax method to source invoice details selected

            function requestInvoice(str) 
            {
              if (str == "") {
                document.getElementById("txtInvoice").innerHTML = "";
                return;
              } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("txtInvoice").innerHTML = this.responseText;
                  }
                };
                xmlhttp.open("GET","functions/request_invoice.php?q="+str,true);
                xmlhttp.send();
              }
            }
            
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
