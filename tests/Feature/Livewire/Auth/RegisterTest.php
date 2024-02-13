<?php

use App\Livewire\Auth\Register;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);
use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('should render the component', function () {
    Livewire::test(Register::class)
        ->assertOk();
});

it('should register a new user', function () {
    Livewire::test(Register::class)
        ->set('name', 'John')
        ->set('email', 'j@j.com')
        ->set('email_confirmation', 'j@j.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'John',
        'email' => 'j@j.com',
    ]);

    assertDatabaseCount('users', 1);

});
