<?php
include 'includes/db.php';
include 'includes/header.php';

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {

        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Email Already Registered!',
                confirmButtonColor: '#C97B84'
            });
        </script>
        ";

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        $stmt->execute();

        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful!',
                confirmButtonColor: '#C97B84'
            }).then(() => {
                window.location = 'login.php';
            });
        </script>
        ";

        $stmt->close();
    }

    $check->close();
}
?>

<div class="auth-wrapper">
    <div class="auth-box">

        <h2>Create Account</h2>

        <form method="POST">

            <div class="input-group">
                <input type="text" name="name" placeholder="Full Name" required>
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" name="register" class="auth-btn">
                Register
            </button>
        </form>

        <p class="auth-switch">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>

    </div>
</div>

<?php include 'includes/footer.php'; ?>