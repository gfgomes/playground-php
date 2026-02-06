<?php

namespace App\Models;

use App\System\Model;

class User extends Model
{
    public string $table = 'users';
    public bool $softDelete = true;

    /**
     * Criptografa a senha antes de criar usuário
     */
    public function create(array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return parent::create($data);
    }

    /**
     * Criptografa a senha antes de atualizar usuário
     */
    public function update(array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return parent::update($data);
    }
}
