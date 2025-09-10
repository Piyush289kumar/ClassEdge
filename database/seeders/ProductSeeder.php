<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\TaxSlab;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Makeup Base
            ['Foundation - Liquid', 'FOUNDLQ', 'Liquid foundation suitable for all skin types'],
            ['Foundation - Cream', 'FOUNDCR', 'Cream-based foundation for dry skin'],
            ['Concealer', 'CONCEAL', 'Concealer for blemishes and dark circles'],
            ['Primer', 'PRIMER', 'Face primer for smooth application'],
            ['Setting Powder', 'SETPWD', 'Loose powder for setting makeup'],

            // Eye Makeup
            ['Eyeshadow Palette', 'EYESHD', '12-color eyeshadow palette with matte and shimmer shades'],
            ['Eyeliner - Pencil', 'EYELINP', 'Black pencil eyeliner'],
            ['Eyeliner - Liquid', 'EYELINL', 'Liquid eyeliner for precise lines'],
            ['Mascara', 'MASCARA', 'Volumizing and lengthening mascara'],
            ['False Eyelashes', 'FALSEL', 'Set of synthetic false eyelashes'],

            // Lip Products
            ['Lipstick - Matte', 'LIPMAT', 'Matte lipstick with long-lasting wear'],
            ['Lip Gloss', 'LIPGLS', 'Shiny lip gloss for glossy finish'],
            ['Lip Liner', 'LIPLIN', 'Lip liner pencil for defining lips'],

            // Face Enhancers
            ['Blush', 'BLUSH', 'Powder blush for natural flush'],
            ['Highlighter', 'HIGHLIGHT', 'Shimmer highlighter for glowing skin'],
            ['Bronzer', 'BRONZER', 'Bronzing powder for contouring'],

            // Brushes & Tools
            ['Brush Set - 10 pcs', 'BRUSH10', 'Set of essential makeup brushes'],
            ['Beauty Blender', 'BLENDER', 'Sponge applicator for foundation and concealer'],
            ['Makeup Palette', 'PALETTE', 'Mixing palette for blending colors'],
            ['Tweezers', 'TWEEZE', 'Stainless steel tweezers for grooming'],
            ['Brush Cleaner', 'BRUSHCL', 'Solution for cleaning makeup brushes'],

            // Skincare
            ['Makeup Remover', 'REMOVER', 'Gentle makeup remover wipes'],
            ['Face Toner', 'TONER', 'Hydrating toner for prepping skin'],
            ['Moisturizer', 'MOIST', 'Daily moisturizer for soft skin'],

            // Accessories
            ['Mirror - LED', 'MIRLED', 'Compact mirror with LED lights'],
            ['Makeup Organizer', 'ORGBOX', 'Organizer box for storing cosmetics'],
            ['Apron', 'APRON', 'Apron to keep clothes clean during makeup sessions'],
            ['Headband', 'HEADBD', 'Soft headband for keeping hair away from face'],
        ];

        $unit = Unit::first(); // You can randomize if needed
        $brand = Brand::first();
        $taxSlab = TaxSlab::first();
        $category = Category::where('name', 'Makeup')->first(); // Ensure this category exists or create it

        foreach ($products as $item) {
            Product::create([
                'name' => $item[0],
                'sku' => $item[1],
                'barcode' => strtoupper(Str::random(12)),
                'unit_id' => $unit?->id,
                'brand_id' => $brand?->id,
                'category_id' => $category?->id,
                'hsn_code' => '330499',
                'tax_slab_id' => $taxSlab?->id,
                'gst_rate' => $taxSlab?->rate ?? 18,
                'purchase_price' => rand(100, 1000),
                'selling_price' => rand(150, 1500),
                'mrp' => rand(200, 2000),
                'track_inventory' => true,
                'min_stock' => rand(5, 20),
                'max_stock' => rand(50, 200),
                'image_path' => null,
                'is_active' => true,
                'meta' => [],
            ]);
        }

    }
}
