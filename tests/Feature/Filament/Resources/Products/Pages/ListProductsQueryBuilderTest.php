<?php

use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Brand;
use App\Models\Product;

use function Pest\Livewire\livewire;

it('can filter products by one brand', function (): void {

    $brand = Brand::factory()->create();

    $products = Product::factory()->count(2)->create(['brand_id' => $brand]);

    $productsWithOtherBrand = Product::factory()->count(2)->create(['brand_id' => Brand::factory()]);

    livewire(ListProducts::class)
        ->assertCanSeeTableRecords($products->merge($productsWithOtherBrand))
        ->set('tableDeferredFilters.queryBuilder.rules', [
            [
                'type' => 'brand',
                'data' => [
                    'operator' => 'isRelatedTo',
                    'settings' => ['value' => [$brand->getKey()]],
                ],
            ],
        ])
        ->call('applyTableFilters')
        ->assertCountTableRecords(2)
        ->assertCanSeeTableRecords($products)
        ->assertCanNotSeeTableRecords($productsWithOtherBrand);
});

it('can filter products by multiple brands', function (): void {

    $brands = Brand::factory()->count(2)->create();

    $products = Product::factory()->count(2)->create(['brand_id' => $brands->first()]);

    $products = $products->merge(Product::factory()->count(2)->create(['brand_id' => $brands->last()]));

    $productsWithOtherBrand = Product::factory()->count(2)->create(['brand_id' => Brand::factory()]);

    livewire(ListProducts::class)
        ->assertCanSeeTableRecords($products->merge($productsWithOtherBrand))
        ->set('tableDeferredFilters.queryBuilder.rules', [
            [
                'type' => 'brand',
                'data' => [
                    'operator' => 'isRelatedTo',
                    'settings' => ['value' => $brands->modelKeys()],
                ],
            ],
        ])
        ->call('applyTableFilters')
        ->assertCountTableRecords(4)
        ->assertCanSeeTableRecords($products)
        ->assertCanNotSeeTableRecords($productsWithOtherBrand);
});
