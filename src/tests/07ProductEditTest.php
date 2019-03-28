<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Constants;
use App\Http\Requests\EditProductRequest;
use App\Product;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class ProductEditTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */

    public function setUp()
    {
        parent::setUp();
        $this->rules = (new \App\Http\Requests\EditProductRequest())->rules();
        $this->validator = $this->app['validator'];
    }


    /** @test */
    public function validateFielCodeIsRequired()
    {
        $this->unitAssert('assertFalse',$this->validateField('codigo', "0,00"));
    }

    /** @test */
    public function validateFieldCodeIsreaterThanTen()
    {
        $this->unitAssert('assertFalse',$this->validateField('codigo', "0987654322156451541"));
    }

    /** @test */
    public function validateFieldNameRequired()
    {
        $this->unitAssert('assertFalse',$this->validateField('name', " "));
    }

  /** @test */
    public function validateNameFieldDoesNotAcceptSpecialValues()
    {
        $this->unitAssert('assertFalse',$this->validateField('name', "ª!\*·$%&/()*/-+-.,ç´`+^¨Ç^[]{}"));
    }

    /**
     *              Product small
     * @test
     */
        public function validatePresentationUnitFieldRequiredInSmallProduct()
    {
        $this->unitAssert('assertFalse',$this->validateField('unidadp', " "));
    }

    /** @test */
        public function validateApproximateWeightRequiredInSmallproduct()
    {
        $this->unitAssert('assertFalse',$this->validateField('weight_small', "  "));
    }

    /** @test */
        public function validateUnitOfSaleRequiredInSmallProduct()
    {
        $this->unitAssert('assertFalse',$this->validateField('salep', " "));
    }


    /**
     *              Product Medium
     * @test
     */
    public function validatePresentationUnitFieldRequiredInMediumProduct()
    {
        $this->unitAssert('assertFalse',$this->validateField('unidadm', " "));
    }

    /** @test */
    public function validateApproximateWeightRequiredInMediumProduct()
    {
        $this->unitAssert('assertFalse',$this->validateField('weight_medium', "  "));
    }

    /** @test */
    public function validateUnitOfSaleRequiredInMediumProduct()
    {
        $this->unitAssert('assertFalse',$this->validateField('salem', " "));
    }


    /**
     *              Product Big
     * @test
     */
    public function validatePresentationUnitFieldRequiredInBigProduct()
    {
        $this->unitAssert('assertFalse',$this->validateField('unidadg', " "));
    }

    /** @test */
    public function validateApproximateWeightRequiredInBigProduct()
    {
        $this->unitAssert('assertFalse',$this->validateField('weight_big', "  "));
    }

    /** @test */
    public function validateUnitOfSaleRequiredInBigProduct()
    {
        $this->unitAssert('assertFalse',$this->validateField('saleg', " "));
    }

    /** @test */
    public function viewPageEditProduct()
    {
        $this->unit(9,'visit','products/2/edit');
        $this->unit(9,'see','','Editar Producto');
        $this->unit(9,'assertResponseOk');

    }

      /** @test */
    public function validateSaveButtonInEditProduct()
    {
        $this->unit(9,'visit','products/2/edit');
        $this->unit(9,'see','','Editar Producto');
        $this->unit(9,'type','','004','codigo');
        $this->unit(9,'type','','Camarones','name');

        $this->unit(9,'select','','Pastillas','unidadp');
        $this->unit(9,'type','','200','weight_small');
        $this->unit(9,'select','','Unidades','salep');

        $this->unit(9,'select','','Unidades','unidadm');
        $this->unit(9,'type','','250','weight_medium');
        $this->unit(9,'select','','Kg','salem');

        $this->unit(9,'select','','Pastillas','unidadg');
        $this->unit(9,'type','','500','weight_big');
        $this->unit(9,'select','','Unidades','saleg');
/*        $this->attach('img/products', __DIR__.'/Imágenes/monta.jpg');*/

        $this->unit(9,'press','','Guardar');
    }

    /** @test */
    public function validateCancelButtonInEditProduct(){

        $this->unit(9,'visit','products/2/edit');
        $this->unit(9,'see','','Editar Producto');
        $this->unit(9,'click','','','Cancelar');
        $this->unit(9,'visit','','','/products');

    }



}
