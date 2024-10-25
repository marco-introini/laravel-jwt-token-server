<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveJwtScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('expires_at', '<=', now());
    }
}
