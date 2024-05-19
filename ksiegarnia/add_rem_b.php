<?php
session_start();
include 'db/poldb.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$current_user_id = $_SESSION['user_id'];


$sql = "SELECT id, title, author, quantity, price FROM books";
$result = mysqli_query($pol, $sql);


if(isset($_POST['create_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    
    $sql_create_book = "INSERT INTO books (title, author, quantity, price) VALUES ('$title', '$author', $quantity, $price)";
    if(mysqli_query($pol, $sql_create_book)) {
        $_SESSION['success_message'] = 'Książka została pomyślnie dodana.';
        header("Location: add_rem_b.php");
        exit();
    } else {
        $_SESSION['error_message'] = 'Błąd podczas dodawania książki.';
        header("Location: add_rem_b.php");
        exit();
    }
}


if(isset($_GET['delete_book'])) {
    $book_id = $_GET['delete_book'];

    
    $sql_delete_book = "DELETE FROM books WHERE id = $book_id";
    if(mysqli_query($pol, $sql_delete_book)) {
        $_SESSION['success_message'] = 'Książka została pomyślnie usunięta.';
        header("Location: add_rem_b.php");
        exit();
    } else {
        $_SESSION['error_message'] = 'Błąd podczas usuwania książki.';
        header("Location: add_rem_b.php");
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
            text-align: left; }

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
        <label for="title">Tytuł:</label>
        <input type="text" name="title" id="title" required>
        <label for="author">Autor:</label>
        <input type="text" name="author" id="author" required>
        <label for="quantity">Ilość:</label>
        <input type="number" name="quantity" id="quantity" required min='1'>
        <label for="price">Cena:</label>
        <input type="number" name="price" id="price" step="0.01" required min='0.1'>

        <button type="submit" name="create_book">Dodaj książkę</button>
    </form>

    <table>
        <tr>
            <th>Tytuł</th>
            <th>Autor</th>
            <th>Ilość</th>
            <th>Cena</th>
            <th>Akcja</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['author'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td><button class='delete-button' onclick='deleteBook(" . $row['id'] . ")'>Usuń</button></td>";
            echo "</tr>";
        }
        ?>
    </table>

    </table>


    <script>
        function deleteBook(bookId) {
            if (confirm("Czy na pewno chcesz usunąć tę książkę?")) {
              
                window.location.href = "add_rem_b.php?delete_book=" + bookId;
            }
        }
    </script>
<br><br>

<nav style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="back-link">Dodaj dostawę</a>
    </nav>
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

