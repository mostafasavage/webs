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
<?php include "includs/navbar.php" ?>
<h3 class="text-center"><?= $lang['dashboard'] ?></h3>
<?php include "includs/header.php" ?>
<?php include "includs/footer.php"?>

 <div class="container">
     <div class="row">
         <?php
         $stmt = $con->prepare("SELECT count(id) FROM user WHERE role =0");
         $stmt->execute();
         $users = $stmt->fetchColumn();
         ?>
         <div class="col-3">
             <a href="member.php"><i class="fa fa-users fa-3x"></i></a>
         </div>
         <div class="col-3">
             <h5><?= $users ?></h5>
         </div>
         <div class="col-3">
             <h5>tast</h5>
         </div>
     </div>
 </div>

