<?php
session_start();
include 'includes/db.php';

$msg = "";

if(isset($_POST['email'])){

$email = mysqli_real_escape_string($conn,$_POST['email']);

$q = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($q) > 0){

$_SESSION['reset_email'] = $email;

header("Location: reset_password.php");
exit();

}else{

$msg = "Email not found";

}

}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5 mb-5" style="max-width:450px">

<h3>Forgot Password</h3>

<p class="text-muted">Enter your registered email</p>

<?php if($msg){ ?>

<div class="alert alert-danger">
<?php echo $msg; ?>
</div>

<?php } ?>

<form method="POST">

<input type="email" name="email" class="form-control mb-3"
placeholder="Enter Email" required>

<button class="btn btn-primary w-100">
Continue
</button>

</form>

</div>

<?php include 'includes/footer.php'; ?>