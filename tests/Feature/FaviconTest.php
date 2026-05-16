<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('includes anibaku favicon on the home page', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('assets/anibaku.ico', false)
        ->assertSee('assets/anibaku.svg', false);
});

it('includes anibaku favicon on the dashboard', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSee('assets/anibaku.ico', false)
        ->assertSee('assets/anibaku.svg', false);
});
