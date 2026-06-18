<?php

namespace App\View;

use Framework\Http\Request;

class CompileHeaderView
{
    private Request $request;
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function renderHeader(): string{
        $user = $this->request->getUser();

        if(!$user){
            return "<header>
                    <h1>📚 MyBooks</h1>
                    <!-- Hamburger menu -->
                    <div class='menu'>
                        <input type='checkbox' id='menu-toggle'>
                        <label for='menu-toggle' class='hamburger'>
                            <span></span>
                            <span></span>
                            <span></span>
                        </label>
                 
                        <nav class='menu-items'>
                            <a href='/'>Dashboard</a>
                            <a href='/login'>Inloggen</a>
                            </nav>
                    </div>
                </header>";
        }

        elseif ($user->getRole() == 'user') {
            return "<header>
                    <h1>📚 MyBooks | Hallo {$user->username}!</h1>
                    <!-- Hamburger menu -->
                    <div class='menu'>
                        <input type='checkbox' id='menu-toggle'>
                        <label for='menu-toggle' class='hamburger'>
                            <span></span>
                            <span></span>
                            <span></span>
                        </label>
                 
                        <nav class='menu-items'>
                            <a href='/'>Dashboard</a>
                            <a href='/addBook'>Boek toevoegen</a>
                            <a href='/logout'>Uitloggen</a>
                            </nav>
                    </div>
                </header>";
        }

        elseif ($user->getRole() == 'admin') {
            return "<header>
                    <h1>📚 MyBooks | Hallo {$user->username}!</h1>
                    <!-- Hamburger menu -->
                    <div class='menu'>
                        <input type='checkbox' id='menu-toggle'>
                        <label for='menu-toggle' class='hamburger'>
                            <span></span>
                            <span></span>
                            <span></span>
                        </label>
                 
                        <nav class='menu-items'>
                            <a href='/'>Dashboard</a>
                            <a href='/addBook'>Boek toevoegen</a>
                            <a href='/logout'>Uitloggen</a>
                            </nav>
                    </div>
                </header>";
        }
        else{
            return "<header>";
        }
    }
}