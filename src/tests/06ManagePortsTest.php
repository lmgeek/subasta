<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Auction;
use App\AuctionInvited;
use App\Batch;
use App\BatchStatus;
use App\Arrive;
use App\Boat;
use App\User;
use App\Constants;

/*
 * Everything related with private auctions, in case of need to put back in action
 * need a second * at the opening of the head comment.
 */
class ManagePortsTest extends TestCase
{
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToGetToPortsAddAsSeller(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->visit('/puertos/agregar');
        $this->see('Subastas destacadas');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToGetToPortsAddAsBuyer(){
        $this->actingAs($this->getAValidUser(Constants::COMPRADOR));
        $this->visit('/puertos/agregar');
        $this->see('Subastas destacadas');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function getToPortsAddForReal(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/puertos/agregar');
        $this->see('Agregar Puerto');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToAddPortWithoutName(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/puertos/agregar');
        $this->press('Guardar');
        $this->seePageIs('/puertos/agregar');
        $this->see('El campo nombre es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToAddPortWithLongName(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/puertos/agregar');
        $this->type('El nombre del puerto no puede tener mas de 30 caracteres','name');
        $this->press('Guardar');
        $this->seePageIs('/puertos/agregar');
        $this->see('El nombre del puerto no puede tener mas de 30 caracteres');
        $this->assertResponseOk();
    }
}
