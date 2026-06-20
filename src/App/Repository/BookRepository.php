<?php

namespace App\Repository;

use App\Mapper\BookMapper;
use App\Model\Book;
use Framework\Database\Classes\IdentityMap;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Database\Interfaces\DataMapperInterface;
use Framework\Database\Interfaces\IdentityMapInterface;
use Framework\Database\Interfaces\RepositoryInterface;

class BookRepository implements RepositoryInterface {
    private DataMapperInterface $mapper;
    private IdentityMapInterface $identityMap;
    public function __construct(ConnectionInterface $connection){
        $this->mapper = new BookMapper($connection);
        $this->identityMap = new IdentityMap();
    }
    public function get(int $id): object
    {
        if ($this->identityMap->has($id)) {
            return $this->identityMap->get($id);
        }

        $object = $this->mapper->get($id);
        if (!$object) {
            throw new \Exception(strval($id));
        }

        $this->identityMap->add($object->rowid, $object);
        return $object;
    }

    function save(object $object): void{
        if (!$object) {
            throw new \Exception('Not found');
        }

        $this->identityMap->add($object->id, $object);
    }

    function remove($object): void
    {
        if (!$object) {
            throw new \Exception('Not found');
        }
        $this->identityMap->remove($object);
    }

    public function addBook(Book $book):void{
        $this->mapper->insert($book);
    }

    public function getBooks(array $params): array{
        if ($this->filtersActive($params)) {
            $rows = $this->findByFilters($params);
        }
        else{
            $rows = $this->mapper->select("SELECT * FROM books LIMIT 15");
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
        $row = $this->mapper->select("SELECT * FROM books WHERE id = :id", $id);
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

    public function changeBookVisible(int $bookId): void{
        $this->mapper->update($this::getBook($bookId));
    }


    private function getAverageStars(int $bookId): float{
        $row = $this->mapper->select("SELECT ROUND(AVG(stars),1) as average FROM reviews WHERE book_id = :id;", $bookId);
        return (float) ($row[0]['average'] ?? 0);
    }

    private function numberOfReviews(int $bookId): int{
        $row = $this->mapper->select("SELECT COUNT(*) as reviewCount FROM reviews WHERE book_id = :id;", $bookId);
//        var_dump($row);
//        exit();
        return (int) ($row[0]['reviewCount'] ?? 0);
    }

    public function deleteBook(int $bookId): void{
        $this->mapper->delete($this::getBook($bookId));
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

        return $this->mapper->select($sql, ...$params);
    }

    private function filtersActive(array $filters): bool{
        return !empty($filters['title']) || !empty($filters['author']) || !empty($filters['genre']);
    }
}