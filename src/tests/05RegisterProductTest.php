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
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');
        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');
        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
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
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->see('PRO- 008 9');
        $this->assertResponseOk();
    }
    function testCodeWithSpecialCharacters(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-/008*9','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->see('El código pesquero es alfanumerico y "-" maximo 10 caracteres');
        $this->assertResponseOk();
    }
    function testSmallCodeNull(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('','codigo');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->see('El campo código pesquero es obligatorio');
        $this->assertResponseOk();
    }
    function testCreateProductWitHoutcode(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type(' ','codigo');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo código pesquero es obligatorio');
        $this->assertResponseOk();
    }
//    /*producto ya exite */
    function testExisteNewProductchico(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-00023','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');
        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');
        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El producto');
        $this->assertResponseOk();
    }
    function testExisteNewProductmediano(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-00023','codigo');
        $this->type('Langostino','name');
        $this->select('Unidades','unidadp');
        $this->type('10','weight_small');
        $this->select('Unidades','salep');
        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');
        $this->select('Cajones','unidadg');
        $this->type('30','weight_big');
        $this->select('Cajones','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El producto');
        $this->assertResponseOk();
    }
//    /*name*/
    function testInvalidProductName(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('   ','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Nombre es obligatorio');
        $this->assertResponseOk();
    }
    function testNameNull(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Nombre es obligatorio');
        $this->assertResponseOk();
    }
    function testNameWithNumeric(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('1456aa','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El Nombre sólo permite caracteres alfabéticos');
        $this->assertResponseOk();
    }
    function testNameWithSpecialCharacters(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('dd/*daa','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El Nombre sólo permite caracteres alfabéticos');
        $this->assertResponseOk();
    }
//    /*unidad de presentacion*/
    function testInvalidProductUnitchico(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');
        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');
        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Unidad de Presentación del Calibre Chico es obligatorio');
        $this->assertResponseOk();
    }
    function testInvalidProductUnitediano(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');
        $this->select('','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');
        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Unidad de Presentación del Calibre Mediana es obligatorio');
        $this->assertResponseOk();
    }
    function testInvalidProductUnit(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');
        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');
        $this->select('','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Unidad de Presentación del Calibre Grande es obligatorio');
        $this->assertResponseOk();
    }
//    /*unidad de venta*/
    function testInvalidProductPresentationUnitchico(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('','salep');
        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');
        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Unidad de Venta del Calibre Chico es obligatorio');
        $this->assertResponseOk();
    }
    function testInvalidProductPresentationUnitmediano(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');
        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('','salem');
        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Unidad de Venta del Calibre Mediana es obligatorio');
        $this->assertResponseOk();
    }
    function testInvalidProductPresentationUnitbig(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');
        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Kg','salem');
        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Unidad de Venta del Calibre Grande es obligatorio');
        $this->assertResponseOk();
    }
//    /*calibre pequeño*/
    function testCaliberSmallObligatory(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type(' ','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Peso por Calibre Chico es obligatorio');
        $this->assertResponseOk();
    }
    function testSmallCaliberMayTo0(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('0,00','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El Peso por Calibre Chico debe ser mayor a 0,00');
        $this->assertResponseOk();
    }
    function testSmallCaliberWithLetter(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('hola','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El Peso por Calibre Chico sólo permite caracteres numéricos');
        $this->assertResponseOk();
    }
//    /*calibre mediano*/
    function testMediumCaliberObligatory(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type(' ','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Peso por Calibre Mediano es obligatorio');
        $this->assertResponseOk();
    }
    function testMediumCaliberMayTo0(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('0,00','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El Peso por Calibre Mediano debe ser mayor a 0,00');
        $this->assertResponseOk();
    }
    function testMediumCaliberWithLyrics(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('hola','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El Peso por Calibre Mediano sólo permite caracteres numéricos');
        $this->assertResponseOk();
    }
//    /*calibre grande*/
    function testCaliberBigObligatory(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type(' ','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Peso por Calibre Grande es obligatorio');
        $this->assertResponseOk();
    }
    function testLargeCaliberMayTo0(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('0,00','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El Peso por Calibre Grande debe ser mayor a 0,00');
        $this->assertResponseOk();
    }
    function testLargeCaliberWithLetter(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('hola','weight_big');
        $this->select('Kg','saleg');
        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El Peso por Calibre Grande sólo permite caracteres numéricos');
        $this->assertResponseOk();
    }
//    /*imagen*/
    function testMandatoryImage(){
        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
        $this->visit('/products/create');
        $this->type('PRO-0089','codigo');
        $this->type('Langostino','name');
        $this->select('Cajones','unidadp');
        $this->type('10','weight_small');
        $this->select('Cajones','salep');

        $this->select('Cajas','unidadm');
        $this->type('20','weight_medium');
        $this->select('Cajones','salem');

        $this->select('Unidades','unidadg');
        $this->type('30','weight_big');
        $this->select('Kg','saleg');
        $this->attach('','imagen');
        $this->press('Guardar');
        $this->seePageIs('/products/create');
        $this->see('El campo Imagen es obligatorio');
        $this->assertResponseOk();
    }
//    /*editar*/
//    function testEditNewProduct(){
//        $this->actingAs($this->getAValidUser(Constants::INTERNAL));
//        $this->visit('/products/1/edit');
//        $this->type('PRO-1002','codigo');
//        $this->type('Langostino','name');
//        $this->type('Cajones','unidad');
//        $this->type('Kg','sale');
//        $this->type('10','weight_small');
//        $this->type('20','weight_medium');
//        $this->type('30','weight_big');
//        $this->attach('langostinos-de-estero-cocidos.jpg','imagen');
//        $this->press('Guardar');
//        $this->see('PRO-1002');
//        $this->assertResponseOk();
//    }
}
