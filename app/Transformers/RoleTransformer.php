<?php

namespace App\Transformers;

use Spatie\Permission\Models\Role;

class RoleTransformer extends TransformerAbstract
{
    public function transform(Role $role){
        return [
            'id' => $role->id,
            'name' => $role->name,
        ];
    }
}