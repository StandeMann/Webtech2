<?php

namespace App\Repository;

use App\Model\Book;
use PDO;

class BookFunctions
{
    private PDO $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addBook(string $title,string $author,string $genre,string $description, int $user_id):void{
        $query = $this->pdo->prepare(
            "INSERT INTO books (title, author, genre, description, showable, user_id)VALUES (:title, :author, :genre, :description, :showable, :user_id)");

        $query->execute([$title, $author, $genre, $description, 0, $user_id]);

    }

    public function getBooks(array $params): array{
        if ($this->filtersActive($params)) {
            $rows = $this->findByFilters($params);
        }
        else{
            $query = $this->pdo->prepare(
                "SELECT * FROM books LIMIT 15"
            );
            $query->execute();

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
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
        $query = $this->pdo->prepare("SELECT * FROM books WHERE id = :id");
        $query->execute([$id]);
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $avgStars = $this->getAverageStars($id);
        $reviewCount = $this->numberOfReviews($id);
        return new Book(
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

    public function makeBookVisible(int $bookId): void
    {
        $query = $this->pdo->prepare(
            "UPDATE books SET showable = :showable WHERE id = :id"
        );

        $query->execute([
            'showable' => 1,
            'id' => $bookId
        ]);
    }

    public function makeBookHidden(int $bookId): void
    {
        $query = $this->pdo->prepare(
            "UPDATE books SET showable = :showable WHERE id = :id"
        );

        $query->execute([
            'showable' => 0,
            'id' => $bookId
        ]);
    }

    private function getAverageStars(int $bookId): float{
        $query = $this->pdo->prepare("SELECT ROUND(AVG(stars),1) as average FROM reviews WHERE book_id = :id;");
        $query->execute(['id' => $bookId]);
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return (float) ($row['average'] ?? 0);
    }

    private function numberOfReviews(int $bookId): int{
        $query = $this->pdo->prepare("SELECT COUNT(*) as reviewCount FROM reviews WHERE book_id = :id;");
        $query->execute(['id' => $bookId]);
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['reviewCount'] ?? 0);
    }

    public function deleteBook(int $bookId): void{
        $query = $this->pdo->prepare("DELETE FROM books WHERE id = :id;");
        $query->execute([$bookId]);
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

        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        return $query->fetchAll();
    }

    private function filtersActive(array $filters): bool{
        return !empty($filters['title']) || !empty($filters['author']) || !empty($filters['genre']);
    }
}