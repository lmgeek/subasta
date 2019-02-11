<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Vendedor;
use App\Comprador;

class RegisterUserTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */

    public function setUp()
    {
        parent::setUp();
        $this->rules = (new \App\Http\Requests\RegisterNewUserRequest())->rules();
        $this->validator = $this->app['validator'];
    }

    /**
     * Name Field Validations
     */
    public function testSentPostAlfabeticValuesIntoNameInput()
    {
        $response = $this->validateField('name', "Alejandro");
        $this->assertTrue($response);
    }

    public function testSentPostAlfabeticWithApostrophelValuesIntoNameInput()
    {
        $response = $this->validateField('name', "Alejandro'Jimenez");
        $this->assertTrue($response);
    }

    public function testSentPostEmptyValuesAreNotAcceptedIntoNameInput()
    {
        $response = $this->validateField('name', "");
        $this->assertFalse($response);
    }

    public function testSentPostAllWhiteSpacesValuesAreNotAcceptedIntoNameInput()
    {
        $response = $this->validateField('name', "      ");
        $this->assertFalse($response);
    }

    public function testSentOnlyNumbersValuesAreNotAcceptedIntoNameInput()
    {
        $response = $this->validateField('name', "1234567890");
        $this->assertFalse($response);
    }

    public function testSentOnlySpecialCharactersValuesAreNotAcceptedIntoNameInput()
    {
        $response = $this->validateField('name', 'ª!"·$%&/()*/-+-.,ç´`+^¨Ç^[]{}');
        $this->assertFalse($response);
    }

    /**
     * Lastname Field Validations
     */

    public function testSentPostAlfabeticValuesIntoLastnameInput()
    {
        $response = $this->validateField('lastname', "Alejandro");
        $this->assertTrue($response);
    }

    public function testSentPostAlfabeticWithApostrophelValuesIntoLastnameInput()
    {
        $response = $this->validateField('lastname', "Alejandro'Jimenez");
        $this->assertTrue($response);
    }

    public function testSentPostEmptyValuesAreNotAcceptedIntoLastnameInput()
    {
        $response = $this->validateField('lastname', "");
        $this->assertFalse($response);
    }

    public function testSentPostAllWhiteSpacesAreNotAcceptedValuesIntoLastnameInput()
    {
        $response = $this->validateField('lastname', "      ");
        $this->assertFalse($response);
    }

    public function testSentOnlyNumbersValuesAreNotAcceptedIntoLastnameInput()
    {
        $response = $this->validateField('lastname', "1234567890");
        $this->assertFalse($response);
    }

    public function testSentOnlySpecialCharactersValuesAreNotAcceptedIntoLastnameInput()
    {
        $response = $this->validateField('lastname', 'ª!"·$%&/()*/-+-.,ç´`+^¨Ç^[]{}');
        $this->assertFalse($response);
    }


    /**         Validacion del input alias
     *
     * @test */
     function validateThatAliasFieldIsRequired()
    {
        $response = $this->validateField('alias', "");
        $this->unitAssert('assertFalse',$response);
    }

    /** @test */
    function validateThatTheAliasFieldAcceptsAlphanumeric()
    {
        $response = $this->validateField('alias', "german2906");
        $this->unitAssert('assertTrue',$response);
    }

    /** @test */
    function validateThatAliasFieldDoesNotAcceptSpaces()
    {
        $response = $this->validateField('alias', "    ");
        $this->unitAssert('assertFalse',$response);
    }

    /** @test*/
    function validateThatTheAliasFieldAcceptsSpecialCharacters(){
        $response = $this->validateField('alias', '\|@\|@#€~¬#~,-€¬.');
        $this->unitAssert('assertFalse',$response);
    }

    /** @test*/
    function validateThatTheAliasFieldDoesNotAcceptMoreThan10Characters(){
        $response = $this->validateField('alias', 'En venezuela tambien hay nieve y desierto');
        $this->unitAssert('assertFalse',$response);
    }



    /**
     * Email Field Validations
     */
    public function testSendingAValidEmalAddressIntoEmailInput()
    {
        $response = $this->validateField('email', 'lageleria17@netlabs.com.ar');
        $this->assertTrue($response);
    }

    public function testSendingDuplicateInfoIsNotAcceptedIntoEmailInput()
    {
        $response = $this->validateField('email', 'alejandroj@netlabs.com.ar');
        $this->assertFalse($response);
    }

    public function testSendANotValidIsNotAcceptedIntoEmailInput()
    {
        $response = $this->validateField('email', 'alejandrogmail.com');
        $this->assertFalse($response);
    }
    public function testSendANotValidIsNotAcceptedTwoAtsIntoEmailInput()
    {
        $response = $this->validateField('email', 'alejandro@@gmail.com');
        $this->assertFalse($response);
    }

    public function testLessthatSevenCharactersAreNotAcceptedIntoEmailInput()
    {
        $response = $this->validateField('email', 'a@g.c');
        $this->assertFalse($response);
    }

    public function testSentPostEmptyValuesIsNotAcceptedIntoEmailInput()
    {
        $response = $this->validateField('email', "");
        $this->assertFalse($response);
    }

    public function testSentPostAllWhiteSpacesValuesAreNotAcceptedIntoEmailInput()
    {
        $response = $this->validateField('email', "      ");
        $this->assertFalse($response);
    }

    public function testSentOnlyNumbersValuesAreNotAcceptedIntoEmailInput()
    {
        $response = $this->validateField('email', "1234567890");
        $this->assertFalse($response);
    }

    public function testSentOnlySpecialCharactersValuesAreNotAcceptedEmailInput()
    {
        $response = $this->validateField('email', 'ª!"·$%&/()*/-+-.,ç´`+^¨Ç^[]{}');
        $this->assertFalse($response);
    }


    /**        Prueba de registrar usuario Comprador en base de datos
     *
     * @test */
    function validateThatABuyerRegistersCorrectly(){

        $user = new User();
        $comprador = new Comprador();

        $response = $this->instanceClassUserBuyer($user,$comprador,"Robert","kiyosaki","rober29","123123123",
            "Robertkiyosaki@netlabs.com.ar","G3rm@n") ;
        $this->unitAssert('assertTrue',$response);
    }


    /**         Prueba de registrar usuario vendedor en base de datos
     *
     * @test */
    function validateThatASellerRegistersCorrectly(){

    $user = new User();
    $vendedor = new Vendedor();

    $response = $this->instanceClassUserSeller($user, $vendedor, "Guaido", "Venezuela", "Guiaido29", "32-32322332-3",
                "guaidopresidente@netlabs.com.ar", "G3rm@n");
    $this->unitAssert('assertTrue', $response);

    }




}
