<?php
use App\User;
use App\Vendedor;
Use App\Http\Controllers\RegisterController;
Use App\Constants;
use App\AuctionQuery;
use \App\Product;
abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
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
            case 'seePageIs':
                $userAuth->seePageIs($uri);
            case 'responseOK':
                $userAuth->assertResponseOk();
                break;

            case 'select':
                $userAuth->select($text,$element);
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
    public function instanceClassBoat($class,$id,$nombre,$matricula,$preference_port ){
        $class->user_id = $id;
        $class->name = $nombre;
        $class->matricula = $matricula;
        $class->preference_port = $preference_port;
        $class->save();
        return true;
    }
    public function instanceClassProduct($class,$nombre,$unidad,$pequeno,$mediano,$grande,$imagen,$codigo,$unit_sale ){
        $class->name = $nombre;
        $class->unit = $unidad;
        $class->weigth_small = $pequeno;
        $class->weigth_medium = $mediano;
        $class->weigth_big = $grande;
        $class->image_name = $imagen;
        $class->fishing_code = $codigo;
        $class->sale_unit = $unit_sale;
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

    //G.B funcion para obtener el id del ultimo registro insertado en la BD
    public function getTheLastIdInsertedInTheSeller(){
        $idLast = User::all()->last();
        return $idLast['id'];
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
    /* INI Rodolfo*/
    public function getAValidUser($type){
        return User::select()
            ->where('type','=',$type)
            ->where(Constants::STATUS,Constants::EQUAL,Constants::APROBADO)
            ->limit(1)->get()[0];
    }
    public function getAValidBoat(){
        return App\Boat::select('id')
                ->where(Constants::STATUS,Constants::EQUAL,Constants::APROBADO)
                ->limit(1)->get()[0]['id'];
    }
    public function getAValidPort(){
        return \App\Ports::select('id')
                ->limit(1)->get()[0]['id'];
    }
    public function getAValidProduct(){
        return App\ProductDetail::select('id')
                ->limit(1)->get()[0]['id'];
    }
    public function getAValidCaliber(){
        return Product::caliber()[0];
    }
    public function getAValidPresentationUnit(){
        return Product::units()[0];
    }
    public function getLastAuction(){
        return AuctionQuery::auctionHome(null,array('orderby'=>'start','order'=>'desc'), Constants::FUTURE)[0];
    }
    /* FIN Rodolfo*/
}
