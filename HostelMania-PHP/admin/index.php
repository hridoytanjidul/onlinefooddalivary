<?php
include("../connection/connect.php");

if (!empty($_SESSION["adm_id"])) {
    header("Location: dashboard.php");
    exit;
}

$message = '';
if (isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = "Please enter your username and password.";
    } else {
        $stmt = $db->prepare("SELECT adm_id, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row && verify_password($password, $row['password'])) {
            // Replace a legacy MD5 hash with a secure one.
            if (password_needs_rehash($row['password'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $upd = $db->prepare("UPDATE admin SET password = ? WHERE adm_id = ?");
                $upd->bind_param("si", $newHash, $row['adm_id']);
                $upd->execute();
            }

            session_regenerate_id(true);
            $_SESSION["adm_id"] = $row['adm_id'];
            header("Location: dashboard.php");
            exit;
        }

        $message = "Invalid Username or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
<link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

      <link rel="stylesheet" href="css/login.css">


</head>

<body>


<div class="container">
  <div class="info">
    <h1>Admin Panel </h1>
  </div>
</div>
<div class="form">
  <div class="thumbnail"><img src="images/manager.png" alt="Admin"/></div>
  <span style="color:red;"><?php echo e($message); ?></span>
  <form class="login-form" action="index.php" method="post">
    <input type="text" placeholder="Username" name="username" value="<?php echo e($_POST['username'] ?? ''); ?>"/>
    <input type="password" placeholder="Password" name="password"/>
    <input type="submit"  name="submit" value="Login" />

  </form>

</div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src='js/index.js'></script>
</body>

</html>
