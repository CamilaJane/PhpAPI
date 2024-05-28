<?php
header("Content-Type: application/json");

$host = 'localhost';
$db = 'library';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = new PDO($dsn, $user, $pass, $options);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT b.borrow_id, b.book_name, s.firstname, s.lastname, s.birthdate, s.email, s.course, s.yearblock, b.borrow_date, b.return_date
                        FROM BorrowedBooks b
                        INNER JOIN Students s ON b.student_id = s.student_id");
    $borrowedBooks = $stmt->fetchAll();
    echo json_encode($borrowedBooks);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $sql = "INSERT INTO BorrowedBooks (book_name, student_id, borrow_date, return_date) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$input['book_name'], $input['student_id'], $input['borrow_date'], $input['return_date']]);
    echo json_encode(['message' => 'Book borrowed successfully']);
}
?>
