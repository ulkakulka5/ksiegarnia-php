
CREATE DATABASE ksiegarnia;


USE ksiegarnia;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client') DEFAULT 'client',
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    publisher VARCHAR(255) NOT NULL
);

CREATE TABLE orders_archive (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_price DECIMAL(10, 2),
    status TINYINT(1),
    book_id INT,
    date_created TIMESTAMP 
);


CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_price DECIMAL(10, 2) NOT NULL,
    status BOOL DEFAULT FALSE,
    book_id INT,
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);




INSERT INTO users (email, password, role) 
VALUES ('admin@example.com', 'zsme123', 'admin'),('z@z.z','z','client'); 




INSERT INTO books (title, author, description, price, quantity, publisher) VALUES
('W pustyni i w puszczy', 'Henryk Sienkiewicz', 'Przygody Stasia i Nel w Afryce.', 29.99, 10, 'Wydawnictwo XYZ'),
('Quo Vadis', 'Henryk Sienkiewicz', 'Powieść historyczna z czasów Nerona.', 34.99, 5, 'Wydawnictwo ABC'),
('Pan Tadeusz', 'Adam Mickiewicz', 'Ostatni zajazd na Litwie. Koszmar dla uczniów małych i dużych :)', 24.99, 7, 'Wydawnictwo 123'),
('Igrzyska śmierci', 'Suzanne Collins', 'Historia Katniss Everdeen i dystopijnego państwa Panem.', 39.99, 8, 'Wydawnictwo QWE'),
('Fortnite: Bitwa o bezludną wyspę', 'Nick Eliopulos', 'Przygody z popularnej gry Fortnite.', 19.99, 15, 'Wydawnictwo ZXC'),
('W pustyni i w puszczy', 'Henryk Sienkiewicz', 'Przygody Stasia i Nel w Afryce.', 19.50, 5, 'Inne Wydawnictwo'),
('Quo Vadis', 'Henryk Sienkiewicz', 'Powieść historyczna z czasów Nerona. EDYCJA JUBILEUSZOWA', 49.99, 20, 'Inne Wydawnictwo'),
('Harry Potter and the Philosopher''s Stone', 'J.K. Rowling', 'The first book in the Harry Potter series.', 19.99, 20, 'Publisher XYZ'),
('Harry Potter and the Chamber of Secrets', 'J.K. Rowling', 'The second book in the Harry Potter series.', 24.99, 15, 'Publisher ABC'),
('Harry Potter and the Prisoner of Azkaban', 'J.K. Rowling', 'The third book in the Harry Potter series.', 29.99, 18, 'Publisher 123'),
('Harry Potter and the Goblet of Fire', 'J.K. Rowling', 'The fourth book in the Harry Potter series.', 34.99, 25, 'Publisher QWE'),
('Harry Potter and the Order of the Phoenix', 'J.K. Rowling', 'The fifth book in the Harry Potter series.', 39.99, 22, 'Publisher ZXC'),
('To Kill a Mockingbird', 'Harper Lee', 'A novel set in the American South during the 1930s.', 14.99, 30, 'Publisher RST'),
('1984', 'George Orwell', 'A dystopian novel set in a totalitarian society.', 16.99, 28, 'Publisher UVW'),
('The Great Gatsby', 'F. Scott Fitzgerald', 'A novel depicting the decadence of the Jazz Age.', 18.99, 27, 'Publisher IJK'),
('Pride and Prejudice', 'Jane Austen', 'A classic romantic novel set in early 19th-century England.', 20.99, 35, 'Publisher LMN'),
('The Catcher in the Rye', 'J.D. Salinger', 'A story of teenage angst and alienation in post-war America.', 22.99, 40, 'Publisher OPQ'),
('Harry Potter and the Philosopher''s Stone', 'J.K. Rowling', 'The first book in the Harry Potter series.', 22.99, 20, 'Publisher JKL'),
('Harry Potter and the Chamber of Secrets', 'J.K. Rowling', 'The second book in the Harry Potter series.', 27.99, 15, 'Publisher MNO'),
('Harry Potter and the Prisoner of Azkaban', 'J.K. Rowling', 'The third book in the Harry Potter series.', 32.99, 18, 'Publisher PQR'),
('Harry Potter and the Goblet of Fire', 'J.K. Rowling', 'The fourth book in the Harry Potter series.', 37.99, 25, 'Publisher STU'),
('Harry Potter and the Order of the Phoenix', 'J.K. Rowling', 'The fifth book in the Harry Potter series.', 42.99, 22, 'Publisher VWX');


DELIMITER //
CREATE TRIGGER before_user_delete
BEFORE DELETE ON users
FOR EACH ROW
BEGIN
    INSERT INTO orders_archive (user_id, total_price, status, book_id, date_created)
    SELECT user_id, total_price, status, book_id, date_created
    FROM orders
    WHERE user_id = OLD.id;

    DELETE FROM orders WHERE user_id = OLD.id;
END//
DELIMITER ;

