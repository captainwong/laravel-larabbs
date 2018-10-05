<?php

namespace App\Transformers;

use Spatie\Permission\Models\Permission;

class PermissionTransformer extends TransformerAbstract
{
    public function transform(Permission $permission){
        return [
            'id' => $permission->id,
            'name' => $permission->name,
        ];
    }
}
