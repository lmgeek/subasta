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
class ManageAuctionsTest extends TestCase
{
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToGetToAuctionAddAsAdmin(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/subastas/agregar');
        $this->see('Subastas destacadas');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToGetToAuctionAddAsBuyer(){
        $this->actingAs($this->getAValidUser(Constants::COMPRADOR));
        $this->visit('/subastas/agregar');
        $this->see('Subastas destacadas');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function getToAuctionAddForReal(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->visit('/subastas/agregar');
        $this->see('Nueva Subasta');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function selectPreferredPortOnSelectingBoat(){
        $boat=$this->getAValidBoat();
        $boatport= Boat::select('preference_port')->where('id',Constants::EQUAL,$boat)
                ->get()[0]['preference_port'];
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->json('GET', '/puertos/ver/preferido', ['idboat' => $boat])
             ->seeJsonEquals([
                 'preferred' => $boatport
             ]);
        $this->assertResponseOk();
    }
    
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutTentativeDate(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("-2 hours"))->format('d-m-Y H:i');
        $tentativedate='';
        $this->visit('subastas/agregar');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El campo fecha tentativa es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutBoat(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+27 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        //$this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El campo barco es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutPort(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+27 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        //$this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El campo puerto es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutProduct(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+27 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        //$this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El campo producto es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutCaliber(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+27 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        //$this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('Debes seleccionar un calibre');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithTentativeDatePast(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("-2 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('La fecha tentativa debe ser una fecha posterior a');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutStartDate(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate='';
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El campo Fecha de Inicio es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithStartDatePast(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("-2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('La Fecha de Inicio debe ser una fecha posterior a');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutActiveHours(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El campo horas activas es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithLessThanOneActiveHours(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('0','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('La cantidad de horas activas debe ser al menos');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutAmount(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El campo cantidad es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithLessThanOneAmount(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('0','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('La cantidad debe ser de al menos 1');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutStartPrice(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El campo precio inicial es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutEndPrice(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('100','startPrice');
        $this->type('','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El campo precio de retiro es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithStartPriceLowerEndPrice(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('50','startPrice');
        $this->type('500','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El precio de retiro no puede ser mayor al precio inicial.');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutDescription(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('','descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El campo descripci&oacute;n es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithShortDescription(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $description='';
        for($z=0;$z<50;$z++){
            $description.='a';
        }
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type($description,'descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('El campo descripci&oacute;n debe ser de al menos 120 carateres');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithLongDescription(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $description='';
        for($z=0;$z<1100;$z++){
            $description.='a';
        }
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        $this->select('3','quality');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type($description,'descri');
        $this->press('Subastar');
        $this->seePageIs('/subastas/agregar');
        $this->see('descripci&oacute;n no debe ser mayor que 1000 caracteres');
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function AddPublicAuctionForReal(){
        $this->withoutMiddleware();
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $description='NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.';
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+5 minutes"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+72 hours"))->format('d-m-Y H:i');
        
        $data=array(
            'testing'=>true,
            'barco'=>$this->getAValidBoat(),
            'puerto'=>$this->getAValidPort(),
            'fechaTentativa'=>$tentativedate,
            'product_detail'=>$this->getAValidProduct(),
            'product'=>$this->getAValidProduct(),
            'caliber'=>$this->getAValidCaliber(),
            'quality'=>'3',
            'fechaInicio'=>$startdate,
            'ActiveHours'=>'24',
            'amount'=>'50',
            'startPrice'=>'200',
            'endPrice'=>'100',
            'tipoSubasta'=>'public',
            'descri'=>$description
        );
        $this->post('/subastas/guardar',$data);
        
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['description'=>$description]);
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToGetToAuctionEditWithNonValidId(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->visit('/subastas/editar/asdasdasd');
        $this->see('Lo sentimos, pero la p&aacute;gina que buscabas no existe.');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToGetToAuctionEditAsAdmin(){
        $auction=$this->getLastAuction();
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/subastas/editar/'.$auction->id);
        $this->see('Subastas destacadas');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToGetToAuctionEditAsBuyer(){
        $auction=$this->getLastAuction();
        $this->actingAs($this->getAValidUser(Constants::COMPRADOR));
        $this->visit('/subastas/editar/'.$auction->id);
        $this->see('Subastas destacadas');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function getToAuctionEditForReal(){
        $auction=$this->getLastAuction();
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->visit('/subastas/editar/'.$auction->id);
        $this->see('Editar Subasta');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToGetToAuctionReplicateWithNonValidId(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->visit('/subastas/replicar/asdasdasd');
        $this->see('Lo sentimos, pero la p&aacute;gina que buscabas no existe.');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToGetToAuctionReplicateAsAdmin(){
        $auction=$this->getLastAuction();
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/subastas/replicar/'.$auction->id);
        $this->see('Subastas destacadas');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function TryToGetToAuctionReplicateAsBuyer(){
        $auction=$this->getLastAuction();
        $this->actingAs($this->getAValidUser(Constants::COMPRADOR));
        $this->visit('/subastas/replicar/'.$auction->id);
        $this->see('Subastas destacadas');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function getToAuctionReplicateForReal(){
        $auction=$this->getLastAuction();
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->visit('/subastas/replicar/'.$auction->id);
        $this->see('Replicar Subasta');
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function EditLastAuctionAllowingArriveAndBatchEdit(){
        $this->withoutMiddleware();
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $comprador=$this->getAValidUser(Constants::COMPRADOR);
        $auction=$this->getLastAuction();
        $description='Auction'.$auction->id.'. Esta descripcion se edito. NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.';
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+1 minutes"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+72 hours"))->format('d-m-Y H:i');
        
        $data=array(
            '_token'=>Session::token(),
            'type'=>'edit',
            'auctionid'=>$auction->id,
            'batchid'=>$auction->batch_id,
            'arriveid'=>$auction->batch->arrive_id,
            'testing'=>true,
            'barco'=>$this->getAValidBoat(),
            'puerto'=>$this->getAValidPort(),
            'fechaTentativa'=>$tentativedate,
            'product'=>$this->getAValidProduct(),
            'product_detail'=>$this->getAValidProduct(),
            'caliber'=>$this->getAValidCaliber(),
            'quality'=>'3',
            'fechaInicio'=>$startdate,
            'ActiveHours'=>'24',
            'amount'=>'200',
            'startPrice'=>'2000',
            'endPrice'=>'1000',
            'tipoSubasta'=>'public',
            'descri'=>$description
        );
        $this->post('/subastas/guardar',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['description'=>$description]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function ReplicateLastAuction(){
        $this->withoutMiddleware();
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $comprador=$this->getAValidUser(Constants::COMPRADOR);
        $auction=$this->getLastAuction();
        $description='Auction'.$auction->id.'. Esta descripcion se replico. NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.';
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+24 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+72 hours"))->format('d-m-Y H:i');
        
        $data=array(
            '_token'=>Session::token(),
            'auctionid'=>$auction->id,
            'batchid'=>$auction->batch_id,
            'type'=>'replication',
            'arriveid'=>$auction->batch->arrive_id,
            'testing'=>true,
            'barco'=>$this->getAValidBoat(),
            'puerto'=>$this->getAValidPort(),
            'fechaTentativa'=>$tentativedate,
            'product'=>$this->getAValidProduct(),
            'caliber'=>$this->getAValidCaliber(),
            'product_detail'=>$this->getAValidProduct(),
            'quality'=>'3',
            'fechaInicio'=>$startdate,
            'ActiveHours'=>'24',
            'amount'=>'200',
            'startPrice'=>'2000',
            'endPrice'=>'1000',
            'tipoSubasta'=>'public',
            'descri'=>$description
        );
        $this->post('/subastas/guardar',$data);
        
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['description'=>$description]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkCorrelativeForLastAuction(){
        $auction=$this->getLastAuction();
        static::assertEquals('157', $auction->correlative);
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkIfCodeIsInCorrectFormat(){
        $auction=$this->getLastAuction();
        static::assertEquals('SU-'.date('ym').'157',$auction->code);
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForAllAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'all',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all'
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>5]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForCurrentAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'incourse',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all'
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>0]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForFutureAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'future',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all'
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>2]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForFinishedAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'finished',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all'
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>3]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForSpecificProductAllAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'all',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all',
                'product'=>'1**'
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>5]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForSpecificProductPlusCaliberAllAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'all',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all',
                'product'=>'1**',
                'caliber'=>'small**'
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>3]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForSpecificQualityEmptyResultsAllAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'all',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all',
                'quality'=>'1**',
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>0]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForSpecificQualityWithResultsAllAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'all',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all',
                'quality'=>'3**',
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>2]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForSpecificPortAllAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'all',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all',
                'port'=>'2**',
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>2]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForSpecificPriceRangeAllAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'all',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'5050**',
                'type'=>'all',
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>5]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForSpecificUserRatingWithNoResultsAllAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'all',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all',
                'userrating'=>'1**'
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>0]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForSpecificUserRatingWithResultsAllAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'all',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all',
                'userrating'=>'3**'
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>5]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForSpecificUserWithResultsAllAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'all',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all',
                'user'=>$this->getAValidUser(Constants::VENDEDOR)->id.'**'
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>5]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function checkAuctionsMasterFilterForSpecificUserWithNoResultsAllAuctions(){
        $this->withoutMiddleware();
        $data=[
            'testing'=>true,
            'limit'=>100,
            'current'=>1,
            'time'=>'all',
            'filters'=>[
                'pricemin'=>'50**',
                'pricemax'=>'10000**',
                'type'=>'all',
                'user'=>$this->getAValidUser(Constants::COMPRADOR).'**'
                ],
            ];
        $this->post('/subastas/cargar/mas',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['quantity'=>0]);
        $this->assertResponseOk();
    }
    
    
    
    
    
    /*
     * PrivateAuctionsTests
     */
    
    
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function getValidUsersForPrivateAuctionWithNoneSelected(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->json('GET', '/getusersauctionprivate', ['val' => 'rafael a'])
             ->seeJsonEquals([
                 0=>[
                     'id' => 5,
                    'name'=>'rafael',
                    'lastname'=>'alvarez',
                    'nickname'=>'rafa'
                 ]
             ]);
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function getNoUsersForPrivateAuctionWithOneSelected(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->json('GET', '/getusersauctionprivate', ['val' => 'rafael a','ids'=>array('5')])
             ->seeJsonEquals([
             ]);
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToGetPendingUserForPrivateAuction(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->json('GET', '/getusersauctionprivate', ['val' => 'ezequiel bikiel'])
             ->seeJsonEquals([
             ]);
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToGetRejectedUserForPrivateAuction(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->json('GET', '/getusersauctionprivate', ['val' => 'lorena perez'])
             ->seeJsonEquals([
             ]);
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToGetSellerForPrivateAuction(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->json('GET', '/getusersauctionprivate', ['val' => 'Maria Crer'])
             ->seeJsonEquals([
             ]);
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToGetAdminForPrivateAuction(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->json('GET', '/getusersauctionprivate', ['val' => 'diego weinstein'])
             ->seeJsonEquals([
             ]);
        $this->assertResponseOk();
    }
    /*  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function AddPrivateAuction(){
        $this->withoutMiddleware();
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $comprador=$this->getAValidUser(Constants::COMPRADOR);
        $description='NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.';
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d-m-Y H:i');
        
        $data=array(
            '_token'=>Session::token(),
            'testing'=>true,
            'barco'=>'4',
            'puerto'=>'2',
            'fechaTentativa'=>$tentativedate,
            'product'=>'1',
            'caliber'=>'small',
            'unidad'=>'Kg',
            'quality'=>'3',
            'fechaInicio'=>$startdate,
            'ActiveHours'=>'24',
            'amount'=>'100',
            'startPrice'=>'100',
            'endPrice'=>'50',
            'tipoSubasta'=>'private',
            'invitados'=>[0=>$comprador->id],
            'descri'=>$description
        );
        $this->post('/auctionstore',$data);
        $this->seeJson(['tested'=>true]);
        $this->assertResponseOk();
    }
    
}
