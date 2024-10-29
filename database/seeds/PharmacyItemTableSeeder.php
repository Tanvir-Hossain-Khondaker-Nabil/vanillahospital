<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Item;
use App\Http\Traits\StockTrait;

class PharmacyItemTableSeeder extends Seeder
{
    use StockTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $company = DB::table("company")->where('status', 1)->get();

        // foreach ($company as  $item) {
        //     Brand::firstOrCreate([
        //         'slug' => snake_case($item->comp_name),
        //         'name' => $item->comp_name,
        //         'status' => "active",
        //         'created_by' => 1,
        //         'member_id' => 1,
        //         'company_id' => 1
        //     ]);
        // }

        // $units = array(
        //     0 => 'Pcs',
        //     1 => 'Pcs',
        //     2 => 'ML',
        //     3 => '1 Pata'
        // );


        // foreach ($units as $key => $item) {
        //     Unit::firstOrCreate([
        //         'name' => strtolower($item),
        //         'slug' => snake_case($item),
        //         'created_by' => 1,
        //         'display_name' => $item
        //     ]);

        // }


        // $product_category = DB::table("product_category")->where('status', 1)->get();

        // foreach ($product_category as $key => $item) {
        //     Category::firstOrCreate([
        //         'name' => snake_case($item->p_category_name),
        //         // 'slug' => snake_case($item),
        //         'display_name' => $item->p_category_name,
        //         'status' => "active",
        //         'created_by' => 1,
        //         'company_id' => 1
        //     ]);

        // }


        // $products = DB::table("product")->where('p_unit_id', "!=",0)->where('p_cat_id',"!=", 0)->where('p_company_id',"!=", 0)->where('p_generic_id',"!=", 0)->where('status', 1)->get();


        // foreach ($products->chunk(1000) as $chunk)
        // {
        //     $inputs = [];
        //     foreach ($chunk as $key => $itemArray) {

        //         $item = (array) $itemArray;

        //         $generic_info = DB::table("generic_info")->where('id', $item['p_generic_id'])->first();
        //         $product_category = DB::table("product_category")->where('id', $item['p_cat_id'])->first();
        //         $brand = DB::table("company")->where('id', $item['p_company_id'])->first();
        //         $category = Category::where('display_name', $product_category->p_category_name)->first();

        //         $inputs[] =   [
        //             'skuCode' => $item['p_code'],
        //             'productCode' => $item['p_code'],
        //             'item_name' => $item['p_name'],
        //             // 'slug' => snake_case($item['p_name']),
        //             'unit' =>  $units[$item['p_unit_id']],
        //             'category_id' => $category->id,
        //             'status' => "active",
        //             'created_by' => 1,
        //             'company_id' => 1,
        //             'description' => $generic_info->generic_name,
        //             'brand_id' => $brand->id,
        //             'member_id' => 1,
        //             'price' => $item['p_sell_price'],
        //             'purchase_price' => $item['p_buy_price'],
        //         ];

        //     }

        //     Item::insert($inputs);
        // }




        // it will chunk the dataset in smaller collections containing 500 values each.
        // Play with the value to get best result

        // $items = Item::where('id', '>', 12555)->get();


        // foreach ($items as $key => $p) {
        //     $this->stockIn($p->id, 100);
        //     $this->stock_report($p->id, 100, 'initial', date("Y-m-d"));
        // }


    }
}
