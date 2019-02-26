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
        $this->unitAssert('assertTrue',$this->instanceClassBoat($boat,$id,'Libertad',' MM2019',4));
    }


    /** @test*/
    function successfullRegisterBoat()
    {
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->unit($id,'visit','/sellerboat/create');
        $this->unit($id,'see','','Agregar Barco');
        $this->unit($id,'type','','Venezuela Libre','name');
        $this->unit($id,'type','','V2019','matricula');
        $this->unit($id,'select','','2','port');
        $this->unit($id,'press','','Guardar');
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
