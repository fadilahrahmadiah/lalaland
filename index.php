<?php
session_start();
include 'db_connection.php';

// Logout handling
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Cek apakah user sudah login
$isLoggedIn = isset($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>beauté</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <nav>
            <div class="nav-left">
                <ul>
                    <li><a href="index.php">HOME</a></li>
                    <li><a href="#">SHOP</a></li>
                    <li><a href="#">ABOUT</a></li>
                </ul>
            </div>
            <div class="nav-center">
                <h1>beauté</h1>
            </div>
            <div class="nav-right">
                <ul>
                    <?php if ($isLoggedIn): ?>
                        <li><a href="?logout=true">LOGOUT</a></li>
                    <?php else: ?>
                        <li><a href="#auth-container">ACCOUNT</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <br><br><br><br>

    <div class="video-container">
        <video loop autoplay muted playsinline>
            <source src="MP4_VLOG.mp4" type="video/mp4" />
        </video>
    </div>

    <?php if (!$isLoggedIn): ?>
        <div class="auth-container" id="auth-container">
            <!-- Login Form -->
            <div class="auth-form-container" id="loginForm">
                <h2>Login</h2>
                <form method="POST" action="index.php">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
                <p>Don't have an account? <a href="javascript:void(0)" onclick="showRegisterForm()">Register here</a></p>
            </div>

            <!-- Register Form -->
            <div class="auth-form-container" id="registerForm" style="display:none;">
                <h2>Register</h2>
                <form method="POST" action="index.php">
                    <input type="text" name="name" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="register">Register</button>
                </form>
                <p>Already have an account? <a href="javascript:void(0)" onclick="showLoginForm()">Login here</a></p>
            </div>
        </div>
    <?php else: ?>
        <div id="mainContent">
            <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
            <p>Enjoy browsing the site!</p>
        </div>
    <?php endif; ?>

    <!-- Content Section -->
    <P class="products">
        our products
    </P>
    <main class="main2">
        <section class="content">
            <button class="scroll-left"> &lt; </button>
            <div class="cards-container">
                <div class="card tint">
                    <h2>Tint</h2>
                    <button class="readmore">Read More ..</button>
                </div>
                <div class="card blush">
                    <h2>Blush</h2>
                    <button class="readmore">Read More ..</button>
                </div>
                <div class="card cleanser">
                    <h2>Cleanser</h2>
                    <button class="readmore">Read More ..</button>
                </div>
            </div>
            <button class="scroll-right"> &gt; </button>
        </section>
    </main>

    <footer>
        <p>Copyright by Fadilah Rahmadiah 2024</p>
    </footer>

    <script>
        function showRegisterForm() {
            document.querySelector("#registerForm").style.display = 'block';
            document.querySelector("#loginForm").style.display = 'none';
        }

        function showLoginForm() {
            document.querySelector("#registerForm").style.display = 'none';
            document.querySelector("#loginForm").style.display = 'block';
        }
    </script>
</body>

</html>

<?php
// Handle Register
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please log in.');</script>";
    } else {
        echo "<script>alert('Error during registration!');</script>";
    }
    $stmt->close();
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $email;
            echo "<script>window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('User not found!');</script>";
    }
    $stmt->close();
}
?>
