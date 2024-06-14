<?php

include_once "ui/connectdb.php";
session_start();


if (isset($_POST["btn_login"])) {

  $useremail = $_POST['txt_email'];
  $password = $_POST['txt_password'];


  $select = $pdo->prepare("select * from tbl_user where useremail='$useremail' AND userpassword='$password'  ");
  $select->execute();


  $row = $select->fetch(PDO::FETCH_ASSOC);


  if (is_array($row)) {

    if ($row['useremail'] == $useremail and $row['userpassword'] == $password and $row['role'] == "Admin") {
  

      $_SESSION['status'] = "login Success by Admin";
      $_SESSION['status_code'] = "success";

      header('refresh:1; ui/dashboard.php');


      $_SEESSION['userid'] = $row['userid'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['useremail'] = $row['useremail'];
      $_SESSION['role'] = $row['role'];
    } else if ($row['useremail'] == $useremail and $row['userpassword'] == $password and $row['role'] == "user") {



      
      $_SESSION['status'] = "login Success By user";
      $_SESSION['status_code'] = "success";


      header('refresh:1; ui/user.php');

      $_SEESSION['userid'] = $row['userid'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['useremail'] = $row['useremail'];
      $_SESSION['role'] = $row['role'];
    }
  } else {



    // echo $success = "wrong password";
    $_SESSION['status'] = "Wrong  username or password or field is empty";
    $_SESSION['status_code'] = "Error";
  }
}




?>



<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>POS  | SYSTEM </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">




    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-header text-center">
        <a href="../../index2.html"><b>POS SYSTEM </b></a>
      </div>
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" name="txt_email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="txt_password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <p class="mb-1">
                <a href="forgot-password.html">I forgot my password</a>
              </p>
            </div>

            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block" name="btn_login">login</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <div class="social-auth-links text-center mb-3">

        </div>
        <!-- /.social-auth-links -->



      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- Toastr -->
  <script src="plugins/toastr/toastr.min.js"></script>
  <!-- AdminLTE App -->
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>





  <?php



  if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
  ?>
    <script>
      $(function() {
        const Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 5000
        });


        Toast.fire({
          icon: '<?php echo $_SESSION['status_code']; ?>',
          title: '<?php echo $_SESSION['status']; ?>'
        })
      });
    </script>



  <?php
    unset($_SESSION['status']);
  }








  ?>












</body>

</html>
