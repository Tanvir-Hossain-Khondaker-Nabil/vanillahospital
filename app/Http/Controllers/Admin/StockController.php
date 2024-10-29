<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\WarehouseStock;
use App\Http\Traits\StockTrait;
use App\Models\Item;
use App\Models\WarehouseHistory;
use App\Models\WarehouseStock as WarehouseStockModel;
use App\Models\LossStockReconcile;
use App\Models\Stock;
use App\Models\StockOverflowReconcile;
use App\Models\StockReport;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StockController extends Controller
{

    use StockTrait;

    public function stock_reconcile($type)
    {
        if (!in_array($type, ['damage', 'loss', 'expired'])) {
            return redirect()->back();
        }

        $data['warehouses'] = Warehouse::active()->pluck('title', 'id');
        $data['items'] = Item::get()->pluck('item_name', 'id');
        $data['type'] = $type;

        return view('admin.stocks.stock_reconcile', $data);

    }

    public function yearly_stock_reconcile()
    {
        $data['items'] = Item::get()->pluck('item_name', 'id');
        $data['warehouses'] = Warehouse::active()->pluck('title', 'id');

        return view('admin.stocks.update_stock_reconcile', $data);

    }

    public function damage_stock_update(Request $request)
    {

        $request['fiscal_year_id'] = Auth::user()->company->fiscal_year_id;
        $request['company_id'] = Auth::user()->company_id;

        $rules = [
            'qty' => 'required',
            'item_id' => 'required',
            'entry_date' => 'required',
        ];

        $this->validate($request, $rules);

        $endDate = db_date_format($request->entry_date);
        $warehouse_id = $request->warehouse_id;

        $inputs = $request->all();
        $item_id = $inputs['item_id'];
        $qty = $inputs['qty'];
        $inputs['enter_date'] = Carbon::today();
        $inputs['closing_date'] = $endDate;

//        dd($warehouse_id);
        LossStockReconcile::create($inputs);

        $this->stock_report($item_id, $qty, 'Lost', $endDate);
        $this->stockOut($item_id, $qty);

        if (config('settings.warehouse') && isset($request->warehouse_id)) {

            $this->store_warehouse_stock_history($request, "Damage", $endDate);

            $warehouseStock = new WarehouseStock();
            $warehouseStock->set($item_id, $warehouse_id, $qty, $endDate, 'Damage');
            $warehouseStock->stockOut();
        }


        $status = ['type' => 'success', 'message' => trans('common.stock_update_successfully')];


        return redirect()->back()->with('status', $status);
    }


    public function update_stock_reconcile(Request $request)
    {

        $request['fiscal_year_id'] = Auth::user()->company->fiscal_year_id;
        $request['company_id'] = Auth::user()->company_id;

        $rules = [
            'qty' => 'required',
            'item_id' => Rule::unique('loss_stock_reconcile')->where(function ($query) use ($request) {
                return $query->where('fiscal_year_id', $request->fiscal_year_id)
                    ->where('company_id', $request->company_id);
            }),
        ];

        $customMessages = [
            'item_id.unique' => 'The :attribute is REQUIRED AND  already exist for this fiscal year and company'
        ];

        $this->validate($request, $rules, $customMessages);

        $set_company_fiscal_year = Auth::user()->company->fiscal_year;


        $fromDate = $set_company_fiscal_year->start_date;
        $endDate = $set_company_fiscal_year->end_date;
//        $fiscal_year_end = $set_company_fiscal_year->end_date->lt(Carbon::today());


//        if($fiscal_year_end)
//        {
        $inputs = $request->all();
        $item_id = $inputs['item_id'];
        $qty = $inputs['qty'];
        $inputs['enter_date'] = Carbon::today();
        $inputs['closing_date'] = $endDate;

        LossStockReconcile::create($inputs);

        $this->stock_report($item_id, $qty, 'Lost', $endDate);
        $this->stockOut($item_id, $qty);

        $warehouse_id = $request->warehouse_id;

        if (config('settings.warehouse') && $warehouse_id > 0) {

            $this->store_warehouse_stock_history($request, "Loss", $endDate);

            $warehouseStock = new WarehouseStock();
            $warehouseStock->set($item_id, $warehouse_id, $qty, $endDate, 'Loss');
            $warehouseStock->stockOut();

        }


        $status = ['type' => 'success', 'message' => trans('common.stock_reconcile_updated_successfully')];
//        }else{
//
//            $status = ['type' => 'danger', 'message' =>'Fiscal year End Date greater than today'];
//        }


        return redirect()->back()->with('status', $status);
    }


    public function update_stock_overflow_reconcile(Request $request)
    {

        $request['fiscal_year_id'] = Auth::user()->company->fiscal_year_id;
        $request['company_id'] = Auth::user()->company_id;

        $rules = [
            'qty' => 'required',
            'item_id' => Rule::unique('stock_overflow_reconcile')->where(function ($query) use ($request) {
                return $query->where('fiscal_year_id', $request->fiscal_year_id)
                    ->where('company_id', $request->company_id);
            }),
        ];

        $customMessages = [
            'item_id.unique' => 'The :attribute is REQUIRED AND  already exist for this fiscal year and company'
        ];

        $this->validate($request, $rules, $customMessages);

        $set_company_fiscal_year = Auth::user()->company->fiscal_year;

        $fromDate = $set_company_fiscal_year->start_date;
        $endDate = $set_company_fiscal_year->end_date;
//        $fiscal_year_end = $set_company_fiscal_year->end_date->lt(Carbon::today());
//
//        if($fiscal_year_end)
//        {
        $inputs = $request->all();
        $item_id = $inputs['item_id'];
        $qty = $inputs['qty'];
        $inputs['enter_date'] = Carbon::today();
        $inputs['closing_date'] = $endDate;

        StockOverflowReconcile::create($inputs);

        $this->stock_report($item_id, $qty, 'Overflow', $endDate);
        $this->stockIn($item_id, $qty);


        $warehouse_id = $request->warehouse_id;

        if (config('settings.warehouse') && $warehouse_id > 0) {

            $this->store_warehouse_stock_history($request, "Overflow", $endDate);

            $warehouseStock = new WarehouseStock();
            $warehouseStock->set($item_id, $warehouse_id, $qty, $endDate, 'Overflow');
            $warehouseStock->stockOut();
        }


        $status = ['type' => 'success', 'message' => trans('common.stock_reconcile_updated_successfully')];
//        }else{
//
//            $status = ['type' => 'danger', 'message' =>'Fiscal year End Date greater than today'];
//        }

        return redirect()->back()->with('status', $status);
    }


    public function get_update_stock_report()
    {
        $data['items'] = Item::get()->pluck('item_name', 'id');
        $data['warehouses'] = Warehouse::active()->pluck('title', 'id');

        $item_id = WarehouseStockModel::authCompany()->pluck('item_id')->toArray();
        $data['products'] = Item::whereIn('id', $item_id)->authCompany()->pluck('item_name', 'id');

        return view('admin.stocks.update_stock_report', $data);

    }

    public function stock_report_update(Request $request)
    {

        $item_id = $request->item_id;
        $start_date = $request->start_date;


        if (isset($item_id)) {
            $last_stock = StockReport::where('item_id', $item_id)->orderBy('date', 'asc')->first();

            if (empty($start_date)) {
                $date = StockReport::orderBy('date', 'asc')->first();
                $start_date = $last_stock->date;
            } else {
                $start_date = $last_stock->date;
            }


            $this->update_stock_report($item_id, $start_date);

//            DB::select('call ChangeStockReportByItem(?,?,?)',array($start_date, $endDate, $item_id));

        } else {

            $last_stock = StockReport::orderBy('date', 'asc')->first();
            $start_date = $last_stock->date;

            $this->update_stock_report($item_id, $start_date);
//            DB::select('call UpdateStockReport(?,?,?)',array($start_date, $endDate));
        }

        $status = ['type' => 'success', 'message' => trans('common.stock_report_updated_successfully')];

        return redirect()->back()->with('status', $status);
    }


    public function warehouse_stock_report_update(Request $request)
    {

        $item_id = $request->item_id;
        $warehouse_id = $request->warehouse_id;
        $start_date = $request->start_date;


        $warehouseStock = new WarehouseStock();
        $warehouseStock->update_stock_report($item_id, $start_date, $warehouse_id);

        $status = ['type' => 'success', 'message' => trans('common.warehouse_stock_report_update_successfully')];

        return redirect()->back()->with('status', $status);
    }

    public function check_and_update_full_daily_stock()
    {

        $stock_report = StockReport::orderBy('item_id', 'asc')->orderBy('date', 'asc')->get();

        $item_id = "";
        foreach ($stock_report as $key => $value) {

            if ($value->item_id != $item_id) {

                if ($item_id > 0) {
                    $stock = Stock::where('item_id', $item_id)->first();
                    $stock->stock = $closing_qty;
                    $stock->save();
                }

                $opening_qty = $value->opening_stock;
            } else {
                $opening_qty = $closing_qty;
            }

            $stock = StockReport::find($value->id);
            $item_id = $value->item_id;


            $sale_qty = DB::table("sales")
                ->leftJoin('sales_details', 'sales_details.sale_id', 'sales.id')
                ->where('sales.date', $value->date)
                ->where('sales_details.item_id', $item_id)
                ->groupBy('sales_details.item_id')
                ->groupBy('sales.date')
                ->sum('sales_details.qty');


            $free_qty = DB::table("sales")
                ->leftJoin('sales_details', 'sales_details.sale_id', 'sales.id')
                ->where('sales.date', $value->date)
                ->where('sales_details.item_id', $item_id)
                ->groupBy('sales_details.item_id')
                ->groupBy('sales.date')
                ->sum('sales_details.free');

            $sale_qty = $free_qty + $sale_qty;

            $purchase_qty = DB::table("purchases")
                ->leftJoin('purchase_details', 'purchase_details.purchase_id', 'purchases.id')
                ->where('purchases.date', $value->date)
                ->where('purchase_details.item_id', $item_id)
                ->groupBy('purchase_details.item_id')
                ->groupBy('purchases.date')
                ->sum('purchase_details.qty');

            $sale_return_qty = DB::table("sales")
                ->leftJoin('sales_return', 'sales_return.sale_id', 'sales.id')
                ->where('sales.date', $value->date)
                ->where('sales_return.item_id', $item_id)
                ->groupBy('sales_return.item_id')
                ->groupBy('sales.date')
                ->sum('sales_return.return_qty');

            $purchase_return_qty = DB::table("purchases")
                ->leftJoin('return_purchases', 'return_purchases.purchase_id', 'purchases.id')
                ->where('purchases.date', $value->date)
                ->where('return_purchases.item_id', $item_id)
                ->groupBy('return_purchases.item_id')
                ->groupBy('purchases.date')
                ->sum('return_purchases.return_qty');

            $stock->opening_stock = $opening_qty;
            $stock->sale_qty = $sale_qty;
            $stock->purchase_qty = $purchase_qty;
            $stock->sale_return_qty = $sale_return_qty;
            $stock->purchase_return_qty = $purchase_return_qty;

            $closing_qty = $opening_qty + $purchase_qty - $sale_qty + $sale_return_qty - $purchase_return_qty - $stock->loss_qty + $stock->stock_overflow_qty;
            $closing_qty = create_float_format($closing_qty);

            $stock->closing_qty = $closing_qty;

            $stock->update();

            if (count($stock_report) - 1 == $key) {
                $stock = Stock::where('item_id', $item_id)->first();
                $stock->stock = $closing_qty;
                $stock->save();
            }

        }

        $status = ['type' => 'success', 'message' => trans('common.stock_report_updated_successfully')];

        return redirect()->back()->with('status', $status);
    }


    public function daily_stock_delete($id)
    {

        $modal = StockReport::where('id', $id)->first();

        if ($modal) {
            $modal->delete();

            return response()->json([
                'data' => [
                    'message' => trans('common.successfully_deleted')
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'message' => trans('common.unable_to_delete')
                ]
            ], 400);
        }

    }


    private function store_warehouse_stock_history($request, $type, $endDate)
    {
        $code = date("Ymdhis");

        $product = Item::findOrFail($request->item_id);

        $inputs = [];
        $inputs['code'] = $code;
        $inputs['company_id'] = $product->company_id;
        $inputs['warehouse_id'] = $request->warehouse_id;
        $inputs['model'] = $type;
        $inputs['qty'] = $request->qty;
        $inputs['model_id'] = $request->warehouse_id;
        $inputs['item_id'] = $request->item_id;
        $inputs['date'] = $endDate;

        WarehouseHistory::create($inputs);
    }
}
