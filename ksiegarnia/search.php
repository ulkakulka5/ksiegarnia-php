<?php
session_start();
include 'db/poldb.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$search_query = ''; 
$result = null; 


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_query'])) {
    $search_query = $_POST['search_query'];
    $sql = "SELECT * FROM books WHERE title LIKE '%$search_query%'";
} else {
    $sql = "SELECT * FROM books";
}

$result = mysqli_query($pol, $sql);


if (isset($_POST['buy_book'])) {
    $book_id = $_POST['book_id'];
    $sql_check_quantity = "SELECT quantity, price FROM books WHERE id = $book_id";
    $result_check_quantity = mysqli_query($pol, $sql_check_quantity);
    
    if ($result_check_quantity) {
        $row = mysqli_fetch_assoc($result_check_quantity);
        $quantity = $row['quantity'];
        $price = $row['price'];
        
        if ($quantity > 0) {
            
            $sql_update = "UPDATE books SET quantity = quantity - 1 WHERE id = $book_id";
            mysqli_query($pol, $sql_update);
            
           
            $user_id = $_SESSION['user_id'];
            $total_price = $price; 
            $status = 1; 
        } else {
           
            $user_id = $_SESSION['user_id'];
            $total_price = 0.00; /
            $status = 0; 
        }
        
        $date = date("Y-m-d H:i:s");
        $sql_insert_order = "INSERT INTO orders (user_id, total_price, status, book_id, date_created) VALUES ($user_id, $total_price, $status, $book_id, '$date')";
        mysqli_query($pol, $sql_insert_order);
        
        if ($status == 1) {
            echo "<script>alert('Książka została pomyślnie zakupiona.');</script>";
        } else {
            echo "<script>alert('Przepraszamy, brak dostępnych egzemplarzy tej książki.');</script>";
        }
        
        echo "<meta http-equiv='refresh' content='0'>"; 
    } else {
        echo "<script>alert('Błąd pobierania ilości książek.');</script>";
    }
}

mysqli_close($pol);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Urszula Zachara">
    <meta name="description" content="Księgarnia internetowa, projekt">
    <meta name="keywords" content="książki, księgarnia, strona internetowa">
    <title>Zasoby</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
    body, html {
    height:250%;
        }
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
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
    
            form {
                margin: 2em auto;
                padding: 2em;
                background-color: white;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                max-width: 400px;
            }
    
            label {
                display: block;
                margin-bottom: 0.5em;
            }
    
            input[type="text"], input[type="email"], input[type="password"] {
                width: calc(100% - 22px);
                padding: 10px;
                margin-bottom: 1em;
                border: 1px solid #ccc;
                border-radius: 5px;
            }
    
            button {
                background-color: #4CAF50;
                color: white;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
                border-radius: 5px;
            }
    
            table {
                border-collapse: collapse;
                width: 100%;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                background-color: white;
            }
    
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
    
            th {
                background-color: #f2f2f2;
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
            }
    
            .back-link:hover {
                background-color: #45a049;
            }
    
            form .buy-button {
                background-color: #4CAF50;
                color: white;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
                border-radius: 5px;
                margin:  auto;
                display: block; 
                width: 80px; 
                text-align: center; 
            }  
    </style>
</head>
<body>
<header>
    <h1>Zasoby księgarni</h1>
</header>

<form method="post">
    <label for="search_query">Wyszukaj książkę:</label>
    <input type="text" name="search_query" id="search_query" value="<?php echo htmlspecialchars($search_query); ?>" required>
    <button type="submit">Szukaj</button>
</form>

<nav style="text-align: center; margin-bottom: 20px;">
    <a href="index.php" class="back-link">Powrót do strony głównej</a>
</nav>

<?php
if ($result && mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>Tytuł</th><th>Autor</th><th>Wydawnictwo</th><th>Cena</th><th>Ilość</th><th>Akcja</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['author']) . "</td>";
        echo "<td>" . htmlspecialchars($row['publisher']) . "</td>";
        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
        echo "<td>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='book_id' value='" . $row['id'] . "'>";
        echo "<input type='hidden' name='total_price' value='" . $row['price'] . "'>"; // Dodanie ukrytego pola z ceną
        echo "<button type='submit' class='buy-button' name='buy_book'>Kup</button>"; // Dodanie klasa 'buy-button'
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Brak książek spełniających kryteria wyszukiwania.</p>";
}
?>

<footer>
    Urszula Zachara, 2024
</footer>
</body>
</html>
