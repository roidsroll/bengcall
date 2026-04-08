<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $transaction = Menu::firstOrCreate(
            [
                'parent_id' => null,
                'name' => 'Transaksi',
            ],
            [
                'url' => null,
                'icon' => 'fa-solid fa-receipt',
                'order' => 2,
            ]
        );

        $ordersMenu = Menu::firstOrCreate(
            [
                'parent_id' => $transaction->id,
                'name' => 'Orders',
            ],
            [
                'url' => '/admin/orders',
                'icon' => 'fa-solid fa-cart-shopping',
                'order' => 10,
            ]
        );

        $masterData = Menu::firstOrCreate(
            [
                'parent_id' => null,
                'name' => 'Master Data',
            ],
            [
                'url' => null,
                'icon' => 'fa-solid fa-database',
                'order' => 1,
            ]
        );

        $categoryMenu = Menu::firstOrCreate(
            [
                'parent_id' => $masterData->id,
                'name' => 'Master Categories',
            ],
            [
                'url' => '/admin/categories',
                'icon' => 'fa-solid fa-tags',
                'order' => 50,
            ]
        );

        $brandMenu = Menu::firstOrCreate(
            [
                'parent_id' => $masterData->id,
                'name' => 'Master Brands',
            ],
            [
                'url' => '/admin/brands',
                'icon' => 'fa-solid fa-copyright',
                'order' => 51,
            ]
        );

        $productMenu = Menu::firstOrCreate(
            [
                'parent_id' => $masterData->id,
                'name' => 'Master Products',
            ],
            [
                'url' => '/admin/products',
                'icon' => 'fa-solid fa-boxes-stacked',
                'order' => 52,
            ]
        );

        $discountMenu = Menu::firstOrCreate(
            [
                'parent_id' => $masterData->id,
                'name' => 'Master Discounts',
            ],
            [
                'url' => '/admin/discounts',
                'icon' => 'fa-solid fa-percent',
                'order' => 54,
            ]
        );

        $supplierMenu = Menu::firstOrCreate(
            [
                'parent_id' => $masterData->id,
                'name' => 'Master Suppliers',
            ],
            [
                'url' => '/admin/supplier',
                'icon' => 'fa-solid fa-truck-field',
                'order' => 55,
            ]
        );

        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            $adminRole->menus()->syncWithoutDetaching([
                $ordersMenu->id,
                $categoryMenu->id,
                $brandMenu->id,
                $productMenu->id,
                $discountMenu->id,
                $supplierMenu->id,
            ]);
        }
    }
}
