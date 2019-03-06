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
class CreateAuctionTest extends TestCase
{
    
//    var $descriptionshort='Descripcion corta';
//    var $descriptionlong='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nec sem auctor, condimentum erat in, mollis leo. Duis nulla nisi, interdum sed velit vitae, blandit elementum eros. Nunc iaculis quam erat, vel suscipit mauris consectetur quis. Aenean a mattis enim, id congue eros. Donec eget efficitur velit, sed blandit urna. Vivamus imperdiet in quam id rutrum. Nulla odio elit, tincidunt quis ligula et, auctor pulvinar sem. Vivamus posuere lacus a felis feugiat, vel mattis lorem volutpat. Vivamus varius pharetra dapibus. Etiam ut augue eu tortor sagittis cursus. Pellentesque vel mi tincidunt, venenatis metus eu, fermentum orci. Nulla vulputate feugiat tempor. Nullam varius eleifend porttitor. Vivamus rutrum libero molestie ligula facilisis auctor. Ut ultrices finibus justo, eu tempor ligula tempor in. Donec vitae ligula quis neque bibendum tempus.Cras quis ipsum auctor, dapibus erat eu, iaculis orci. Mauris auctor vulputate commodo. Maecenas sed est efficitur, sagittis felis pharetra, fermentum velit. Integer vel nisi vitae odio dictum auctor. Proin magna mauris, accumsan eu auctor ut, consectetur et leo. Integer ac eros a lectus varius elementum. Morbi interdum tempor lorem, vitae dapibus nibh mattis id.';
//    var $descriptiongood='NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.';
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function getToAuctionAdd(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->visit('/auction/add');
        $this->see('Arribo');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function selectPreferredPortOnSelectingBoat(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $this->json('GET', '/getpreferredport', ['idboat' => '1'])
             ->seeJsonEquals([
                 'preferred' => '4'
             ]);
        $this->assertResponseOk();
    }
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
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutTentativeDate(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("-2 hours"))->format('d/m/Y H:i');
        $tentativedate='';
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('El campo fecha tentativa es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithTentativeDatePast(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("-2 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('La fecha tentativa debe ser una fecha posterior a');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutStartDate(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate='';
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('El campo fecha inicio es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithStartDatePast(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("-2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('La fecha inicio debe ser una fecha posterior a');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutActiveHours(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('El campo horas activas es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithLessThanOneActiveHours(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('0','ActiveHours');
        $this->type('10','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('La cantidad de horas activas debe ser al menos');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutAmount(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('El campo cantidad es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithLessThanOneAmount(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('0','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('La cantidad debe ser de al menos 1');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutStartPrice(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('','startPrice');
        $this->type('10','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('El campo precio inicial es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutEndPrice(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('100','startPrice');
        $this->type('','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('El campo precio final es obligatorio');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithStartPriceLowerEndPrice(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('50','startPrice');
        $this->type('500','endPrice');
        $this->type('Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('El precio final no puede ser mayor al precio inicial.');
        $this->assertResponseOk();
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function tryToAddPublicAuctionWithoutDescription(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type('','descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
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
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type($description,'descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
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
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type($description,'descri');
        $this->press('Enviar');
        $this->seePageIs('/auction/add');
        $this->see('descripci&oacute;n no debe ser mayor que 1000 caracteres');
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function AddPublicAuction(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $description='NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.';
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+12 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+24 hours"))->format('d/m/Y H:i');
        $this->visit('auction/add');
        $this->type($tentativedate,'fechaTentativa');
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('100','amount');
        $this->type('100','startPrice');
        $this->type('10','endPrice');
        $this->type($description,'descri');
        $this->press('Enviar');
        $this->see($description);
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
        $description='Esta descripcion se edito. NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.';
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+24 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+48 hours"))->format('d/m/Y H:i');
        
        $data=array(
            '_token'=>Session::token(),
            'auctionid'=>$auction->id,
            'batchid'=>$auction->batch_id,
            'arriveid'=>$auction->batch->arrive_id,
            'testing'=>true,
            'barco'=>'4',
            'puerto'=>'2',
            'fechaTentativa'=>$tentativedate,
            'product'=>'3',
            'caliber'=>'big',
            'unidad'=>'Kg',
            'quality'=>'3',
            'fechaInicio'=>$startdate,
            'ActiveHours'=>'24',
            'amount'=>'200',
            'startPrice'=>'2000',
            'endPrice'=>'1000',
            'tipoSubasta'=>'public',
            'descri'=>$description
        );
        $this->post('/auctionstore',$data);
        $this->seeJson(['tested'=>true]);
        $this->seeJson(['description'=>$description]);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function ReplicateLastAuction(){
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $description='Subasta Replicada. NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.';
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+12 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+24 hours"))->format('d/m/Y H:i');
        $auction=$this->getLastAuction();
        $this->visit('auction/replicate/'.$auction->id);
        $this->type($startdate,'fechaInicio');
        $this->type('24','ActiveHours');
        $this->type('1000','amount');
        $this->type('1000','startPrice');
        $this->type('100','endPrice');
        $this->type($description,'descri');
        $this->press('Enviar');
        $this->see($description);
        $this->assertResponseOk();
    }
    /**  @test
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     */
    function AddPrivateAuction(){
        $this->withoutMiddleware();
        $this->actingAs($this->getAValidUser(Constants::VENDEDOR));
        $comprador=$this->getAValidUser(Constants::COMPRADOR);
        $description='NetLabs nace de la unión de jóvenes emprendedores y profesionales con 12 años de experiencia en el mercado informático nacional e internacional. En Netlabs desarrollamos aplicaciones según las necesidades de nuestros clientes y del mercado. Contamos con un equipo de profesionales experimentados en desarrollo de software, servicios de consultoría y asesoría en tecnologías de información.';
        $startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
        $tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+10 hours"))->format('d/m/Y H:i');
        
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
