<?php

namespace App\Repository;

use App\Model\Book;
use Framework\Database\ConnectionInterface;

class BookFunctions
{
    private ConnectionInterface $connection;
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function addBook(string $title,string $author,string $genre,string $description, int $user_id):void{
        $this->connection->query(
            "INSERT INTO books (title, author, genre, description, showable, user_id)VALUES (:title, :author, :genre, :description, :showable, :user_id)",
            $title, $author, $genre, $description, 0, $user_id );
    }

    public function getBooks(array $params): array{
        if ($this->filtersActive($params)) {
            $rows = $this->findByFilters($params);
        }
        else{
            $rows = $this->connection->query("SELECT * FROM books LIMIT 15");

        }
        $books = [];
        foreach($rows as $row){
            $avgStars = $this->getAverageStars($row['id']);
            $reviewCount = $this->numberOfReviews($row['id']);
            $books[] = new Book(
                $row['id'],
                $row['title'],
                $row['author'],
                $row['genre'],
                $row['description'],
                $row['user_id'],
                $row['showable'],
                $avgStars,
                $reviewCount
            );
        }
        return $books;
    }

    public function getBook(int $id): Book{
        $row = $this->connection->query("SELECT * FROM books WHERE id = :id", $id);
        $avgStars = $this->getAverageStars($id);
        $reviewCount = $this->numberOfReviews($id);
        return new Book(
            $row[0]['id'],
            $row[0]['title'],
            $row[0]['author'],
            $row[0]['genre'],
            $row[0]['description'],
            $row[0]['user_id'],
            $row[0]['showable'],
            $avgStars,
            $reviewCount
        );
    }

    public function makeBookVisible(int $bookId): void
    {
        $this->connection->query("UPDATE books SET showable = :showable WHERE id = :id", 1, $bookId);
    }

    public function makeBookHidden(int $bookId): void
    {
        $this->connection->query("UPDATE books SET showable = :showable WHERE id = :id", 0, $bookId);
    }

    private function getAverageStars(int $bookId): float{
        $row = $this->connection->query("SELECT ROUND(AVG(stars),1) as average FROM reviews WHERE book_id = :id;", $bookId);
        return (float) ($row['average'] ?? 0);
    }

    private function numberOfReviews(int $bookId): int{
        $row = $this->connection->query("SELECT COUNT(*) as reviewCount FROM reviews WHERE book_id = :id;", $bookId);
        return (int) ($row['reviewCount'] ?? 0);
    }

    public function deleteBook(int $bookId): void{
        $this->connection->query("DELETE FROM reviews WHERE book_id = :id;", $bookId);
    }

    private function findByFilters(array $filters): array{

        $sql = 'SELECT * FROM books WHERE 1=1';
        $params = [];

        if (!empty($filters['title'])) {
            $sql .= ' AND title LIKE :title';
            $params['title'] = '%' . $filters['title'] . '%';
        }

        if (!empty($filters['author'])) {
            $sql .= ' AND author LIKE :author';
            $params['author'] = '%' . $filters['author'] . '%';
        }

        if (!empty($filters['genre'])) {
            $sql .= ' AND genre = :genre';
            $params['genre'] = $filters['genre'];
        }

        return $this->connection->query($sql, $params);
    }

    private function filtersActive(array $filters): bool{
        return !empty($filters['title']) || !empty($filters['author']) || !empty($filters['genre']);
    }
}