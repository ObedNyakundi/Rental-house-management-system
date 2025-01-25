<?php
//I use this file to define all errors

//these are the model error messages
/*$error="<div id='dlt' class='alert alert-slim w3-card-4 alert-danger fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-frown-o fa-3x'></i></div>
                <strong>Ooops!...<br>
                Now, this is embarrasing. </strong> <br>
                We faced was a technical error when submitting your information. This could have been caused by the following reasons:<br>
               
            <br>
            
            </div>";


 '<div class="alert alert-success" >
					                          <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
					                         <strong>DONE!! </strong><p> The new Company Administrator has been added. They can now log in to their account with their credentials.</p>
					                    </div>'
*/

/****************************************************************************************
		DEFINED ERROR STATES
	1 - New house added
	2 - Failed to add new house
	3 - A new tenant admitted
	4 - Failed to admit a new tenant
	5 - Added a new invoice successfully
	6 - Failed to add invoice due to technical error
	7 - Failed to add invoice because it already exists  
	8 - Successful update of rent payment
	9 - Failed attempt of rent payment
    10 - New location added successfully.
    11 - New location failed to add.
    12 - Tenant updated successfully.
    13 - Tenant update failed.

****************************************************************************************/

            if (isset($_GET['state'])) {
            	$err_state=$_GET['state'];

            	if ($err_state==1) {

            		$error="<div id='dlt' class='alert alert-slim w3-card-4 alert-success fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div> <i class='fa fa-building fa-3x'></i> </div>
                <strong>We've got that! ...<br></strong>
                 <p> The new house has been added and listed successfully.</p>
            
            </div>";
            		
            	}
            	elseif ($err_state==2) 
            	{
            		$error="<div id='dlt' class='alert alert-slim w3-card-4 alert-danger fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-frown-o fa-3x'></i></div>
                <strong>OOops!...</strong><br>
                <p>It seems we had a little problem adding the new house. Please try again.</p>  <br>
               
            <br>
            
            </div>";
            	}
            	elseif ($err_state==3) 
            	{
            		$error="<div id='dlt' class='alert alert-slim w3-card-4 alert-success fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div> <i class='fa fa-user fa-3x'></i> </div>
                <strong>Great to have a new tenant!<br></strong>
                 <p> The new tenant has been admitted successfully.</p>
            
            </div>";
            	}
            	elseif ($err_state==4) 
            	{
            		$error="<div id='dlt' class='alert alert-slim w3-card-4 alert-danger fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-frown-o fa-3x'></i></div>
                <strong>Ooops! This was embarrasing...</strong><br>
                <p>It seems we had a little problem admitting the new tenant. Please try again.</p>  <br>
               
            <br>
            
            </div>";
            	}
            	elseif ($err_state==5) 
            	{
            		$error="<div id='dlt' class='alert alert-slim w3-card-4 alert-success fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-credit-card fa-3x'></i></div>
                <strong>All is good!</strong><br>
                <p>The new rent invoice was created successfully.</p>  <br>
               
            <br>
            
            </div>";
            	}
            	elseif ($err_state==6) 
            	{
            		$error="<div id='dlt' class='alert alert-slim w3-card-4 alert-danger fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-frown-o fa-3x'></i></div>
                <strong>Ooops!This was embarrasing...</strong><br>
                <p>It seems we had a little problem admitting the new rental invoice. Please try again. </p> <br>
               
            <br>
            
            </div>";
            	}
            	elseif ($err_state==7) 
            	{
            		$error="<div id='dlt' class='alert alert-slim w3-card-4 alert-info fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-frown-o fa-3x'></i></div>
                <strong>The client invoice already exists</strong><br>
                <p>It seems like you have already created this month's rental invoice for this tenant.</p>  <br>
               
            <br>
            
            </div>";
            	}
            	elseif ($err_state==8) {
            		$error="<div id='dlt' class='alert alert-slim w3-card-4 alert-success fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-money fa-3x'></i></div>
                <strong>Payment has been updated!</strong><br>
                <p>The new rent payment has been made successfully.</p>  <br>
               
            <br>
            
            </div>";
            	}
            	elseif ($err_state==9) {
            		$error="<div id='dlt' class='alert alert-slim w3-card-4 alert-danger fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-frown-o fa-3x'></i></div>
                <strong>The was a little problem</strong><br>
                <p>We faced a problem when we tried to update the payment. Please try it again</p>  <br>
               
            <br>
            
            </div>";
            	}
                elseif ($err_state==10) {
                    $error="<div id='dlt' class='alert alert-slim w3-card-4 alert-success fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-map-marker fa-3x'></i></div>
                <strong>A new location was added!</strong><br>
                <p>A new location was added successfully.</p>  <br>
               
            <br>
            
            </div>";
                }
                elseif ($err_state==11) {
                    $error="<div id='dlt' class='alert alert-slim w3-card-4 alert-danger fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-map-marker fa-3x'></i></div>
                <strong>Failed to add new location...</strong><br>
                <p>The system failed to add the new location. please try again.</p>  <br>
               
            <br>
            
            </div>";
                }
            elseif ($err_state==12) {
                    $error="<div id='dlt' class='alert alert-slim w3-card-4 alert-success fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-user fa-3x'></i></div>
                <strong>Tenant Record Updated successfully!</strong><br>
                <p>The tenant records have been updated successfully.</p>  <br>
               
            <br>
            
            </div>";
                }

            elseif ($err_state==13) {
                    $error="<div id='dlt' class='alert alert-slim w3-card-4 alert-danger fade in'> 
                <h1><a href=\"index.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a> </h1>
                <div class='center'><i class='fa fa-user fa-3x'></i></div>
                <strong>Failed to update tenant record...</strong><br>
                <p>The system failed to update the tenant records. please try again.</p>  <br>
               
            <br>
            
            </div>";
                }
            }


?>