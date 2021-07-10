<?php
session_start();
$_SESSION['Lang'] = isset($_GET['lang'])?$_GET['lang']:'en';
if ($_SESSION['Lang']=='en'){
    include "lang/en.php";
}elseif ($_SESSION['Lang']=='ar'){
    include "lang/ar.php";
}else{
    include "lang/en.php";
}
require "includs/config.php";
?>
<?php
if ($_SERVER['REQUEST_METHOD']=='POST'){
    $adminUserNmae = $_POST['adminusernmae'];
    $adminPassword = $_POST['adminpassword'];
    $stmt = $con->prepare('SELECT * FROM user WHERE username=? AND password=? AND role!=0');
    $stmt->execute(array($adminUserNmae , $adminPassword));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    $dbcount = 1;
    if ($count == $dbcount){
        $_SESSION['ID'] = $row['id'];
        $_SESSION['USERNAME'] = $adminUserNmae;
        $_SESSION['EMAIL'] = $row['email'];
        $_SESSION['ROLE'] = $row['role'];
        $_SESSION['FULLNAME'] = $row['fullname'];
        header("location:dashboard.php");

    }else{
        echo "<div class='alert alert-danger'>Data Is Error</div>";
    }

}
?>
<?php include "includs/header.php" ?>
 <div class="container">
 <h3 class="text-center pt-3"><?= $lang['adminuser'] ?></h3>
 <div class="user pt-4">
     <a href="?lang=ar">اللغه العربيه</a>
     <a href="?lang=en">English</a>
 <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
  <div class="mb-3">
    <label  class="form-label"><?= $lang['username'] ?></label>
    <input type="text" class="form-control" name="adminusernmae">
  </div>
  <div class="mb-3">
    <label  class="form-label"><?= $lang['password'] ?></label>
    <input type="password" class="form-control" name="adminpassword">
  </div>
  <button type="submit" class="btn btn-primary"><?= $lang['login'] ?></button>
</form>
 </div>
 </div>
 <?php include "includs/footer.php"?>