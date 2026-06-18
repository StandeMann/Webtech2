<?php

namespace Framework\Database;
use PDO;

class DatabaseMigrator
{
    public static function migrate(PDO $pdo):void{
        $pdo->exec("CREATE TABLE IF NOT EXISTS users(
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            role TEXT NOT NULL DEFAULT 'user')"
        );

        $pdo->exec("CREATE TABLE IF NOT EXISTS books(
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            author TEXT NOT NULL,
            genre TEXT NOT NULL,
            description TEXT NOT NULL,
            image BLOB,
            showable INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE)"
        );

        $pdo->exec("CREATE TABLE IF NOT EXISTS reviews(
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            book_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            description TEXT NOT NULL,
            stars INTEGER NOT NULL CHECK(stars >= 1 AND stars <= 5),
    
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE)"
        );
    }

    public function alterDatabase(PDO $pdo):void{
        $pdo->exec("
        ALTER TABLE reviews
        ADD COLUMN description TEXT");
    }

}