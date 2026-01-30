<?php

namespace App\Controllers;

use App\System\Controller;
use App\System\Redirect;

class Login extends Controller
{
    public function index(array $data = []): string|false
    {
        return $this->render('layouts/login.php');
    }
}
