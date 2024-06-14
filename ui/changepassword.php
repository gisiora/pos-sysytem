<?php

include_once 'connectdb.php';
session_start();


if($_SESSION['useremail']==""){
  header('location:../index.php');

}

if($_SESSION['role']=="Admin"){

  include_once("header.php");
}
else{
  include_once("headeruser.php");
}






if (isset($_POST['btnupdate'])) {

  $oldpassword = $_POST['old_password'];
  $newpassword = $_POST['new_password'];
  $confirmpassword = $_POST['confirm_password'];
  
  $email = $_SESSION['useremail'];
  $select = $pdo->prepare("SELECT * FROM tbl_user WHERE useremail = :email");
  $select->bindParam(':email', $email);
  $select->execute();
  
  $row = $select->fetch(PDO::FETCH_ASSOC);
  
  if ($row) {
    $password_db = $row['userpassword'];

    if ($oldpassword == $password_db) {
      if ($newpassword == $confirmpassword) {

        $update = $pdo->prepare("UPDATE tbl_user SET userpassword = :pass WHERE useremail = :email");
        $update->bindParam(':pass', $confirmpassword);
        $update->bindParam(':email', $email);

        if ($update->execute()) {
          $_SESSION['status'] = "Password updated successfully";
          $_SESSION['status_code'] = "success";
        } else {
          $_SESSION['status'] = "Password not updated successfully";
          $_SESSION['status_code'] = "error";
        }
      } else {
        $_SESSION['status'] = "New password does not match the confirmation password";
        $_SESSION['status_code'] = "error";
      }
    } else {
      $_SESSION['status'] = "Old password does not match";
      $_SESSION['status_code'] = "error";
    }
  } else {
    $_SESSION['status'] = "User not found";
    $_SESSION['status_code'] = "error";
  }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Change Password </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">

        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">

        <!-- /.col-md-6 -->
        <div class="col-lg-12">
          <!-- /.card -->
          <!-- Horizontal Form -->
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Change Password</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" action="" method="POST">
              <div class="card-body">
                <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-2 col-form-label"> Cornfirm Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword3" placeholder="Old Password" name="old_password">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword3" placeholder=" New Password" name="new_password">
                  </div>
                </div>


                <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-2 col-form-label"> Cornfirm Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword3" placeholder="Confirm New Password" name="confirm_password">
                  </div>
                </div>

                <div class="form-group row">
                  <div class="offset-sm-2 col-sm-10">

                  </div>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" class="btn btn-info" name="btnupdate">Update Password</button>

              </div>
              <!-- /.card-footer -->
            </form>
          </div>
          <!-- /.card -->


        </div>
        <!-- /.col-md-6 -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php

include_once("footer.php")

?>

<?php



if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
?>
  <script>
    Swal.fire({
      icon: '<?php echo $_SESSION['status_code']; ?>',
      title: '<?php echo $_SESSION['status']; ?>'
    })
  </script>



<?php
  unset($_SESSION['status']);
}








?>
