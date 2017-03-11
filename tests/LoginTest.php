<?php

namespace AI\Omega\Tests;

class LoginTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->install();
    }

    public function testSuccessfulLoginWithDefaultCredentials()
    {
        $this->visit(route('omega.login'));
        $this->type('admin@admin.com', 'email');
        $this->type('password', 'password');
        $this->press('Login');
        $this->seePageIs(route('omega.dashboard'));
    }

    public function testShowAnErrorMessageWhenITryToLoginWithWrongCredentials()
    {
        $this->visit(route('omega.login'))
             ->type('john@Doe.com', 'email')
             ->type('pass', 'password')
             ->press('Login')
             ->seePageIs(route('omega.login'))
             ->see(trans('auth.failed'))
             ->seeInField('email', 'john@Doe.com');
    }
}
