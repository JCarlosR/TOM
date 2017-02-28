<?php

use App\CategoryTranslation;
use Illuminate\Database\Seeder;

class CategoriesTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoryTranslation::create([
            'en' => 'Art',
            'es' => 'Arte'
        ]);

        CategoryTranslation::create([
            'en' => 'Arts & Entertainment',
            'es' => 'Entretenimiento'
        ]);

        CategoryTranslation::create([
            'en' => 'Baby Goods/Kids Goods',
            'es' => 'Para bebés y niños'
        ]);

        CategoryTranslation::create([
            'en' => 'Beauty',
            'es' => 'Belleza'
        ]);

        CategoryTranslation::create([
            'en' => 'Business Service',
            'es' => 'Servicios de negocio'
        ]);

        CategoryTranslation::create([
            'en' => 'Caterer',
            'es' => 'Proveedores'
        ]);

        CategoryTranslation::create([
            'en' => 'Company',
            'es' => 'Empresas'
        ]);

        CategoryTranslation::create([
            'en' => 'Consulting Agency',
            'es' => 'Agencias de consultoría'
        ]);

        CategoryTranslation::create([
            'en' => 'Design',
            'es' => 'Diseño'
        ]);

        CategoryTranslation::create([
            'en' => 'Education',
            'es' => 'Educación'
        ]);

        CategoryTranslation::create([
            'en' => 'Entertainment Website',
            'es' => 'Sitios web de entretenimiento'
        ]);

        CategoryTranslation::create([
            'en' => 'Event Planning Service',
            'es' => 'Planificación de eventos'
        ]);

        CategoryTranslation::create([
            'en' => 'Fashion',
            'es' => 'Moda'
        ]);

        CategoryTranslation::create([
            'en' => 'Food & Beverage Company',
            'es' => 'Alimentos y bebidas'
        ]);

        CategoryTranslation::create([
            'en' => 'Games/Toys',
            'es' => 'Juegos y juguetes'
        ]);

        CategoryTranslation::create([
            'en' => 'Gluten-Free Restaurant',
            'es' => 'Restaurantes dietéticos'
        ]);

        CategoryTranslation::create([
            'en' => 'Grocery Store',
            'es' => 'Bodegas'
        ]);

        CategoryTranslation::create([
            'en' => 'Health/Beauty',
            'es' => 'Salud y belleza'
        ]);

        CategoryTranslation::create([
            'en' => 'Home Decor',
            'es' => 'Decoración del hogar'
        ]);

        CategoryTranslation::create([
            'en' => 'Jewelry/Watches',
            'es' => 'Joyas y relojes'
        ]);

        CategoryTranslation::create([
            'en' => 'Kitchen/Cooking',
            'es' => 'Cocina'
        ]);

        CategoryTranslation::create([
            'en' => 'Legal Company',
            'es' => 'Empresa legal'
        ]);

        CategoryTranslation::create([
            'en' => 'Local Business',
            'es' => 'Negocio local'
        ]);

        CategoryTranslation::create([
            'en' => 'Medical & Health',
            'es' => 'Salud médica'
        ]);

        CategoryTranslation::create([
            'en' => 'Product/Service',
            'es' => 'Productos y servicios'
        ]);

        CategoryTranslation::create([
            'en' => 'Professional Service',
            'es' => 'Servicios profesionales'
        ]);

        CategoryTranslation::create([
            'en' => 'Real Estate',
            'es' => 'Bienes raíces'
        ]);

        CategoryTranslation::create([
            'en' => 'Region',
            'es' => 'Región'
        ]);

        CategoryTranslation::create([
            'en' => "Women's Health Clinic",
            'es' => 'Salud de la mujer'
        ]);

        CategoryTranslation::create([
            'en' => 'Writer',
            'es' => 'Escritores'
        ]);

    }
}
