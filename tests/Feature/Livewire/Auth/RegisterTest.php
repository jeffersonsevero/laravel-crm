<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use App\Providers\RouteServiceProvider;
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
        ->assertHasNoErrors()
        ->assertRedirect(RouteServiceProvider::HOME);

    assertDatabaseHas('users', [
        'name'  => 'John',
        'email' => 'j@j.com',
    ]);

    assertDatabaseCount('users', 1);

    expect(auth()->user()->id)->toBe(User::query()->first()->id);

});

test('required fields', function ($field) {
    Livewire::test(Register::class)
        ->set($field, '')
        ->call('submit')
        ->assertHasErrors([$field => 'required']);
})->with(['name', 'email', 'password']);

test('max characters', function ($field) {
    Livewire::test(Register::class)
        ->set($field, str_repeat('aa', 256))
        ->call('submit')
        ->assertHasErrors();
})->with(['name']);

it('validates email', function () {
    Livewire::test(Register::class)
        ->set('email', 'invalid')
        ->call('submit')
        ->assertHasErrors(['email' => 'email']);
});

it('validates email confirmation', function () {
    Livewire::test(Register::class)
        ->set('email', 'a@a.com')
        ->set('email_confirmation', 'b@b.com')
        ->call('submit')
        ->assertHasErrors(['email' => 'confirmed']);
});
