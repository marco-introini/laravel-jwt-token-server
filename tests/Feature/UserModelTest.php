<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Password is correctly hashed', function () {
    User::create([
        'name' => 'Nome',
        'email' => 'test@test.com',
        'password' => 'password'
    ]);

    $user = User::first();

    expect(Hash::check('password',$user->password))
        ->toBeTrue();
});
