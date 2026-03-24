<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['reset_email'])){
header("Location: login.php");
exit();
}

$msg = "";

if(isset($_POST['password'])){

$email = $_SESSION['reset_email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
mysqli_query($conn,"
UPDATE users
SET password='$password'
WHERE email='$email'
");

unset($_SESSION['reset_email']);

$msg = "Password updated successfully";

}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5 mb-5" style="max-width:450px">

<h3>Reset Password</h3>

<?php if($msg){ ?>

<div class="alert alert-success">
<?php echo $msg; ?>
</div>

<a href="login.php" class="btn btn-success w-100">
Login Now
</a>

<?php } else { ?>

<form method="POST">

<input type="password" name="password"
class="form-control mb-3"
placeholder="New Password" required>

<button class="btn btn-primary w-100">
Update Password
</button>

</form>

<?php } ?>

</div>

<?php include 'includes/footer.php'; ?>