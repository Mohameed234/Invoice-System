<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Product;
use App\Models\Currency;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create currencies
        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'exchange_rate' => 1.000000, 'is_default' => true],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'exchange_rate' => 0.850000],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'exchange_rate' => 0.730000],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(['code' => $currency['code']], $currency);
        }

        // Create categories
        $categories = [
            ['name' => 'Web Development', 'description' => 'Website and web application development services', 'color' => '#3B82F6'],
            ['name' => 'Design', 'description' => 'Graphic design and UI/UX services', 'color' => '#10B981'],
            ['name' => 'Consulting', 'description' => 'Business and technical consulting services', 'color' => '#F59E0B'],
            ['name' => 'Hosting', 'description' => 'Web hosting and server management services', 'color' => '#EF4444'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }

        // Create customers
        $customers = [
            [
                'name' => 'Acme Corporation',
                'email' => 'contact@acme.com',
                'phone' => '+1-555-0123',
                'address' => '123 Business St, New York, NY 10001',
                'tax_number' => 'TAX123456789',
                'type' => 'company',
                'notes' => 'Regular client with monthly retainer'
            ],
            [
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'phone' => '+1-555-0456',
                'address' => '456 Personal Ave, Los Angeles, CA 90210',
                'tax_number' => null,
                'type' => 'individual',
                'notes' => 'Freelance consultant'
            ],
            [
                'name' => 'TechStart Inc',
                'email' => 'hello@techstart.com',
                'phone' => '+1-555-0789',
                'address' => '789 Innovation Blvd, San Francisco, CA 94105',
                'tax_number' => 'TAX987654321',
                'type' => 'company',
                'notes' => 'Startup company, flexible payment terms'
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(['email' => $customer['email']], $customer);
        }

        // Create products
        $products = [
            [
                'name' => 'Website Development',
                'description' => 'Custom website development with responsive design',
                'unit_price' => 2500.00,
                'tax_rate' => 8.5,
                'category_id' => Category::where('name', 'Web Development')->first()->id,
                'type' => 'service',
                'sku' => 'WEB-DEV-001'
            ],
            [
                'name' => 'Logo Design',
                'description' => 'Professional logo design with multiple concepts',
                'unit_price' => 500.00,
                'tax_rate' => 8.5,
                'category_id' => Category::where('name', 'Design')->first()->id,
                'type' => 'service',
                'sku' => 'DESIGN-001'
            ],
            [
                'name' => 'Business Consultation',
                'description' => 'Strategic business consultation and planning',
                'unit_price' => 150.00,
                'tax_rate' => 8.5,
                'category_id' => Category::where('name', 'Consulting')->first()->id,
                'type' => 'service',
                'sku' => 'CONS-001'
            ],
            [
                'name' => 'Web Hosting (Monthly)',
                'description' => 'Premium web hosting with SSL certificate',
                'unit_price' => 25.00,
                'tax_rate' => 8.5,
                'category_id' => Category::where('name', 'Hosting')->first()->id,
                'type' => 'service',
                'sku' => 'HOST-001'
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['sku' => $product['sku']], $product);
        }
    }
}
