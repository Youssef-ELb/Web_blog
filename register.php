<?php
session_start();
include('config.php'); // Include your database configuration

// Initialize variables
$username = "";
$email = "";
$errors = array();

// Check if the register button was clicked
if (isset($_POST['register_btn'])) {
    // Retrieve form data and trim whitespace
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password_confirmation = trim($_POST['password_confirmation']);

    // Validation
    if (empty($username)) { array_push($errors, "Username is required."); }
    if (empty($email)) { array_push($errors, "Email is required."); }
    if (empty($password)) { array_push($errors, "Password is required."); }
    if ($password !== $password_confirmation) { array_push($errors, "Passwords do not match."); }

    // Check uniqueness of username and email
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            array_push($errors, "Username or Email already exists.");
        }
        $stmt->close();
    }

    // Proceed with registration if no errors
    if (empty($errors)) {
        $password = md5($password); // Encrypt the password
        $role = 'Author'; // Default role
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, role, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $role, $password);
        $success = $stmt->execute();

        if ($success) {
            $reg_user_id = mysqli_insert_id($conn);
            // Store user data in session
            $_SESSION['user_id'] = $reg_user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['message'] = "You are now registered and logged in.";
         
            // Redirect based on role
            if ($role === 'Admin') {
                header('location: dashboard.php'); // Admin dashboard
            } else {
                header('location: index.php'); // Homepage for authors and guests
            }
            exit();
        } else {
            array_push($errors, "Failed to register. Please try again.");
        }
        $stmt->close();
    }
}

// Load the HTML form or errors here


include('includes/public/head_section.php');
// Include the HTML form below the PHP code
?>

<!DOCTYPE html>
<html lang="en">
<title>MyWebSite | Register </title>
</head>
<body>
	<!-- Container -->
	<div class="container">

		<!-- Navbar -->
		<?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
		<!-- // Navbar -->

		<!-- Registration Form -->
		<div style="width: 40%; margin: 20px auto;">
			<form method="post" action="register.php">
				<h2>Register on MyWebSite</h2>
				<!-- Errors -->
				<?php include(ROOT_PATH . '/includes/public/errors.php') ?>
				<!-- Username -->
				<input type="text" name="username" value="<?php echo $username; ?>" placeholder="Username">
				<!-- Email -->
				<input type="email" name="email" value="<?php echo $email; ?>" placeholder="Email">
				<!-- Password -->
				<input type="password" name="password" placeholder="Password">
				<!-- Password Confirmation -->
				<input type="password" name="password_confirmation" placeholder="Password confirmation">
				<!-- Register Button -->
				<button type="submit" class="btn" name="register_btn">Register</button>
				<p>
					Already a member? <a href="login.php">Sign in</a>
				</p>
			</form>
		</div>
		<!-- // Registration Form -->

	</div>
	<!-- // Container -->

	<!-- Footer -->
	<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
	<!-- // Footer -->
</body>
</html>