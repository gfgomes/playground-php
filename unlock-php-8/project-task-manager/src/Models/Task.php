<?php

namespace App\Models;

use App\System\Model;

class Task extends Model
{
    public string $table = 'tasks';
    public bool $softDelete = true;
    public function createTask(array $data): bool
    {
        return $this->create($data);
    }
    public function markTaskAsCompleted(int $id): bool
    {
        return $this->where('id', '=', $id)->update([
            'is_concluded' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function deleteTaskById(int $id): bool
    {
        return $this->where('id', '=', $id)->delete();
    }
    public function clearAllTasks(): bool
    {
        return $this->delete();
    }
}
