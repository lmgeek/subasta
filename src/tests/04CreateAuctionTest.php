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
class CreateAuctionTest extends TestCase
{
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @covers AuctionController::addAuction
     */
    function getToAuctionAdd(){
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->unit($id,'visit','/auction/add');
        $this->unit($id,'see','','Arribo');
        $this->unit($id,'responseOk');
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @covers BoatController::getPreferredPort
     */
    function selectPreferredPortOnSelectingBoat(){
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->json('GET', '/getpreferredport', ['idboat' => '1'])
             ->seeJsonEquals([
                 'preferred' => '4'
             ]);
        $this->unit($id,'responseOk');
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @covers AuctionController::getUsersAuctionPrivate
     */
    function getValidUsersForPrivateAuctionWithNoneSelected(){
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->json('GET', '/getusersauctionprivate', ['val' => 'rafael a'])
             ->seeJsonEquals([
                 0=>[
                     'id' => 5,
                    'name'=>'rafael',
                    'lastname'=>'alvarez',
                    'nickname'=>'rafa'
                 ]
             ]);
        $this->unit($id,'responseOk');
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @covers AuctionController::getUsersAuctionPrivate
     */
    function getNoUsersForPrivateAuctionWithOneSelected(){
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->json('GET', '/getusersauctionprivate', ['val' => 'rafael a','ids'=>array('5')])
             ->seeJsonEquals([
             ]);
        $this->unit($id,'responseOk');
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @covers AuctionController::getUsersAuctionPrivate
     */
    function tryToGetPendingUserForPrivateAuction(){
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->json('GET', '/getusersauctionprivate', ['val' => 'ezequiel bikiel'])
             ->seeJsonEquals([
             ]);
        $this->unit($id,'responseOk');
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @covers AuctionController::getUsersAuctionPrivate
     */
    function tryToGetRejectedUserForPrivateAuction(){
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->json('GET', '/getusersauctionprivate', ['val' => 'lorena perez'])
             ->seeJsonEquals([
             ]);
        $this->unit($id,'responseOk');
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @covers AuctionController::getUsersAuctionPrivate
     */
    function tryToGetSellerForPrivateAuction(){
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->json('GET', '/getusersauctionprivate', ['val' => 'Maria Crer'])
             ->seeJsonEquals([
             ]);
        $this->unit($id,'responseOk');
    }
    /**  @test 
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @covers AuctionController::getUsersAuctionPrivate
     */
    function tryToGetAdminForPrivateAuction(){
        $id = $this->getTheLastIdInsertedInTheSeller();
        $this->json('GET', '/getusersauctionprivate', ['val' => 'diego weinstein'])
             ->seeJsonEquals([
             ]);
        $this->unit($id,'responseOk');
    }
}
