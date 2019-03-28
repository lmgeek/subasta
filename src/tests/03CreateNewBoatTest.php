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
        $this->unit($id,'visit','/barcos');
        $this->unit($id,'click','','','Nuevo Barco');
        $this->unit($id,'see','','Nombre del barco');
        $this->unit($id,'type','','Venezuela Libre','name');
        $this->unit($id,'type','','V2019','matricula');
        $this->unit($id,'select','','2','port');
        $this->unit($id,'press','','','Guardar');

    }

    /** @test */
    function cancelBottonWhenCreatingBoat()
    {
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->unit($id,'visit','/barcos');
        $this->unit($id,'click','','','Nuevo Barco');
        $this->unit($id,'see','','Cancelar');
        $this->unit($id,'click','','','Cancelar');
        $this->unit($id,'see','','Mis Barcos');
        $this->unit($id,'responseOK');
    }


    /**
     * Lista de barcos en perfil vendedor
     * @test
     */
    function seeMyBoatsInTheSellerProfile()
    {
        $this->unit(1,'visit','/barcos');
        $this->unit(1,'see','','Mis Barcos');
        $this->unit(1,'responseOK');
    }


    /** @test */
    function filterAllByStatusInSellerProfile()
    {
        $this->unit(1,'visit','/barcos');
        $this->unit(1,'see','','Mis Barcos');
        $this->unit(1,'select','','all','filterBoat');
        $this->unit(1,'see','','caribe');
        $this->unit(1,'responseOK');
    }

    /** @test */
    function filterApprovedByStatusInSellerProfile()
    {
        $this->unit(1,'visit','/barcos');
        $this->unit(1,'see','','Mis Barcos');
        $this->unit(1,'select','','approved','filterBoat');
        $this->unit(1,'see','','Aprobado');
        $this->unit(1,'responseOK');
    }

    /** @test */
    function filterRejectedByStatusInSellerProfile()
    {
        $this->unit(1,'visit','/filtrar?status=rejected');
        $this->unit(1,'see','','Mis Barcos');
        $this->unit(1,'select','','rejected','filterBoat');
        $this->unit(1,'see','','magallanes');
        $this->unit(1,'responseOK');
    }

    /** @test */
    function filterPendingByStatusInSellerProfile()
    {
        $this->unit(1,'visit','/filtrar?status=pending');
        $this->unit(1,'see','','Mis Barcos');
        $this->unit(1,'select','','pending','filterBoat');
        $this->unit(1,'see','','la perla');
        $this->unit(1,'responseOK');
    }


    /**
     * lista de barcos en el perfil de admin
     * @test
     */
    function seeListOfBoatsInTheAdministratorProfile()
    {
        $this->unit(9,'visit','/barcos');
        $this->unit(9,'see','','Lista de Barcos');
        $this->unit(9,'responseOK');
    }


    /** @test */
    function filterAllByStatusInAdminProfile()
    {
        $this->unit(9,'visit','/barcos');
        $this->unit(9,'see','','Lista de Barcos');
        $this->unit(9,'select','','all','filterBoat');
        $this->unit(9,'see','','caribe');
        $this->unit(9,'responseOK');
    }


    /** @test */
    function filterApprovedByStatusInAdminProfile()
    {
        $this->unit(9,'visit','/filtrar?status=approved');
        $this->unit(9,'see','','Lista de Barcos');
        $this->unit(9,'select','','approved','filterBoat');
        $this->unit(9,'see','','caribe');
        $this->unit(9,'responseOK');
    }

    /** @test */
    function filterRejectedByStatusInAdminProfile()
    {
        $this->unit(9,'visit','/filtrar?status=rejected');
        $this->unit(9,'see','','Lista de Barcos');
        $this->unit(9,'select','','rejected','filterBoat');
        $this->unit(9,'see','','magallanes');
        $this->unit(9,'responseOK');
    }

    /** @test */
    function filterPendingByStatusInAdminProfile()
    {
        $this->unit(9,'visit','/filtrar?status=pending');
        $this->unit(9,'see','','Lista de Barcos');
        $this->unit(9,'select','','pending','filterBoat');
        $this->unit(9,'see','','la perla');
        $this->unit(9,'responseOK');
    }


}
