<?php
session_start();
include 'db/poldb.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$current_user_id = $_SESSION['user_id'];


$sql = "SELECT id, email, password FROM users WHERE id != $current_user_id";
$result = mysqli_query($pol, $sql);


if (isset($_POST['create_user'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

   
    $sql_create_user = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
    if (mysqli_query($pol, $sql_create_user)) {
        echo "<script>alert('Użytkownik został pomyślnie utworzony.');</script>";
        
        echo "<script>window.location.replace('add_rem_c.php');</script>";
        exit();
    } else {
        echo "<script>alert('Błąd podczas tworzenia użytkownika.');</script>";
    }
}


if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];

    
    $sql_delete_user = "DELETE FROM users WHERE id = $user_id";
    if (mysqli_query($pol, $sql_delete_user)) {
        
        echo "<script>window.location.replace('add_rem_c.php');</script>";
        exit();
    } else {
        echo "<script>alert('Błąd podczas usuwania użytkownika.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta name="author" content="Urszula Zachara">
    <meta name="description" content="Księgarnia internetowa, projekt">
    <meta name="keywords" content="książki, księgarnia, strona internetowa">
    <meta charset="UTF-8">
    <title>Zarządzanie użytkownikami</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

       
        .delete-button {
            background-color: #ff3333;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

       
        form {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            max-width: 400px;
            width: auto;
            margin: 0 auto;
            text-align: center;
            margin-top: 20px;
        }

        form label {
            display: block;
            margin-bottom: 10px;
            text-align: left;
        }

        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        
        header {
            background-color: #4CAF50;
            color: white;
            padding: 1em;
            text-align: center;
        }

        nav a {
            margin: 0 1em;
            color: white;
            text-decoration: none;
        }

        footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 1em;
            position: fixed; 
            bottom: 0;
            width: 100%;
            margin: 20px auto 0; 
        }
    </style>
</head>
<body>
    <header>
        <h1>Zarządzanie użytkownikami</h1>
    </header>

    <table>
        <tr>
            <th>Email</th>
            <th>Hasło</th>
            <th>Akcja</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['password'] . "</td>";
            echo "<td><button class='delete-button' onclick='deleteUser(" . $row['id'] . ")'>Usuń</button></td>";
            echo "</tr>";
        }
        ?>
    </table>

    
    <form id="createUserForm" method="post">
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" required>
        <label for="password">Hasło:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" name="create_user">Utwórz użytkownika</button>
    </form>

    <script>
        function deleteUser(userId) {
            if (confirm("Czy na pewno chcesz usunąć tego użytkownika?")) {
                
                window.location.href = "add_rem_c.php?delete_user=" + userId;
            }
        }
    </script>
<br><br>
    <nav style="text-align: center; margin-bottom: 20px;">
        <a href="index.php" class="back-link">Powrót do strony głównej</a>
    </nav>
    
    <footer>
        Urszula Zachara, 2024
    </footer>
</body>
</html>

<?php
mysqli_close($pol);
?>
