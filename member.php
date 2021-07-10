<?php
session_start();
require "includs/config.php";
?>
<?php include "includs/navbar.php" ?>
<?php include "includs/header.php" ?>
<?php
$action = "";
if (isset($_GET['action'])){
    $action = $_GET['action'];
}else{
    $action = "index";
}
?>
<?php if ($action =='index') : ?>
<?php
   $checkadmin = isset($_GET['check'])?'role!=0':'role=0';
    $stmt = $con->prepare('SELECT * FROM user WHERE '.$checkadmin);
    $stmt->execute();
    $users = $stmt->fetchAll();
    ?>
    <table class="table table-dark table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">User Name</th>
            <th scope="col">Email Address</th>
            <th scope="col">Full Name</th>
            <th scope="col">Photo</th>
            <th scope="col">Control</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user) : ?>
        <tr>

            <td><?= $user['id'] ?></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['fullname'] ?></td>
            <td><img src="assest/image/<?= $user['image'] ?>" style="height: 10vh" </td>
            <td>
                <a href="?action=show&Selection=<?= $user['id'] ?>" class="btn btn-primary">Show Data</a>
                    <a href="?action=edit&Selection=<?= $user['id'] ?>" class="btn btn-info">Edit Data</a>
                    <a href="?action=delete&Selection=<?= $user['id'] ?>" class="btn btn-danger">Delete Data</a>
            </td>
        </tr>
        </tbody>
        <?php endforeach; ?>
    </table>
    <a href="?action=create" class="btn btn-info">create New data</a>
<?php elseif ($action =='create') : ?>
    <div class="container">
        <h3 class="text-center pt-3">Insert Data</h3>
        <div class="user pt-4">
            <form method="POST" action="?action=store" enctype="multipart/form-data">
                <div class="mb-3">
                    <label  class="form-label">User Name</label>
                    <input type="text" class="form-control" name="usernmae">
                </div>
                <div class="mb-3">
                    <label  class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="mb-3">
                    <label  class="form-label">Password</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="mb-3">
                    <label  class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="fullname">
                </div>
                <div class="mb-3">
                    <label  class="form-label">Photo</label>
                    <input type="file" class="form-control" name="avater">
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
<?php elseif ($action =='store') : ?>
<?php
    if ($_SERVER['REQUEST_METHOD']=='POST'){
        $avater = $_FILES['avater'];
        $avaterName = $_FILES['avater']['name'];
        $avaterTmaption = $_FILES['avater']['tmp_name'];
        $avaterType = $_FILES['avater']['type'];
        $avaterError = $_FILES['avater']['error'];
        $avaterSize = $_FILES['avater']['size'];
        $exptionimage = array('image/jpeg','image/jpg','image/png');
        if (in_array($avaterType , $exptionimage)){
            $randname = rand(0 , 10000)."_".$avaterName;
            $dstmtionimage = "assest/image//".$randname;
            move_uploaded_file($avaterTmaption , $dstmtionimage);
        }
        $usernmae = $_POST['usernmae'];
        $email = $_POST['email'];
        $password = sha1($_POST['password']);
        $fullname = $_POST['fullname'];
        $fromError = array();
        if (strlen($usernmae)< 4 || empty($usernmae)){
            $fromError[] ="Insert User Name";
        }
        if (empty($email)){
            $fromError[] ="Insert Email Address";
        }
        if (empty($password)){
            $fromError[] = "Insert Password";
        }
        if (empty($fullname)){
            $fromError[] = "Insert Full Name";
        }
        if (empty($fromError)){
            $stmt = $con->prepare("INSERT INTO user (username , email , password , fullname , image , role) VALUES (?,?,?,?,?,0)");
            $stmt->execute(array($usernmae , $email , $password , $fullname,$randname));
            header("location:member.php?action=create");
        }else{
            foreach ($fromError as $error){
                echo "<div class='alert alert-danger'>.$error</div>";
            }
        }
    }
    ?>
<?php elseif ($action =='edit') : ?>
<?php
    $userid = isset($_GET['Selection'])&&is_numeric($_GET['Selection'])?intval($_GET['Selection']):0;
    $stmt = $con->prepare("SELECT * FROM user WHERE id =?");
    $stmt->execute(array($userid));
    $users = $stmt->fetch();
    $countuser = $stmt->rowCount();
    ?>
<?php if ($countuser > 0) : ?>
        <div class="container">
            <h3 class="text-center pt-3">Update Data</h3>
            <div class="user pt-4">
                <form method="POST" action="?action=update" enctype="multipart/form-data">
                    <input type="hidden" class="form-control" name="userid" value="<?= $users['id'] ?>">
                    <div class="mb-3">
                        <label  class="form-label">User Name</label>
                        <input type="text" class="form-control" name="username" value="<?= $users['username'] ?>">
                    </div>
                    <div class="mb-3">
                        <label  class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" value="<?= $users['email'] ?>">
                    </div>
                    <div class="mb-3">
                        <label  class="form-label">Password</label>
                        <input type="password" class="form-control" name="oldpassword">
                        <input type="hidden" class="form-control" name="newpassword" value="<?= $users['password']?>">
                    </div>
                    <div class="mb-3">
                        <label  class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="fullname" value="<?= $users['fullname'] ?>">
                    </div>
                    <div class="mb-3">
                        <label  class="form-label">Photo</label>
                        <input type="file" class="form-control" name="avater" value="<?= $users['image'] ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </form>
            </div>
        </div>
    <?php  else: ?>
    <?php header("location:member.php"); ?>
<?php endif; ?>
<?php elseif ($action =='update') :?>
<?php
    if ($_SERVER['REQUEST_METHOD']=='POST'){
        $avater = $_FILES['avater'];
        $avaterName = $_FILES['avater']['name'];
        $avaterTmaption = $_FILES['avater']['tmp_name'];
        $avaterType = $_FILES['avater']['type'];
        $avaterError = $_FILES['avater']['error'];
        $avaterSize = $_FILES['avater']['size'];
        $exptionimage = array('image/jpeg','image/jpg','image/png');
        if (in_array($avaterType , $exptionimage)){
            $randname = rand(0 , 10000)."_".$avaterName;
            $dstmtionimage = "assest/image//".$randname;
            move_uploaded_file($avaterTmaption , $dstmtionimage);
        }
        $avater = $_POST['avater'];
        $userid = $_POST['userid'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = empty($_POST['newpassword'])?$_POST['oldpassword']:sha1($_POST['newpassword']);
        $fullname = $_POST['fullname'];
        $stmt = $con->prepare('UPDATE user SET username =? , email =? , password =? , fullname =? , image =? WHERE id =?');
        $stmt->execute(array($username , $email , $password , $fullname , $randname , $userid));
        header("location:member.php");
    }
    ?>
<?php elseif ($action =='show') : ?>
<?php
    $userid = isset($_GET['Selection'])&&is_numeric($_GET['Selection'])?intval($_GET['Selection']):0;
    $stmt = $con->prepare('SELECT * FROM user WHERE id =?');
    $stmt->execute(array($userid));
    $users = $stmt->fetch();
    $countuser = $stmt->rowCount();
    ?>
 <?php if ($countuser > 0) : ?>
    <div class="container">
        <h3 class="text-center pt-3">Update Data</h3>
        <div class="user pt-4">
            <form method="POST" action="?action=update" enctype="multipart/form-data">
                <div class="mb-3">
                    <label  class="form-label">User Name</label>
                    <input type="text" class="form-control" name="username" value="<?= $users['username'] ?>">
                </div>
                <div class="mb-3">
                    <label  class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" value="<?= $users['email'] ?>">
                </div>
                <div class="mb-3">
                    <label  class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="fullname" value="<?= $users['fullname'] ?>">
                </div>
                <div class="mb-3">
                    <label  class="form-label">Photo</label>
                    <input type="text" class="form-control" name="avater" value="<?= $users['image'] ?>">
                </div>
                <a href="member.php" class="btn btn-dark">Back</a>
            </form>
        </div>
    </div>
    <?php else: ?>
    <?php  header("location:member.php"); ?>
 <?php endif; ?>
<?php elseif ($action =='delete') : ?>
<?php
    $userid = isset($_GET['Selection'])&&is_numeric($_GET['Selection'])?intval($_GET['Selection']):0;
    $stmt = $con->prepare("DELETE FROM user WHERE id =?");
    $stmt->execute(array($userid));
    header("location:member.php");
    ?>
<?php endif; ?>
<?php include "includs/footer.php"?>
