<?php


/**
 * Created by PhpStorm.
 * User: alejandroj
 * Date: 31/10/2018
 * Time: 12:38
 */

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SignInTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->usuarioSeller = "pruebastest@test.com";
        $this->usuarioBuyer = "buyerTest@test.com";
        $this->password = "N3tl4bs!";
    }

    public function testSingInSuccessfulySeller()
    {

        $this->visit('/auth/login')
            ->type($this->usuarioSeller, 'email')
            ->type($this->password, 'password')
            ->press('Entrar')
            ->visit("/home")
            ->see("Ventas");
    }

    public function testSingInNotSuccessfulySeller()
    {

        $this->visit('/auth/login')
            ->type("alejandro", 'email')
            ->type("1234", 'password')
            ->press('Entrar')
            ->visit("/auth/login")
            ->see("Error");
    }

    public function SingInSuccessfulyBuyer()
    {
        $this->visit('/auth/login')
            ->type($this->usuarioSeller, 'email')
            ->type($this->password, 'password')
            ->press('Entrar')
            ->visit("/home")
            ->see("Compras");
    }

    public function SingInNotSuccessfulyBuyer()
    {
        $this->visit('/auth/login')
            ->type("alejandro", 'email')
            ->type("1234", 'password')
            ->press('Entrar')
            ->visit("/auth/login")
            ->see("Error");
    }

    public function SingInNotPassingEmailInput()
    {
        $this->visit('/auth/login')
            ->type("", '')
            ->see("Completa este campo");
    }
}