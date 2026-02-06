<?php

namespace App\Controllers;

use App\Models\Task;
use App\System\Controller;
use App\System\Redirect;

class Tasks extends Controller
{
    public Task $taskModel;

    public function __construct()
    {
        parent::__construct();
        $this->taskModel = new Task;
    }

    public function index(array $data = [], string | null $layout = null): string|false
    {
        return parent::index([
            'tasks' => $this->taskModel
                ->where('user_id', '=', $_SESSION['userId'])
                ->all(),
        ]);
    }

    public function onCreateTask(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        try {
            $payload = [
                'title' => ucfirst($_POST['task']),
                'user_id' =>  $_SESSION['userId'],
                'is_concluded' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->taskModel->createTask($payload);
            Redirect::to('/tasks', [
                'success' => 'Successfully created'
            ]);
        } catch (\Throwable $th) {
            Redirect::to('/tasks', [
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function onActionTask(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        try {
            if (!method_exists($this->taskModel, $_POST['action'])) {
                throw new \Exception('Method does not exist in the Tasks model');
            }
            
            // Corrigido: usar 'id' em vez de 'index'
            $taskId = (int) $_POST['id'];
            
            if ($taskId <= 0) {
                throw new \Exception('Invalid task ID');
            }
            
            $this->taskModel->{$_POST['action']}($taskId);
            Redirect::to('/tasks', [
                'success' => 'Task updated successfully'
            ]);
        } catch (\Throwable $th) {
            Redirect::to('/tasks', [
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function onDeleteAll(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        try {
            $this->taskModel->clearAllTasks();
            Redirect::to('/tasks', [
                'success' => 'All records deleted successfully'
            ]);
        } catch (\Throwable $th) {
            Redirect::to('/tasks', [
                'error' => $th->getMessage(),
            ]);
        }
    }
}
