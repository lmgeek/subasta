<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Http\FormRequest;

class makeOffersTest extends TestCase
{
    /** @test */
    function openDialogInOffersAndSaveOffers(){

        $user = \App\User::find(5);
        $userAuth =  $this->actingAs($user);
        $userAuth->visit('/');
        $userAuth->click('realizar una oferta');
        $userAuth->see('Subastas del Mar');
        $userAuth->get('/ofertas/agregar?auction_id=2&prices=502,00');
    }

  /** @test */
    function validateThatTheFieldOfferPriceIsNotEempty()
    {
        $user = \App\User::find(5);
        $userAuth =  $this->actingAs($user);
        $userAuth->visit('/');
        $userAuth->click('realizar una oferta');
        $userAuth->see('Subastas del Mar');
        $userAuth->type('','OfferPrice2');
        $userAuth->get('/ofertas/agregar?auction_id=2&prices=');
        $userAuth->assertFalse(false);

    }

    /** @test */
    function makeYourPurchaseNow()
    {
        $user = \App\User::find(5);
        $userAuth =  $this->actingAs($user);
        $userAuth->visit('/');
        $userAuth->click('realizar una oferta');
        $userAuth->click('Realiza tu compra ahora');
        $userAuth->see('Sólo un paso más y es tuyo');
        $userAuth->type('2','qtyInput');
        $userAuth->get('/makeBid?auction_id=5&price=503&amount=20');
        $userAuth->assertFalse(false);

    }



}
