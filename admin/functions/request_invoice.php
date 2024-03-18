<?php 
/*
    THIS IS A STANDARD INPUT TEXTBOX ON NEW-PAYMENT.PHP
  '<div class="form-group">
        <label for="ddate">Invoice Due Date: </label>
      <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-user"></i></div>
          <input type="text" name="ddate" class="form-control" id="ddate" value="5" readonly=""> 
      </div>
    </div>'
*/
 
require_once "db.php";

if (isset($_GET["q"])) {
 //our user has requested an invoice. get invoice ID, then get related data from viewInvoice
  $invoiceId=$_GET['q'];
  
  $sql_invoice="SELECT * from `invoicesView` where `invoiceNumber`='$invoiceId'";
  $invoice_query=mysqli_query($conn,$sql_invoice);

  $record=mysqli_fetch_array($invoice_query,MYSQLI_BOTH);

  $tenantId=$record['tenantID'];
  $invoicedate=$record['dateOfInvoice'];
  $dueDate=$record['dateDue'];
  $amountDue=$record['amountDue'];

  echo "
  <label>Invoice Date: <i>$invoicedate</i> </label><br>
  <label>Invoice Due Date: <i>$dueDate</i> </label><br>
  <label>Expected Amount: <i>KSh. $amountDue</i> </label><br>
    <br>

  <div class='form-group hidden'>
        <label for='amount'>Expected Amount: </label>
      <div class='input-group'>
          <div class='input-group-addon'><i class='fa fa-usd'></i></div>
          <input type='text' name='amountDue' class='form-control' id='amount' value='$amountDue' readonly=''> 
      </div>
  </div>

  <div class='form-group hidden'>
        <label for='tenID'>Tetant ID.: </label>
      <div class='input-group'>
          <div class='input-group-addon'><i class='fa fa-user'></i></div>
          <input type='text' name='tenID' class='form-control' id='tenID' value='$tenantId' readonly=''> 
      </div>
  </div>


    ";

	
}

	

?>