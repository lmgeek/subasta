<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Boat;
class CreateNewBoatTest extends TestCase
{

    /** @test*/
    function successfullRegisterBoatWithAlias()
    {
        $this->unit(2,'visit','/sellerboat/create');
        $this->unit(2,'see','','Barco III');
        $this->unit(2,'type','','Barco III','alias');
        $this->unit(2,'press','','Guardar');
        $this->unit(2,'visit','/sellerboa');
        $this->unit(2,'seePageis','/sellerboat/');
        $this->unit(2,'responseOk');
    }

    /** @test */
    function saveANewBoat(){
        $boat = new Boat();
        $response = $this->instanceClassBoat($boat,118,'Barco III');
        $this->unitAssert('assertTrue',$response);
    }

    /** @test */
    function cancelBottonWhenCreatingBoat()
    {
        $this->unit(2,'visit','/sellerboat/create');
        $this->unit('2','click','','','Cancelar');
        $this->unit(2,'see','','Mis Barcos');
        $this->unit(2,'responseOK');
    }
}
