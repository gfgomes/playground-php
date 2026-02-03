<?php

namespace App\Controllers;

use App\System\Controller;

class Home extends Controller
{
    public function index(array $data = []): string|false
    {
        $tasks = $_SESSION['tasks'] ?? [];
        return parent::index(['tasks' => $tasks]);
    }
}
