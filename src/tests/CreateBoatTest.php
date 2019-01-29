<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;

class CreateBoatTest extends TestCase
{

    /**
     *
     * Test Input Nombre Barco
     *
     */


    /**
     * A test Register Successfully.
     *
     * @return void
     */
    public function testSuccessfulRegisterBoatWithCorrectData()
    {

    $user = \App\User::find(2);
    $this->actingAs($user)
        ->visit("/sellerboat/create")
        ->type('Walrus Nro 3', 'name')
        ->type('WS-10-', 'matricula')
        ->press('Guardar')
        ->visit("/sellerboat")
        ->see("Mis Barcos");
    }


    /**
     * A test Register Successfully With special Characters.
     *
     * @return void
     */
    public function testSuccessfulRegisterBoatWithCorrectSpecialCharacterData()
    {

        $user = \App\User::find(2);
        $this->actingAs($user)
            ->visit('/sellerboat/create')
            ->type('Walrus #4-A', 'name')
            ->type('WS2', 'matricula')
            ->press('Guardar')
            ->visit("/sellerboat")
            ->see("Mis Barcos")
        ;
    }


    /**
     * A test Incorrect Register With Special Characters.
     *
     * @return void
     */
    public function testIncorrectRegisterBoatWithSpecialCharacters()
    {

        $user = \App\User::find(2);
        $this->actingAs($user)
            ->visit('/sellerboat/create')
            ->type('Walrus@¬€~#@{}[]¿?=)(/&%$·"!', 'name')
//            ->type('WS-10-4', 'matricula')
            ->press('Guardar')
            ->seePageIs('/sellerboat/create')
            ->see('El nombre sólo permite caracteres alfanumericos y # y -');
    }


    /**
     * A test Incorrect Register With Space Character.
     *
     * @return void
     */
    public function testIncorrectRegisterBoatWithSpaceCharacter()
    {

        $user = \App\User::find(2);
        $this->actingAs($user)
            ->visit('/sellerboat/create')
            ->type('  ', 'name')
//            ->type('WS-10-4', 'matricula')
            ->press('Guardar')
            ->seePageIs('/sellerboat/create')
            ->see('El campo Nombre es obligatorio');
    }


    /**
     * A test Incorrect Register With Void In Input Name.
     *
     * @return void
     */
    public function testIncorrectRegisterBoatWithVoidInput()
    {

        $user = \App\User::find(2);
        $this->actingAs($user)
            ->visit('/sellerboat/create')
            ->type('', 'name')
//            ->type('WS-10-4', 'matricula')
            ->press('Guardar')
            ->seePageIs('/sellerboat/create')
            ->see('El campo Nombre es obligatorio');
    }


    /**
     *
     * Test Input Matrícula Barco
     *
     */


    /**
     * A test Register Successfully.
     *
     * @return void
     */
    public function testSuccessfulRegisterBoatWithCorrectMatricula()
    {

        $user = \App\User::find(2);
        $this->actingAs($user)
            ->visit("/sellerboat/create")
            ->type('Walrus #4-A', 'name')
            ->type('WS2A', 'matricula')
            ->press('Guardar')
            ->visit("/sellerboat")
            ->see("Mis Barcos");
    }

    /**
     * A test Register Successfully.
     *
     * @return void
     */
    public function testSuccessfulRegisterBoatWithCorrectDataMatricula()
    {

        $user = \App\User::find(2);
        $this->actingAs($user)
            ->visit("/sellerboat/create")
            ->type('Walrus #4-A', 'name')
            ->type('WS-10-4-A', 'matricula')
            ->press('Guardar')
            ->visit("/sellerboat");
    }


    /**
     * A test Incorrect Register With Special Characters.
     *
     * @return void
     */
    public function testIncorrectRegisterBoatWithSpecialCharactersMatricula()
    {

        $user = \App\User::find(2);
        $this->actingAs($user)
            ->visit('/sellerboat/create')
            ->type('Walrus- @¬€~#@{}[]¿?=)(/&%$·"!', 'matricula')
            ->press('Guardar')
            ->seePageIs('/sellerboat/create')
            ->see('La Matrícula sólo permite caracteres alfanumericos y -');
    }


    /**
     * A test Incorrect Register With Space Character.
     *
     * @return void
     */
    public function testIncorrectRegisterBoatWithSpaceCharacterMatricula()
    {

        $user = \App\User::find(2);
        $this->actingAs($user)
            ->visit('/sellerboat/create')
            ->type('  ', 'matricula')
            ->press('Guardar')
            ->seePageIs('/sellerboat/create')
            ->see('El campo matricula es obligatorio');
    }


    /**
     * A test Incorrect Register With Void In Input Matrícula.
     *
     * @return void
     */
    public function testIncorrectRegisterBoatWithVoidInputMatricula()
    {

        $user = \App\User::find(2);
        $this->actingAs($user)
            ->visit('/sellerboat/create')
            ->type('', 'matricula')
            ->press('Guardar')
            ->seePageIs('/sellerboat/create')
            ->see('El campo matricula es obligatorio');
    }


    /**
     * A test Incorrect Register With Duplicate Registry.
     *
     * @return void
     */
    public function testIncorrectRegisterBoatWithDuplicateMatricula()
    {

        $user = \App\User::find(2);
        $this->actingAs($user)
            ->visit('/sellerboat/create')
            ->type('AAA-BBB-ZZZ', 'matricula')
            ->press('Guardar')
            ->seePageIs('/sellerboat/create');
    }


}
