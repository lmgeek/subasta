<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Constants;
use App\Product;
use App\ProductDetail;
class RegisterProduct extends TestCase
{
    /*codigo pesquero */
    function testCreateNewProduct(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->see('PRO-0089');
        $this->assertResponseOk();
    }
    function  testsaveANewProduct(){
        $produc = new Product();
        $produc_daetail = new ProductDetail();
        $produc_daetail2 = new ProductDetail();
        $produc_daetail3 = new ProductDetail();
        $this->unitAssert('assertTrue',$this->instanceClassProduct
        ($produc,$produc_daetail,$produc_daetail2,$produc_daetail3,'PRO-0089','Langostino','Cajones', ' 10', 'Cajones', 'Cajon','20', 'Kg', 'Unidades', '30', 'Unidades', 'langostinos-de-estero-cocidos.jpg'));
    }
    function testProductNameWithSpace(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO- 008 9','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->see('PRO- 008 9');
        $this->assertResponseOk();
    }
    function testCodeWithSpecialCharacters(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-/008*9','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->see('El código pesquero es alfanumerico y "-" maximo 10 caracteres');
        $this->assertResponseOk();
    }
    function testSmallCodeNull(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->see('El campo código pesquero es obligatorio');
        $this->assertResponseOk();
    }
    function testCreateProductWitHoutcode(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type(' ','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo código pesquero es obligatorio');
        $this->assertResponseOk();
    }
    /*producto ya exite */
    function testExisteNewProduct(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-089','codigo');
        $this->type('Camaron','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('La relación nombre unidad ya se encuentra registrada');
        $this->assertResponseOk();
    }
    /*nombre*/
    function testInvalidProductName(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('   ','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El nombre es obligatorio');
        $this->assertResponseOk();
    }
    function testNameNull(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El nombre es obligatorio');
        $this->assertResponseOk();
    }
    function testNameWithNumeric(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('1456aa','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El nombre sólo permite caracteres alfabéticos');
        $this->assertResponseOk();
    }
    function testNameWithSpecialCharacters(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('dd/*daa','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El nombre sólo permite caracteres alfabéticos');
        $this->assertResponseOk();
    }
    /*unidad de presentacion*/
    function testInvalidProductUnit(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('La unidad es obligatoria');
        $this->assertResponseOk();
    }
    /*unidad de venta*/
    function testInvalidProductPresentationUnit(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo unidad de venta es obligatorio');
        $this->assertResponseOk();
    }
    /*calibre pequeño*/
    function testCaliberSmallObligatory(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type(' ','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre chico es obligatorio');
        $this->assertResponseOk();
    }
    function testSmallCaliberMayTo0(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('0,00','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre chico debe ser mayor a 0,00');
        $this->assertResponseOk();
    }
    function testSmallCaliberWithLetter(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('hola','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre chico sólo permite caracteres numéricos');
        $this->assertResponseOk();
    }
    /*calibre mediano*/
    function testMediumCaliberObligatory(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type(' ','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre mediano es obligatorio');
        $this->assertResponseOk();
    }
    function testMediumCaliberMayTo0(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('0,00','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre mediano debe ser mayor a 0,00');
        $this->assertResponseOk();
    }
    function testMediumCaliberWithLyrics(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('hola','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre mediano sólo permite caracteres numéricos');
        $this->assertResponseOk();
    }
    /*calibre grande*/
    function testCaliberBigObligatory(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type(' ','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre grande es obligatorio');
        $this->assertResponseOk();
    }
    function testLargeCaliberMayTo0(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('0,00','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre grande debe ser mayor a 0,00');
        $this->assertResponseOk();
    }
    function testLargeCaliberWithLetter(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('hola','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El peso de calibre grande sólo permite caracteres numéricos');
        $this->assertResponseOk();
    }
    /*imagen*/
    function testMandatoryImage(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('La imagen es obligatoria');
        $this->assertResponseOk();
    }
    /*editar*/
    function testEditNewProduct(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/1/edit');
        $this->type('PRO-1002','codigo');
        $this->type('Langostino','nombre');
        $this->type('Cajones','unidad');
        $this->type('Kg','sale');
        $this->type('10','weight_small');
        $this->type('20','weight_medium');
        $this->type('30','weight_big');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->see('PRO-1002');
        $this->assertResponseOk();
    }
}
