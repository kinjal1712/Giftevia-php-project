<?php
session_start();
include 'includes/db.php';

/* Already logged in redirect */

if(isset($_SESSION['user_id'])){

    if(strtolower($_SESSION['user_role']) == 'admin'){
        header("Location: admin/dashboard.php");
    }else{
        header("Location: index.php");
    }
    exit();
}

include 'includes/header.php';

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){

        echo "<script>
        document.addEventListener('DOMContentLoaded',function(){
            Swal.fire({
                icon:'error',
                title:'All fields are required',
                confirmButtonColor:'#C05C75'
            });
        });
        </script>";

    } else {

        $stmt = $conn->prepare("SELECT id,name,email,password,role FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){

            $user = $result->fetch_assoc();

            if(password_verify($password,$user['password'])){

                /* SESSION SET */

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                /* ADMIN LOGIN */

                if(strtolower($user['role']) == 'admin'){

                    echo "<script>
                    document.addEventListener('DOMContentLoaded',function(){
                        Swal.fire({
                            icon:'success',
                            title:'Welcome Admin 👑',
                            text:'Redirecting to dashboard...',
                            confirmButtonColor:'#C05C75'
                        }).then(()=>{
                            window.location='admin/dashboard.php';
                        });
                    });
                    </script>";

                } else {

                    /* USER LOGIN */

                    echo "<script>
                    document.addEventListener('DOMContentLoaded',function(){
                        Swal.fire({
                            icon:'success',
                            title:'Welcome back 💖',
                            text:'Login Successful',
                            confirmButtonColor:'#C05C75'
                        }).then(()=>{
                            window.location='index.php';
                        });
                    });
                    </script>";
                }

            } else {

                echo "<script>
                document.addEventListener('DOMContentLoaded',function(){
                    Swal.fire({
                        icon:'error',
                        title:'Wrong Password',
                        confirmButtonColor:'#C05C75'
                    });
                });
                </script>";
            }

        } else {

            echo "<script>
            document.addEventListener('DOMContentLoaded',function(){
                Swal.fire({
                    icon:'error',
                    title:'Email not found',
                    confirmButtonColor:'#C05C75'
                });
            });
            </script>";
        }
    }
}
?>

<div class="auth-wrapper">
    <div class="auth-box">

        <h2>Login</h2>

        <form method="POST">

            <div class="input-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <a href="forgot_password.php" style="font-size:14px;">Forgot Password?</a>

            <button type="submit" name="login" class="auth-btn">
                Login
            </button>

        </form>

        <div class="auth-switch">
            Don't have an account?
            <a href="register.php">Register</a>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>