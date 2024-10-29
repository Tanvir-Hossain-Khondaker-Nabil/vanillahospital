<?php

namespace App\Http\Controllers\member;

use App\DataTables\QuotationsDataTable;
use App\Http\Traits\CompanyInfoTrait;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\QuotationCompany;
use App\Models\QuotationTerm;
use App\Models\QuotationTransaction;
use App\Models\QuoteAttention;
use App\Models\QuoteTerm;
use App\Models\Quoting;
use App\Models\Transactions;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Milon\Barcode\DNS1D;
use Barryvdh\DomPDF\Facade as PDF;

class QuotationController extends Controller
{
    use CompanyInfoTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(QuotationsDataTable $dataTable)
    {
        return $dataTable->render('member.quotation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countQuote = Quotation::count() + 1;
        $data['quote_companies'] = QuotationCompany::quotationType()->active()->get()->pluck('info_details', 'id');
        $data['quotation_terms'] = QuotationTerm::parent()->active()->get();
        $data['quotation_sub_terms'] = QuotationTerm::subTerm()->active()->get()->pluck('name', 'id');
        $data['quote_attentions'] = QuoteAttention::active()->get()->pluck('info_details', 'id');
        $data['quoting'] = Quoting::active()->get()->pluck('info_details', 'id');
        $data['products'] = Item::authCompany()->latest()->get();

        $data['message_text'] = "In response to your inquiry, we are pleased to submit our Best Price Offer for supplying our globally renowned industrial products as per following technical specification & terms:";
        $data['message_end'] = "We hope, you will find our offer as per your requirement and expecting your positive feedback in this regard.";


        $quote_ref = Auth::user()->company->quote_ref;
        if(!$quote_ref)
        {
            $quote_ref  = get_quote_ref(Auth::user()->company->company_name);
            Auth::user()->company->update(['quote_ref' => $quote_ref]);
        }

        $data['subject'] = "Quotation for ";
        $data['ref'] = $quote_ref."/COMPANY-NAME/" . date("Y") . "/" . sprintf("%04d", $countQuote);

        $data['categories'] = Category::all()->pluck('display_name', 'id');
        $data['units'] = Unit::all()->pluck('name', 'name');
        $data['brands'] = Brand::all()->pluck('name', 'id');
        $data['product_code'] = sprintf("%04d", (Item::count() + 1));

        $set_company_fiscal_year = Auth::user()->company->fiscal_year->first();
        $fromDate = $set_company_fiscal_year->start_date;

        $data['initial_date'] = $fromDate;

        return view('member.quotation.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules(), $this->customMessages());

        $inputs = $request->all();

        $countQuote = Quotation::count() + 1;

        $quotation = [];
        $quotation['quote_date'] = $requisitionDate = db_date_format($inputs['date']);
        $quotation['quote_attention_id'] = $inputs['quote_attention_id'];
        $quotation['quotationer_id'] = $quotationer_id = $inputs['quotationer_id'];
        $quotation['contact_quoting_id'] = $inputs['contact_quoting_id'];
        $quotation['quoting_id'] = $inputs['quoting_id'];
        $quotation['starting_text'] = $inputs['starting_text'];
        $quotation['ending_text'] = $inputs['ending_text'];
        $quotation['subject'] = $inputs['subject'];
        $quotation['discount'] = $inputs['discount'];
        $quotation['grand_total'] = $inputs['grand_total'];
        $quotation['company_id'] = $company_id = Auth::user()->company_id;

        $quote_ref = Auth::user()->company->quote_ref;

//        "BAZ/COR/SALES/"

        $quotationCompany = QuotationCompany::find($quotationer_id);
        $ref = $quote_ref."/". strtoupper(removeSpecialChar($quotationCompany->company_name)) . "/" . date("Y") . "/" . sprintf("%04d", $countQuote);

        $quotation['ref'] = $ref;


        DB::beginTransaction();
        try {

            $quotationId = Quotation::create($quotation);

            $quotationDetails = [];
            $quotationDetails['quotation_id'] = $quotationId->id;
            $quotationDetails['quoting_date'] = $requisitionDate;
            $quotationDetails['company_id'] = $company_id;


            $product = $request->product_id;
            $qty = $request->qty;
            $price = $request->price;
            $total_price = $request->total_price;
            $description = $request->description;

            $total_amount = 0;
            for ($i = 0; $i < count($product); $i++) {

                if (!isset($product[$i]) || !isset($qty[$i]))
                    break;


                if ($qty[$i] < 1 || $total_price[$i] < 1) {
                    break;
                }

                $item = Item::find($product[$i]);
                $quotationDetails['item_id'] = $item_id = $product[$i];
                $quotationDetails['unit'] = $item->unit;
                $quotationDetails['qty'] = $quantity = $qty[$i];
                $quotationDetails['price'] = $price[$i];
                $quotationDetails['total_price'] = $qty[$i] * $price[$i];
                $quotationDetails['description'] = $description[$i];

                $total_amount += $qty[$i] * $price[$i];

                QuotationDetail::create($quotationDetails);

            }

            $quotationId->grand_total = $total_amount;
            $quotationId->save();

            $quote_sub_terms = $request->quote_sub_term ?? [];
            $quote_terms = $request->quote_term ?? [];

            foreach ($quote_sub_terms as $value) {
                $data = [];
                $data['quotation_id'] = $quotationId->id;
                $data['quote_term_id'] = $value;
                QuoteTerm::create($data);
            }

            foreach ($quote_terms as $value) {

                $data = [];
                $data['quotation_id'] = $quotationId->id;
                $data['quote_term_id'] = $value;
                QuoteTerm::create($data);
            }


            $status = ['type' => 'success', 'message' => 'Quotation done Successfully'];


        } catch (\Exception $e) {

            $status = ['type' => 'danger', 'message' => 'Unable to save'];
            DB::rollBack();
        }

        DB::commit();

        if ($status['type'] == 'success') {
            return redirect()->route('member.quotations.show', $quotationId->id)->with('status', $status);
        } else {

            return redirect()->back()->with('status', $status);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data['quotation'] = $quotation = Quotation::findorFail($id);
        $data['quotation_terms'] = QuotationTerm::parent()->get();
        $data['quote_terms'] = QuoteTerm::where('quotation_id', $id)->pluck('quote_term_id')->toArray();


        $d = new DNS1D();
        $d->setStorPath(__DIR__ . "/cache/");
        $barcode = $quotation->id . Str::random(5);
        $data['barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 2, 50) . '" alt="' . $quotation->id . '"   />';
        $data = $this->company($data);

        return view('member.quotation.show', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function print_quotation(Request $request, $id)
    {

        $data['quotation'] = $quotation = Quotation::findorFail($id);
        $data['quotation_terms'] = QuotationTerm::parent()->get();
        $data['quote_terms'] = QuoteTerm::where('quotation_id', $id)->pluck('quote_term_id')->toArray();

        $data['hasImages'] = Quotation::where('id', $id)
            ->whereHas('quotation_details', function (Builder $query) {
                $query->whereHas('item', function (Builder $query1) {
                    return $query1->whereNotNull('product_image');
                });
            })->count();

        $d = new DNS1D();
        $d->setStorPath(__DIR__ . "/cache/");
        $barcode = $quotation->ref;
        $data['barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 2, 50) . '" alt="' . $quotation->ref . '"   />';
        $data = $this->company($data);
        $data['asset_url'] = url('/');
        $data['based'] = $request->based;


        $data['report_title'] = $title = "Quotation Invoice";

        if ($request->based == "print") {
            return view('member.quotation.print_quote', $data);
        } else {

            $pdf = PDF::loadView('member.quotation.print_quote', $data);
            $file_name = $quotation->ref;

            return $pdf->download($file_name . ".pdf");
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['modal'] = $quotation = Quotation::findOrFail($id);
        $data['quote_terms'] = QuoteTerm::where('quotation_id', $id)->pluck('quote_term_id')->toArray();
        $data['quote_companies'] = QuotationCompany::quotationType()->active()->get()->pluck('info_details', 'id');
        $data['quotation_terms'] = QuotationTerm::parent()->active()->get();
        $data['quotation_sub_terms'] = QuotationTerm::subTerm()->active()->get()->pluck('name', 'id');
        $data['quote_attentions'] = QuoteAttention::active()->get()->pluck('info_details', 'id');
        $data['quoting'] = Quoting::active()->get()->pluck('info_details', 'id');
        $data['products'] = Item::authCompany()->latest()->pluck('item_name', 'id');


        $data['products'] = Item::authCompany()->latest()->get();
        $data['quotation_products'] = Item::whereNotIn('id', $quotation->quotation_details()->pluck('item_id')->toArray())->latest()->pluck('item_name', 'id');


        $data['categories'] = Category::all()->pluck('display_name', 'id');
        $data['units'] = Unit::all()->pluck('name', 'name');
        $data['brands'] = Brand::all()->pluck('name', 'id');
        $data['product_code'] = sprintf("%04d", (Item::count() + 1));

        $set_company_fiscal_year = Auth::user()->company->fiscal_year->first();
        $fromDate = $set_company_fiscal_year->start_date;

        $data['initial_date'] = $fromDate;

        return view('member.quotation.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $quotationId = Quotation::find($id);

        $inputs = $request->all();

        $quotation = [];
        $quotation['quote_date'] = $requisitionDate = db_date_format($inputs['date']);
        $quotation['quote_attention_id'] = $inputs['quote_attention_id'];
        $quotation['quotationer_id'] = $quotationer_id = $inputs['quotationer_id'];
        $quotation['contact_quoting_id'] = $inputs['contact_quoting_id'];
        $quotation['quoting_id'] = $inputs['quoting_id'];
        $quotation['starting_text'] = $inputs['starting_text'];
        $quotation['ending_text'] = $inputs['ending_text'];
        $quotation['subject'] = $inputs['subject'];
        $quotation['discount'] = $inputs['discount'];
        $quotation['grand_total'] = $inputs['grand_total'];
        $quotation['company_id'] = $company_id = Auth::user()->company_id;


        DB::beginTransaction();
        try {

            $quotationId->update($quotation);


            $product = $request->product_id;
            $qty = $request->qty;
            $price = $request->price;
            $description = $request->description;

            $quotation_details_id = $request->quotationDetails;


            $deleteQuotationDetail = QuotationDetail::where('quotation_id', $id)->whereNotIn('id', $quotation_details_id)->get();


            foreach ($deleteQuotationDetail as $value) {
                $delete_quotation_details_id = QuotationDetail::find($value->id);
                $delete_quotation_details_id->delete();
            }

            $quotationDetails = [];
            $quotationDetails['quotation_id'] = $id;
            $quotationDetails['quoting_date'] = $requisitionDate;
            $quotationDetails['company_id'] = $company_id;


            $total_amount = 0;
            for ($i = 0; $i < count($product); $i++) {
                $item = Item::find($product[$i]);

                if ($quotation_details_id[$i] != "new") {
                    $quotations_details_id = QuotationDetail::find($quotation_details_id[$i]);
                } else {
                    $quotationDetails['quotation_id'] = $quotationId->id;
                }

                $quotationDetails['item_id'] = $item_id = $product[$i];
                $quotationDetails['unit'] = $item->unit;
                $quotationDetails['qty'] = $quantity = $qty[$i];
                $quotationDetails['price'] = $price[$i];
                $quotationDetails['description'] = $description[$i];
                $quotationDetails['total_price'] = $total_price = $qty[$i] * $price[$i];

                $total_amount += $total_price;

                if ($quotation_details_id[$i] != "new") {
                    $quotations_details_id->update($quotationDetails);
                } else {
                    QuotationDetail::create($quotationDetails);
                }
            }

            $quotationId->grand_total = $total_amount;
            $quotationId->save();

            $quote_sub_terms = $request->quote_sub_term ?? [];
            $quote_terms = $request->quote_term ?? [];

            QuoteTerm::where('quotation_id', $quotationId->id)->delete();

            if ($quote_sub_terms) {

                foreach ($quote_sub_terms as $value) {
                    $data = [];
                    $data['quotation_id'] = $quotationId->id;
                    $data['quote_term_id'] = $value;
                    QuoteTerm::create($data);
                }
            }

            if ($quote_terms) {
                foreach ($quote_terms as $value) {

                    $data = [];
                    $data['quotation_id'] = $quotationId->id;
                    $data['quote_term_id'] = $value;
                    QuoteTerm::create($data);
                }
            }


            $status = ['type' => 'success', 'message' => 'Quotation update Successfully'];


        } catch (\Exception $e) {

            $status = ['type' => 'danger', 'message' => 'Unable to update'];
            DB::rollBack();
        }

        DB::commit();

        if ($status['type'] == 'success') {
            return redirect()->route('member.quotations.show', $quotationId->id)->with('status', $status);
        } else {
            return redirect()->back()->with('status', $status);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);

        if (!$quotation) {
            return response()->json([
                'data' => [
                    'message' => 'Unable to delete'
                ]
            ], 400);
        }


        $quotation->quotation_details()->delete();
        $quotation->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyTransactionAttach($id)
    {
        $quotation = QuotationTransaction::findOrFail($id);

        if (!$quotation) {
            return response()->json([
                'data' => [
                    'message' => 'Unable to delete'
                ]
            ], 400);
        }

        $quotation->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);

    }


    public function addQuotationOthersTransaction()
    {
        $data['quotations'] = Quotation::get()->pluck('ref', 'id');

        return view('member.quotation.add_others_transaction', $data);
    }

    public function saveQuotationOthersTransaction(Request $request)
    {
        $quotation_id = $request->quotation_id;
        $transaction_code = $request->transaction_code;

        $quotation = Quotation::find($quotation_id);
        $transaction = Transactions::where('transaction_code', $transaction_code)->first();

        if ($transaction) {

            $quotationTrans = new QuotationTransaction();
            $quotationTrans->quotation_id = $quotation_id;
            $quotationTrans->transaction_id = $transaction->id;
            $quotationTrans->note = $request->note;
            $quotationTrans->transaction_type = $request->transaction_type;
            $quotationTrans->created_by = Auth::user()->id;
            $quotationTrans->save();


//            $quotation->others_transaction = $quotation->others_transaction != "" ? $quotation->others_transaction.",".$transaction->id : $transaction->id;
//            $quotation->save();


            $status = ['type' => 'success', 'message' => 'Quotation Transaction Set Successfully'];
        } else {

            $status = ['type' => 'danger', 'message' => 'Transaction Not Found'];
        }


        return redirect()->back()->with('status', $status);

    }

    public function profit_quotation($id)
    {

        $data['quotation'] = $quotation = Quotation::findorFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__ . "/cache/");
        $barcode = $quotation->ref;
        $data['barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 2, 50) . '" alt="' . $quotation->ref . '"   />';
        $data = $this->company($data);
        $data['asset_url'] = url('/');

        $data['report_title'] = $title = "Quotation Invoice";


        return view('member.quotation.profit_quote', $data);

    }



    private function validationRules()
    {
        $validator = [
            "ref" => "required",
            "subject" => "required",
            "starting_text" => "required",
            "contact_quoting_id" => "required|numeric",
            "quoting_id" => "required|numeric",
            "total_bill" => "required|numeric",
            "grand_total" => "required|numeric",
            "quotationer_id" => "required",
            "date"          => "required|date_format:m/d/Y",
            "product_id"    => "required|array|min:1",
            "product_id.*"  => "required|numeric|min:1",
            "qty"    => "required|array",
            "qty.*"  => "nullable|numeric",
            "price"    => "required|array",
            "price.*"  => "nullable|numeric",
        ];

        return $validator;
    }


    private function customMessages(){

        $customMessages = [
            'contact_quoting_id.required' => 'Contact Person is required',
            'quoting_id.required' => 'Quote By is required',
            'quotationer_id.required' => 'Quote Company is required'
        ];

        return $customMessages;
    }

}
