<?php
  /* DATABASE CONNECTION*/
      require "db.php";
   /*DATABASE CONNECTION */
  if (isset($_POST['submit'])) 
  {
      //-- Get Employee Data --//
      $email = is_email($_POST['email']);
      $uname=is_username($_POST['uname']);
      $role=uncrack($_POST['role']);
      

      // CHECK IF EMPLOYEE EMAIL EXISTS //
      
      $sql = "SELECT `id` FROM `admin` WHERE `email` = '$email'";
      $stmt = mysqli_query($conn, $sql);


      if (mysqli_num_rows($stmt) > 0) {
          // email already EXISTS
            echo "Oops...This email already exists!";
            // die();
      }
      
      // END OF - CHECK IF EMPLOYEE EMAIL EXISTS //

      $password = password_hash($_POST['password'],PASSWORD_BCRYPT,array('cost'=>12));

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  $emailErr = "Invalid email format";
                  echo $emailErr;
                  //die();
                  $email = $_POST['email'];
               }
               else {

               }
      //-- Get Employee Data --//


      //-- Insert Data Into DB --//
      $sql = "INSERT INTO `admin` (`email`, `password`,`name`,`role`)
      VALUES ('$email','$password','$uname','$role')";
      //-- Insert Data Into DB --//

      //header('Location:../users.php?success');

      $stmt = $db->prepare($sql);

      try {
      $stmt->execute();
      header('Location:../users.php?added');

      }

     catch (Exception $e) {
        $e->getMessage();
        echo "Error";
    }

      }


?>
