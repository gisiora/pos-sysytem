<?php
include_once 'connectdb.php';
session_start();
include_once("header.php");

// Handle form submissions for saving, updating, and deleting categories
if (isset($_POST['btnsave'])) {
    $category = $_POST['txtcategory'];

    if (empty($category)) {
        $_SESSION['status'] = "Category field is Empty";
        $_SESSION['status_code'] = "warning";
    } else {
        // Check if the category already exists
        $check = $pdo->prepare("select * from tbl_category where category = :cat");
        $check->bindParam(':cat', $category);
        $check->execute();
        $existingCategory = $check->fetch(PDO::FETCH_ASSOC);

        if ($existingCategory) {
            $_SESSION['status'] = "Category already exists";
            $_SESSION['status_code'] = "warning";
        } else {
            // Insert category without specifying catid (auto-increment)
            $insert = $pdo->prepare("insert into tbl_category (category) values(:cat)");
            $insert->bindParam(':cat', $category);
            if ($insert->execute()) {
                $_SESSION['status'] = "Category Added Successfully";
                $_SESSION['status_code'] = "success";
            } else {
                $_SESSION['status'] = "Category Add Failed";
                $_SESSION['status_code'] = "warning";
            }
        }
    }
}

if (isset($_POST['btnupdate'])) {
    $catid = $_POST['txtcatid'];
    $category = $_POST['txtcategory'];

    if (empty($category)) {
        $_SESSION['status'] = "Category field is Empty";
        $_SESSION['status_code'] = "warning";
    } else {
        $update = $pdo->prepare("update tbl_category set category = :category where catid = :catid");
        $update->bindParam(':category', $category);
        $update->bindParam(':catid', $catid);
        if ($update->execute()) {
            $_SESSION['status'] = "Category Updated Successfully";
            $_SESSION['status_code'] = "success";
        } else {
            $_SESSION['status'] = "Category Update Failed";
            $_SESSION['status_code'] = "warning";
        }
    }
}

if (isset($_POST['btndelete'])) {
    // Ensure btndelete is set and not empty
    if (!empty($_POST['btndelete'])) {
        $delete = $pdo->prepare("delete from tbl_category where catid=:catid");
        $delete->bindParam(':catid', $_POST['btndelete']);
        if ($delete->execute()) {
            $_SESSION['status'] = "Deleted successfully";
            $_SESSION['status_code'] = "success";
        } else {
            $_SESSION['status'] = "Deletion failed";
            $_SESSION['status_code'] = "warning";
        }
    } else {
        $_SESSION['status'] = "Invalid operation";
        $_SESSION['status_code'] = "warning";
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
          <h1 class="m-0 text-dark">Category</h1>
        </div>
        <div class="col-sm-6"></div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4">
          <div class="card card-warning">
            <div class="card-header">
              <h5 class="m-0">Category Form</h5>
            </div>
            <form role="form" action="" method="post">
              <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Category</label>
                  <?php
                  if (isset($_POST['btnedit'])) {
                    $select = $pdo->prepare("select * from tbl_category where catid = :catid");
                    $select->bindParam(':catid', $_POST['btnedit'], PDO::PARAM_INT);
                    $select->execute();
                    $row = $select->fetch(PDO::FETCH_OBJ);

                    if ($row) {
                      echo '<input type="hidden" class="form-control" placeholder="Enter Category" value="' . htmlspecialchars($row->catid) . '" name="txtcatid" readonly>
                                                  <input type="text" class="form-control" placeholder="Enter Category" value="' . htmlspecialchars($row->category) . '" name="txtcategory">';
                    } else {
                      echo '<div class="alert alert-warning">No category found with the provided ID.</div>';
                    }
                  } else {
                    echo '<input type="text" class="form-control" placeholder="Enter Category" name="txtcategory">';
                  }
                  ?>
                </div>
                <div class="card-footer">
                  <?php
                  if (isset($_POST['btnedit'])) {
                    echo '<button type="submit" class="btn btn-info" name="btnupdate">Update</button>';
                  } else {
                    echo '<button type="submit" class="btn btn-warning" name="btnsave">Save</button>';
                  }
                  ?>
                </div>
              </div>
            </form>
            <!-- Closing form tag here -->
          </div>
        </div>

        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              <h5 class="m-0">Category List</h5>
            </div>
            <div class="card-body">
              <table   id="table_category"     class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Category</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Fetch categories ordered by catid ASC
                  $select = $pdo->prepare("select * from tbl_category order by catid ASC");
                  $select->execute();
                  while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                    echo '<tr>
                                                <td>' . htmlspecialchars($row->catid) . '</td>
                                                <td>' . htmlspecialchars($row->category) . '</td>
                                                <td>
                                                    <form action="" method="post">
                                                        <button type="submit" class="btn btn-primary" value="' . htmlspecialchars($row->catid) . '" name="btnedit">Edit</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="" method="post">
                                                        <button type="submit" class="btn btn-danger" value="' . htmlspecialchars($row->catid) . '" name="btndelete">Delete</button>
                                                    </form>
                                                </td>
                                              </tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>

<?php
include_once("footer.php");

// Display status message using SweetAlert
if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
  echo '<script>
            Swal.fire({
                icon: "' . $_SESSION['status_code'] . '",
                title: "' . $_SESSION['status'] . '"
            })
          </script>';
  unset($_SESSION['status']);
  unset($_SESSION['status_code']);
}
?>
<script>



$(document).ready( function () {
    $('#table_category').DataTable();
} );


</script>
