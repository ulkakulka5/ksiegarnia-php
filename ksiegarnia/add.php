<?php
session_start();
include 'db/poldb.php';

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$sql = "SELECT id, title, author, quantity FROM books";
$result = mysqli_query($pol, $sql);


if(isset($_POST['add_books'])) {
    if(isset($_POST['selected_books']) && !empty($_POST['selected_books'])) {
        $selected_books = $_POST['selected_books'];
        $quantity = $_POST['quantity'];

        
        foreach($selected_books as $book_id) {
            $sql_update_quantity = "UPDATE books SET quantity = quantity + $quantity WHERE id = $book_id";
            mysqli_query($pol, $sql_update_quantity);
        }

        $_SESSION['success_message'] = 'Książki zostały pomyślnie dodane.';
        header("Location: add.php");
        exit();
    } else {
        $_SESSION['error_message'] = 'Proszę wybrać co najmniej jedną książkę.';
        header("Location: add.php");
        exit();
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
    <title>Zarządzanie książkami</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body, html {
    height:135%;
    overflow-y: auto; 
}

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
        form input[type="number"] {
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
            width: 200px;
        }

    </style>
</head>
<body>
    <header>
        <h1>Zarządzanie książkami</h1>
    </header>


    <form method="post">
        <table>
            <tr>
                <th>Tytuł</th>
                <th>Autor</th>
                <th>Ilość</th>
                <th>Wybierz</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['title'] . "</td>";
                echo "<td>" . $row['author'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td><input type='checkbox' name='selected_books[]' value='" . $row['id'] . "'></td>";
                echo "</tr>";
            }
            ?>
        </table>
        <label for="quantity">Ilość:</label>
        <input type="number" name="quantity" id="quantity" value="1" min="1" required>
        <button type="submit" name="add_books">Dodaj</button>
    </form>



    <nav style="text-align: center; margin-bottom: 20px;">
        <a href="index.php" class="back-link">Powrót do strony głównej</a>
    </nav>

    <footer>
        Urszula Zachara, 2024
    </footer>
</body>
</html>
