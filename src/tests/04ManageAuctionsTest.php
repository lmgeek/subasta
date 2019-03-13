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
class CreateAuctionTest extends TestCase
{
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function getToAuctionAdd(){
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->see('Debes escoger un barco');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->see('Debes escoger un puerto');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->see('Debes escoger un producto');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
    function tryToAddPublicAuctionWithoutPresentationUnit(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+1 minutes"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+27 hours"))->format('d-m-Y H:i');
        $this->visit('subastas/agregar');
        $this->select($this->getAValidBoat(),'barco');
        $this->select($this->getAValidPort(),'puerto');
        $this->select($this->getAValidProduct(),'product');
        $this->select($this->getAValidCaliber(),'caliber');
        //$this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->see('Debes seleccionar una unidad de presentacion');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->see('El campo fecha inicio es obligatorio');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->see('La fecha inicio debe ser una fecha posterior a');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
        $this->select($this->getAValidPresentationUnit(),'unidad');
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
    function AddPublicAuction(){
        $this->withoutMiddleware();
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $description='NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.';
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+1 minutes"))->format('d-m-Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+72 hours"))->format('d-m-Y H:i');
        
        $data=array(
            'testing'=>true,
            'barco'=>$this->getAValidBoat(),
            'puerto'=>$this->getAValidPort(),
            'fechaTentativa'=>$tentativedate,
            'product'=>$this->getAValidProduct(),
            'caliber'=>$this->getAValidCaliber(),
            'unidad'=>$this->getAValidPresentationUnit(),
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
            'caliber'=>$this->getAValidCaliber(),
            'unidad'=>$this->getAValidPresentationUnit(),
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
            'unidad'=>$this->getAValidPresentationUnit(),
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
        $id = $this->getTheLastIdInsertedInTheSeller();
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
