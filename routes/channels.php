<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user-{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('online-users', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});
Broadcast::routes([
    'middleware' => ['auth:sanctum'], // or auth:api
]);