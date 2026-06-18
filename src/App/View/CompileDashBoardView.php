<?php

namespace App\View;

use Framework\Http\Classes\Request;

class CompileDashBoardView
{
    private Request $request;
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function renderBooks(array $books): string{
        $user = $this->request->getUser();
        if (!$user || $user->getRole() == "user") {
            $books = $this->checkShowableBooks($books);
        }

        $html = "";
        foreach ($books as $book){
            if ($user && $user->getRole() != "user") {
                $button = "
                            <form action='/deleteBook/{$book->id}' method='POST'>
                            <button type='submit'>Verwijder Boek</button>
                            </form>";
            }
            else{
                $button = "";
            }
            $html .=
                "<a href='/bookDetail/{$book->id}' class='book-link'>
                    <div class='book-card'>
                        <div class='card-content'>
                            <h3>{$book->title} | ⭐ {$book->average} ({$book->reviewCount}) </h3>
                            <p class='author'>{$book->author}</p>
                            <p class='author'>{$book->genre}</p> 
                            <p>{$book->description}</p>
                            {$button}
                        </div>
                    </div>
                </a>";
        }

        return $html;
    }

    private function checkShowableBooks(array $books): array{
        $showableBooks = [];
        foreach ($books as $book){
            if ($book->showable === 1){
                $showableBooks[] = $book;
            }
        }
        return $showableBooks;
    }

    private function renderFilterValues(array $params, string $key): string{
        if (!empty($params[$key])){
            return $params[$key];
        }
        return "";
    }

    public function renderFilters(array $params): string{
        $titleValue = $this->renderFilterValues($params, "title");
        $authorValue = $this->renderFilterValues($params, "author");
        $genreValue = $this->renderFilterValues($params, "genre");
        return "<section class='filter-bar'>
                <form action='/' method='POST' class='filter-form'>
                    <input
                        type='text'
                        name='title'
                        placeholder='Zoek op titel'
                        value='{$titleValue}'
                    >
            
                    <input
                        type='text'
                        name='author'
                        placeholder='Zoek op auteur'
                        value='$authorValue'
                    >
            
                    <select name='genre'>
                        <option value=''>Alle genres</option>
                            <option value='Roman'>Roman</option>
                            <option value='Thriller'>Thriller</option>
                            <option value='Fantasy'>Fantasy</option>
                            <option value='Science Fiction'>Science Fiction</option>
                            <option value='Non-fictie'>Non-fictie</option>
                    </select>
            
                    <button type='submit'>Zoeken</button>
            
                </form>
            </section>";
    }

}