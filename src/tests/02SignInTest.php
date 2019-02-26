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

class SignInTest extends \Testcase
{

    public function setUp()
    {
        parent::setUp();
        $this->usuarioSeller = "guaidopresidente@netlabs.com.ar";
        $this->usuarioBuyer = "Robertkiyosaki@netlabs.com.ar";
        $this->password = "G3rm@n";
    }

    /** @test */
    function singInSuccessfulySeller()
    {
        $this->unit(null,'visit','/auth/login');
        $this->unit(null,'type','',$this->usuarioSeller,'email');
        $this->unit(null,'type','',$this->password,'password');
        $this->unit(null,'press','','','Entrar');
        $this->unit(null,'see','','Correo no verificado');
    }


    /** @test */
    function singInNotSuccessfulySeller()
    {
        $this->unit(null,'visit','/auth/login');
        $this->unit(null,'type','','guaidopresidente','email');
        $this->unit(null,'type','','1234','password');
        $this->unit(null,'press','','','Entrar');
        $this->unit(null,'see','','Error');
    }


    /** @test */
    function SingInSuccessfulyBuyer()
    {
        $this->unit(null,'visit','/auth/login');
        $this->unit(null,'type','',$this->usuarioBuyer,'email');
        $this->unit(null,'type','',$this->password,'password');
        $this->unit(null,'press','','','Entrar');
        $this->unit(null,'see','','Correo no verificado');
    }

    /** @test */
    function singInNotSuccessfulyBuyer()
    {
        $this->unit(null,'visit','/auth/login');
        $this->unit(null,'type','','Robertkiyosaki','email');
        $this->unit(null,'type','','1234','password');
        $this->unit(null,'press','','','Entrar');
        $this->unit(null,'see','','Error');

    }


}