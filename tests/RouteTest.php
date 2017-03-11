<?php

namespace AI\Omega\Tests;

class RouteTest extends TestCase
{
    protected $withDummy = true;

    public function setUp()
    {
        parent::setUp();

        $this->install();
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGetRoutes()
    {
        $this->disableExceptionHandling();

        $this->visit(route('omega.login'));
        $this->type('admin@admin.com', 'email');
        $this->type('password', 'password');
        $this->press('Login');

        $urls = [
            route('omega.dashboard'),
            route('omega.media.index'),
            route('omega.settings.index'),
            route('omega.roles.index'),
            route('omega.roles.create'),
            route('omega.roles.show', ['role' => 1]),
            route('omega.roles.edit', ['role' => 1]),
            route('omega.users.index'),
            route('omega.users.create'),
            route('omega.users.show', ['user' => 1]),
            route('omega.users.edit', ['user' => 1]),
            route('omega.posts.index'),
            route('omega.posts.create'),
            route('omega.posts.show', ['post' => 1]),
            route('omega.posts.edit', ['post' => 1]),
            route('omega.pages.index'),
            route('omega.pages.create'),
            route('omega.pages.show', ['page' => 1]),
            route('omega.pages.edit', ['page' => 1]),
            route('omega.categories.index'),
            route('omega.categories.create'),
            route('omega.categories.show', ['category' => 1]),
            route('omega.categories.edit', ['category' => 1]),
            route('omega.menus.index'),
            route('omega.menus.create'),
            route('omega.menus.show', ['menu' => 1]),
            route('omega.menus.edit', ['menu' => 1]),
            route('omega.database.index'),
            route('omega.database.edit_bread', ['table' => 'categories']),
            route('omega.database.edit', ['table' => 'categories']),
            route('omega.database.create'),
        ];

        foreach ($urls as $url) {
            $response = $this->call('GET', $url);
            $this->assertEquals(200, $response->status(), $url.' did not return a 200');
        }
    }
}
