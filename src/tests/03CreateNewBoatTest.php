<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Boat;
class CreateNewBoatTest extends TestCase
{


    /** @test */
    function saveANewBoat(){
        $id = $this->getTheLastIdInsertedInTheSeller();
        $boat = new Boat();
        $response = $this->instanceClassBoat($boat,$id,'Barco I');
        $this->unitAssert('assertTrue',$response);
    }


    /** @test*/
    function successfullRegisterBoatWithAlias()
    {
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->unit($id,'visit','/sellerboat/create');
        $this->unit($id,'see','','Barco II');
        $this->unit($id,'type','','Barco II','alias');
        $this->unit($id,'press','','Guardar');
        $this->unit($id,'visit','/sellerboa');
        $this->unit($id,'seePageis','/sellerboat/');
        $this->unit($id,'responseOk');
    }

    /** @test */
    function cancelBottonWhenCreatingBoat()
    {
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->unit($id,'visit','/sellerboat/create');
        $this->unit($id,'click','','','Cancelar');
        $this->unit($id,'see','','Mis Barcos');
        $this->unit($id,'responseOK');
    }
}
