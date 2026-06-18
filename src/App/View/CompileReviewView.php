<?php

namespace App\View;

use App\Model\Book;
use Framework\Http\Classes\Request;

class CompileReviewView
{
    private Request $request;
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function renderBookDetail(Book $book, int $bookId): string{
        if ($book->showable == 0){
            $string = "Maak boek zichtbaar";
        }
        if ($book->showable == 1){
            $string = "Stop met boek tonen";
        }
        $user = $this->request->getUser();
        if (!$user) {
            return "<main class='book-details'>
                <div class='book-detail-container'>
                <div class='book-card'>            
                    <div class='book-info'>
                        <h1>{$book->title} ⭐ {$book->average} ({$book->reviewCount})</h1>
                        <h3>{$book->author}</h3>
        
                        <p class='description'>
                        {$book->description}
                        </p>
        
                        <div class='details'>
                            <p><strong>Genre:</strong> {$book->genre}</p>
                        </div>
                    </div>
                </div>
            </div>";
        }

        if ($user->getRole() == 'user') {
            return "<main class='book-details'>
                <div class='book-detail-container'>
                <div class='book-card'>            
                    <div class='book-info'>
                        <h1>{$book->title} ⭐ {$book->average} ({$book->reviewCount})</h1>
                        <h3>{$book->author}</h3>
        
                        <p class='description'>
                        {$book->description}
                        </p>
        
                        <div class='details'>
                            <p><strong>Genre:</strong> {$book->genre}</p>
                        </div>
                    </div>
                </div>
            </div>";
        }

        elseif ($user->getRole() == 'admin') {
            return "<main class='book-details'>
                <div class='book-detail-container'>
                <div class='book-card'>            
                    <div class='book-info'>
                        <h1>{$book->title} | ⭐ {$book->average} ({$book->reviewCount})</h1>
                        <h3>{$book->author}</h3>
        
                        <p class='description'>
                        {$book->description}
                        </p>
        
                        <div class='details'>
                            <p><strong>Genre:</strong> {$book->genre}</p>
                        </div>
                        <form action='/bookDetail/{$bookId}/visible' method='POST'>
                        <button type='submit'>{$string}</button>
                        </form>
                    </div>
                </div>
            </div>";
        } else {
            return "";
        }
    }

    public function renderReviewForm(int $bookId): string{
        $user = $this->request->getUser();
        if (!$user){
            return "<div class='form-container'>
                <form class='book-form' enctype='multipart/form-data'>
            
                    <h2>Plaats een review!</h2>
                    <!-- Genre -->
                    <div class='input-group'>
                        <label>Sterren</label>
                        <select name='stars' required>
                            <option value=''>Kies een aantal sterren</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
            
                    <!-- Beschrijving -->
                    <div class='input-group'>
                        <label>Beschrijving</label>
                        <textarea rows='4' placeholder='Korte beschrijving...' name='description' required></textarea>
                    </div>
            
                    <a type='submit' href='/login'>Review plaatsen</a>
                </form>
            </div>
            ";
        }
        elseif ($user->getRole() == 'admin' || $user->getRole() == 'user') {
            return "<div class='form-container'>
                <form class='book-form' enctype='multipart/form-data' action='/bookDetail/{$bookId}/review' method='POST'>
            
                    <h2>Plaats een review!</h2>
                    <!-- Genre -->
                    <div class='input-group'>
                        <label>Sterren</label>
                        <select name='stars' required>
                            <option value=''>Kies een aantal sterren</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
            
                    <!-- Beschrijving -->
                    <div class='input-group'>
                        <label>Beschrijving</label>
                        <textarea rows='4' placeholder='Korte beschrijving...' name='description' required></textarea>
                    </div>
            
                    <button type='submit'>Review plaatsen</button>
                </form>
            </div>
            ";
        }
        return "";
    }

    public function renderReviewList(array $reviews, int $bookId): string{
        $user = $this->request->getUser();
        if (!$user){
            $html = "";
            foreach ($reviews as $review) {
                $stars = str_repeat('⭐', $review->stars);
                $html .="<div class='review-list'>
                <div class='review-card'>
                    <p class='review-text'>{$review->description}</p>
                            <p class='review-author'>– {$review->username} : {$stars}</p>
                </div>
            </div>";
            }
            return $html;
        }
        elseif ($user->getRole() == 'user') {
            $html = "";
            foreach ($reviews as $review) {
                $stars = str_repeat('⭐', $review->stars);
                $html .= "<div class='review-list'>
                <div class='review-card'>
                    <p class='review-text'>{$review->description}</p>
                            <p class='review-author'>– {$review->username} : {$stars}</p>
                </div>
            </div>";
            }
            return $html;
        }
        elseif ($user->getRole() == 'admin'){
            $html = "";
            foreach ($reviews as $review) {
                $stars = str_repeat('⭐', $review->stars);
                $html .=
                "<div class='review-list'>
                    <div class='review-card'>
                        <p class='review-text'>{$review->description}</p>
                                <p class='review-author'>– {$review->username} : {$stars}</p>
                                <form action='/bookDetail/deleteReview/{$review->reviewId}' method='POST'>
                                <button type='submit'>Verwijder review</button>
                                </form>
                    </div>
                </div>
                ";
            }
            return $html;
        }
        return "";
    }
}