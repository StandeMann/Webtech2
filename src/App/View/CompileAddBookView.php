<?php

namespace App\View;

use Framework\Http\Classes\Request;

class CompileAddBookView
{
    private Request $request;
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function compileAddBookForm(): string{
        $user = $this->request->getUser();
        if (!$user){
            $location = 'Location: /403';
            header($location);
            exit;
        }
        elseif ($user->getRole() == 'user' || $user->getRole() == 'admin'){
            return "<div class='form-container'>
                        <form class='book-form' enctype='multipart/form-data' action='/addBook' method='POST'>
                            <h2>Nieuw Boek</h2>
                            <!-- Titel -->
                            <div class='input-group'>
                                <label>Titel</label>
                                <input type='text' placeholder='Boektitel' name='title' required>
                            </div>
                    
                            <!-- Auteur -->
                            <div class='input-group'>
                                <label>Auteur</label>
                                <input type='text' placeholder='Naam van de auteur' name='author' required>
                            </div>
                    
                            <!-- Genre -->
                            <div class='input-group'>
                                <label>Genre</label>
                                <select name='genre' required>
                                    <option value=''>Kies een genre</option>
                                    <option>Roman</option>
                                    <option>Thriller</option>
                                    <option>Fantasy</option>
                                    <option>Science Fiction</option>
                                    <option>Non-fictie</option>
                                </select>
                            </div>
                    
                            <!-- Beschrijving -->
                            <div class='input-group'>
                                <label>Beschrijving</label>
                                <textarea rows='4' placeholder='Korte beschrijving...' name='description' required></textarea>
                            </div>
                    
                            <button type='submit'>Boek toevoegen</button>
                        </form>
                    </div>";
        }
        return "";
    }
}