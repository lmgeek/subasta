<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;

class CreateNewBoatTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function setUp()
    {
        parent::setUp();
        $this->rules = (new \App\Http\Requests\CreateBoatRequest())->rules();
        $this->validator = $this->app['validator'];
    }

    public function getFieldValidator($field, $value)
    {
        return $this->validator->make(
            [$field => $value],
            [$field => $this->rules[$field]]
        );
    }

    public function ValidateField($field, $value)
    {
        return $this->getFieldValidator($field, $value)->passes();
    }

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
    public function testSuccessfulRegisterBoatWithCorrectName()
    {
        $response = $this->validateField('name', "Walrus Nro 3");
        $this->assertTrue($response);
    }



}