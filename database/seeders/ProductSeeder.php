<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener categorías y tipos
        $aceroBlanco = Category::where('name', 'Acero Blanco')->first();
        $aceroDorado = Category::where('name', 'Acero Dorado')->first();
        $tipoAro = Type::where('name', 'Aro')->first();
        $tipoAnillo = Type::where('name', 'Anillo')->first();

        // Crear productos - Anillos
        Product::create([
            'name' => 'Anillo Minimalista Dorado',
            'description' => 'Elegante anillo de acero dorado con diseño minimalista, perfecto para uso diario',
            'price' => 24.99,
            'purchasePrice' => 12.50,
            'stock' => 20,
            'imageUrl' => '/storage/products/anillo1.jpg',
            'status' => true,
            'category_id' => $aceroDorado->id,
            'type_id' => $tipoAnillo->id,
        ]);

        Product::create([
            'name' => 'Anillo Trenzado Plateado',
            'description' => 'Anillo de acero blanco con diseño trenzado, ideal para ocasiones especiales',
            'price' => 29.99,
            'purchasePrice' => 15.00,
            'stock' => 15,
            'imageUrl' => '/storage/products/anillo2.jpg',
            'status' => true,
            'category_id' => $aceroBlanco->id,
            'type_id' => $tipoAnillo->id,
        ]);

        Product::create([
            'name' => 'Anillo Geométrico Dorado',
            'description' => 'Moderno anillo de acero dorado con formas geométricas contemporáneas',
            'price' => 32.50,
            'purchasePrice' => 16.75,
            'stock' => 18,
            'imageUrl' => '/storage/products/anillo3.jpg',
            'status' => true,
            'category_id' => $aceroDorado->id,
            'type_id' => $tipoAnillo->id,
        ]);

        // Crear productos - Aros
        Product::create([
            'name' => 'Aros Circulares Pequeños',
            'description' => 'Aros circulares de acero blanco, tamaño pequeño, perfectos para un look discreto',
            'price' => 18.99,
            'purchasePrice' => 9.50,
            'stock' => 30,
            'imageUrl' => '/storage/products/aro1.jpg',
            'status' => true,
            'category_id' => $aceroBlanco->id,
            'type_id' => $tipoAro->id,
        ]);

        Product::create([
            'name' => 'Aros Largos Dorados',
            'description' => 'Aros largos de acero dorado con diseño elegante, ideales para eventos formales',
            'price' => 26.50,
            'purchasePrice' => 13.25,
            'stock' => 25,
            'imageUrl' => '/storage/products/aro2.jpg',
            'status' => true,
            'category_id' => $aceroDorado->id,
            'type_id' => $tipoAro->id,
        ]);
    }
}
