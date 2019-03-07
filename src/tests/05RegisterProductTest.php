<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Constants;
use App\Product;
class RegisterProduct extends TestCase
{

    function testCreateNewProduct(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','presen');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->see('PRO-0089');
        $this->assertResponseOk();
    }
    function  testsaveANewProduct(){
        $boat = new Product();
        $this->unitAssert('assertTrue',$this->instanceClassProduct($boat,'Langostino','Cajones','10', '20', '30', 'langostinos-de-estero-cocidos.jpg','PRO-0089', 'Kg'));
    }
    function testProductNameWithSpace(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO- 008 9','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','presen');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->see('El código pesquero es alfanumerico maximo 10 caracteres');
        $this->assertResponseOk();
    }
    function testExisteNewProduct(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Camaron','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','presen');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('La relación nombre unidad ya se encuentra registrada');
        $this->assertResponseOk();
    }
    function testCreateProductWitHoutcode(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type(' ','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','presen');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo código pesquero es obligatorio');
        $this->assertResponseOk();
    }
    function testInvalidProductName(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type(' ','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','presen');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El nombre es obligatorio');
        $this->assertResponseOk();
    }
    function testInvalidProductUnit(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('','unidad');
        $this->type('Kg','presen');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('La unidad es obligatoria');
        $this->assertResponseOk();
    }
    function testInvalidProductPresentationUnit(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('','presen');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo unidad de presentacion es obligatorio');
        $this->assertResponseOk();
    }
    function testCaliberSmallObligatory(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','presen');
        $this->type(' ','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre chico es obligatorio');
        $this->assertResponseOk();
    }
    function testMediumCaliberObligatory(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','presen');
        $this->type('10','weight_small');
        $this->type(' ','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre mediano es obligatorio');
        $this->assertResponseOk();
    }
    function testCaliberBigObligatory(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','presen');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type(' ','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre grande es obligatorio');
        $this->assertResponseOk();
    }
    function testMandatoryImage(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','presen');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('La imagen es obligatoria');
        $this->assertResponseOk();
    }
    function testEditNewProduct(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/1/edit');
        $this->type('PRO-1002','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','presen');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->see('PRO-1002');
        $this->assertResponseOk();
    }
}
