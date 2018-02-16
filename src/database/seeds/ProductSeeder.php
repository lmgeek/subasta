<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [ "name" => 'Merluza' , "unit" => Product::CAJONES, ],
            [ "name" => 'Bacalao' , "unit" => Product::UNIDADES, ],
            [ "name" => 'Bonito'  , "unit" => Product::CAJONES, ],
            [ "name" => 'Lenguado', "unit" => Product::UNIDADES, ],
            [ "name" => 'Sardina' , "unit" => Product::CAJONES, ],
        ];

        foreach($products as $p) {
            Product::create($p);
        }
    }
}
