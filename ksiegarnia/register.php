<?php
session_start();
include 'db/poldb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password']; 

    if ($password !== $confirm_password) {
        $error_message = "Hasła się nie zgadzają.";
    } else {
        $sql_check_email = "SELECT id FROM users WHERE email = '$email'";
        $result = mysqli_query($pol, $sql_check_email);

        if (mysqli_num_rows($result) > 0) {
            $error_message = "Podany email jest już zarejestrowany.";
        } else {
            $sql_register = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
            if (mysqli_query($pol, $sql_register)) {
               
                $sql_get_user_id = "SELECT id FROM users WHERE email = '$email'";
                $result = mysqli_query($pol, $sql_get_user_id);
                $row = mysqli_fetch_assoc($result);
                $user_id = $row['id'];
                
                $_SESSION['user_id'] = $user_id; 
                $_SESSION['user_role'] = 'user';
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Błąd podczas rejestracji: " . mysqli_error($pol);
            }
        }
    }
}

mysqli_close($pol);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta name="author" content="Urszula Zachara">
    <meta name="description" content="Księgarnia internetowa, projekt">
    <meta name="keywords" content="książki, księgarnia, strona internetowa">
    <meta charset="UTF-8">
    <title>Rejestracja</title>

    <!--error mess-->
    <?php
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

    <link rel="stylesheet" href="css/style.css">
    <style>
.back-link {
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
            display: inline-block;
            margin-top: 20px;
            text-align: center;
        }

        .back-link:hover {
            background-color: #45a049;
        }
        nav {
    text-align: center;
}

footer {
    background-color: #4CAF50;
    color: white;
    text-align: center;
    padding: 1em;
    position: fixed;; 
    bottom: 0;
    width: 100%;
    margin: 20px auto 0; 
}

        </style>
</head>
<body>
    <form action="register.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Hasło:</label>
        <input type="password" name="password" id="password" required>
        <label for="confirm_password">Potwierdź hasło:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <button type="submit">Zarejestruj</button>
    </form>
</body>
<nav>
        <a href="index.php" class="back-link">Powrót do strony głównej</a>
    </nav>
<footer>
         Urszula Zachara, 2024 
    </footer>

</html>
