<?php
session_start();
include 'db/poldb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // db search
    $sql = "SELECT id, password, role FROM users WHERE email = '$email'";
    $result = mysqli_query($pol, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        // pass???
        if ($password == $row['password']) {
            // save
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_role'] = $row['role'];
            $_SESSION['user_email'] = $row['email'];
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Nieprawidłowe hasło.";
        }
    } else {
        $error_message = "Nie znaleziono użytkownika z podanym emailem.";
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
    <title>Logowanie</title>


<!--error -->
<?php
    if (isset($error_message)) {
        echo "<p style='color: red;'><b>$error_message</b></p>";
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
    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Hasło:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Zaloguj</button>
    </form>
    <nav>
        <a href="index.php" class="back-link">Powrót do strony głównej</a>
    </nav>
</body>
<footer>
         Urszula Zachara, 2024 
    </footer>

</html>
