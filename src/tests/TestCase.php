<?php
use App\User;
use App\Vendedor;
Use App\Http\Controllers\RegisterController;
class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }


    //G.B Esta funcion retorna la directiva a solicitar
    public function unit($id = null, $directiva = "", $uri= "", $text="", $element = "", $bolean = false)
    {

        if($id != null){

            $user = \App\User::find($id);
            $userAuth =  $this->actingAs($user);

        }else{

            $userAuth = $this;
        }

        switch ($directiva){

            case 'visit':
                $userAuth->visit($uri);
                break;

            case 'type':
                $userAuth->type($text,$element);
                break;

            case 'press':
                $userAuth->press($text);
                break;

            case 'seePageIs':
                $userAuth->seePageIs($uri);
                break;

            case 'see':
                $userAuth->see($text,$bolean);
                break;

            case 'dontSee':
                $userAuth->dontSee($text);
                break;

            case 'responseOK':
                $userAuth->assertResponseOk();
                break;

            //Recuerda que funcion Click() recibe como parametro el text0 que se encuentra entre las etiquetas <a>
            case 'click':
                $userAuth->click($element);
                break;

            case 'check':
                $userAuth->check($element);
                break;

            default:
                echo "Directiva no encontrada";
        }

    }

    //G.B Esta funcion retorna la directiva a assert
    Public function unitAssert($directiva,$condicion,$mensaje = ''){

        switch ($directiva){

            case 'assertTrue':
                $this->assertTrue($condicion, $mensaje);
                break;

            case 'assertFalse':
                $this->assertFalse($condicion, $mensaje);
                break;

            default:
                echo "Directiva no encontrada";
        }
    }


    /*    G.B Resgitrar barco*/
    public function instanceClassBoat($class,$id,$nickname ){
        $class->user_id = $id;
        $class->nickname = $nickname;
        $class->save();
        return true;
    }

    /*    G.B Resgitro vendedor*/
    public function instanceClassUserSeller($classUser,$classVendedor,$name,$lastname,$nickname,$cuit,$email,$password,$phone = ""){

        $objt = new RegisterController;

        $classUser->name = $name;
        $classUser->lastname = $lastname;
        $classUser->nickname = $nickname;
        $classUser->email = $email;
        $classUser->password = Hash::make($password);
        $classUser->type = User::VENDEDOR;
        $classUser->status = User::PENDIENTE;
        $classUser->hash = $objt->generateRandomString(45);

        if ($classUser->save()) {

            $classVendedor->user_id = $classUser->id;
            $classVendedor->cuit = $cuit;
            $classVendedor->save();
            return true;
        }
    }

    /*    G.B Resgitro Comprador*/
    public function instanceClassUserBuyer($classUser,$classComprador,$name,$lastname,$nickname,$dni,$email,$password,$phone = ""){

        $objt = new RegisterController;

        $classUser->name = $name;
        $classUser->lastname = $lastname;
        $classUser->nickname = $nickname;
        $classUser->email = $email;
        $classUser->password = Hash::make($password);
        $classUser->phone = $phone;
        $classUser->type = User::COMPRADOR;
        $classUser->status = User::PENDIENTE;
        $classUser->hash = $objt->generateRandomString(45);

        if ($classUser->save()) {
            $classComprador->user_id = $classUser->id;
            $classComprador->dni = $dni;
            $classComprador->save();
            return true;
        }

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



}
