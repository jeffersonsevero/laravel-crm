<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertOk();
});

it('should be able to login', function () {

    $user = User::query()->create([
        'name'     => 'John',
        'email'    => 'j@j.com',
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'j@j.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user()->id)->toBe($user->id);

});

it('should send error when email or password is wrong', function () {

    Livewire::test(Login::class)
        ->set('email', 'j@j.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasErrors(['invalidCredentials'])
        ->assertSee(trans('auth.failed'));

});
