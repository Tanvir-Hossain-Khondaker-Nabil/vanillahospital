<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\QuotationCompany;
use App\Models\{Client, Division, District, Area, Label, Labeling,Country};
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\FileUploadTrait;
use App\DataTables\ClientDataTable;

class ClientController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClientDataTable $dataTable)
    {
        return $dataTable->render('member.client.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['countries'] = Country::get()->pluck('countryName', 'id');
        $data['divisions'] = [];
        $data['districts'] = [];
        $data['areas'] = [];
        $data['companies'] = QuotationCompany::where('status', 1)->projectType()->pluck('company_name', 'id');
        $data['label'] = Label::where('label_type', 'client')->pluck('name', 'id');

        return view('member.client.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();


        $this->validate($request, $this->validationRules(), $this->customMessages());

        $inputs = $request->all();

        $inputs['company_id'] = \Auth::user()->company_id;
        $inputs['created_by'] = \Auth::user()->id;

        if ($request->hasFile('client_image')) {
            $id_photo = $request->file('client_image');

            $upload1 = $this->fileUpload($id_photo, '/client_image/', null, ['jpg', 'jpeg','png']);

            if (!$upload1) {
                $status = ['type' => 'danger', 'message' => 'Client Image Must be JPG'];
                if($request->ajax()){
                    return response()->json([
                        'message' => 'Client Image Must be JPG,JPEG,PNG',
                        'status' => 400,

                    ]);
                }
                return back()->with('status', $status);
            }
            $inputs['client_image'] = $upload1;
        //   dd($inputs);
        }

        if ($request->hasFile('card_image')) {
            $id_photo = $request->file('card_image');

            $upload2 = $this->fileUpload($id_photo, '/card_image/', null, ['jpg', 'jpeg','png']);

            if (!$upload2) {
                $status = ['type' => 'danger', 'message' => 'Card Image Must be JPG'];
                if($request->ajax()){
                    return response()->json([
                        'message' => 'Card Image Must be JPG,JPEG,PNG',
                        'status' => 400,

                    ]);
                }
                return back()->with('status', $status);
            }
            $inputs['card_image'] = $upload2;

        }

        $client = Client::create($inputs);
        // return 'kkkk';
        $labelings = $request->label_id ?? [];

        if ($client) {
        //    dd($inputs);
            foreach ($labelings as $id) {

                $label = Label::findOrFail($id);
                $labelingInputs['label_id'] = $label->id;
                $labelingInputs['modal_id'] = $client->id;
                $labelingInputs['modal'] = 'Client';
                $labelingInputs['created_by'] = \Auth::user()->id;
                $labelingInputs['company_id'] = \Auth::user()->company_id;

                Labeling::create($labelingInputs);
            }

        }
        $data['client_id'] = $client->id;
        $status = ['type' => 'success', 'message' => 'Successfully Added.'];

        if($request->ajax()){
            $allClient = Client::all()->pluck('name', 'id');
            return response()->json([
                'message' => 'Added Client Successful',
                'client_id' => $client->id,
                'client' => $allClient,
                'status' => 200,

            ]);
        }else{
            return back()->with('status', $status);

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
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] =$client= Client::findOrFail($id);
        $data['divisions'] = Division::active()->get()->pluck('name', 'id');
        $data['districts'] = District::active()->get()->pluck('name', 'id');
        $data['areas'] = Area::active()->get()->pluck('name', 'id');
        $data['companies'] = QuotationCompany::projectType()->get()->pluck('company_name', 'id');
        $data['label'] = Label::where('label_type', 'Client')->pluck('name', 'id');
        $data['label'] = Label::where('label_type', 'client')->pluck('name', 'id');
        $data['labeling'] = Labeling::where('modal', 'Client')->where('modal_id', $id)
                                    ->pluck('label_id')->toArray();
        $data['countries'] = Country::all()->pluck('countryName', 'id');

        $data['divisions'] = Division::active()->get()->pluck('name', 'id');
        $data['districts'] = District::active()->get()->pluck('name', 'id');
        $data['areas'] = Area::active()->get()->pluck('name', 'id');

        return view('member.client.edit', $data);
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

        $this->validate($request, $this->validationRules(), $this->customMessages());

        $client = Client::findOrFail($id);

        $inputs = $request->all();
        $inputs['updated_by'] = \Auth::user()->id;

        if ($request->hasFile('client_image')) {
            $id_photo = $request->file('client_image');

            $upload1 = $this->fileUpload($id_photo, '/client_image/', null);

            if (!$upload1) {
                $status = ['type' => 'danger', 'message' => 'Client Image Must be JPG'];
                return back()->with('status', $status);
            }
            $inputs['client_image'] = $upload1;

        } else {
            $inputs['client_image'] = $client->client_image;
        }

        if ($request->hasFile('card_image')) {
            $id_photo = $request->file('card_image');

            $upload2 = $this->fileUpload($id_photo, '/card_image/', null);

            if (!$upload2) {
                $status = ['type' => 'danger', 'message' => 'Card Image Must be JPG'];
                return back()->with('status', $status);
            }
            $inputs['card_image'] = $upload2;

        } else {
            $inputs['card_image'] = $client->card_image;
        }

        $client->update($inputs);


        $labelings = $request->label_id ?? [];

        if ($client) {

            Labeling::where('modal', 'Client')->where('modal_id', $client->id)->delete();

            foreach ($labelings as $id) {

                $label = Label::findOrFail($id);

                $labelingInputs['label_id'] = $label->id;
                $labelingInputs['modal_id'] = $client->id;
                $labelingInputs['modal'] = 'Client';
                $labelingInputs['created_by'] = \Auth::user()->id;
                $labelingInputs['company_id'] = \Auth::user()->company_id;

                Labeling::create($labelingInputs);
            }

        }


        $status = ['type' => 'success', 'message' => 'Successfully Updated.'];

        return back()->with('status', $status);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully Deleted',
            ]
        ], 200);
    }

    public function validationRules($id=''){

//        if($id==''){
//            $rules = [
//                'name' => 'required|unique:leads,name',
//                'lead_category_id' => 'required',
//            ];
//        }else{
        $rules = [
            'name' => 'required',
            'quotationer_id' => 'required',
            'address_1' => 'required',
            'country_id' => 'required',
            'division_id' => 'required',
            'phone_1' => 'required|min:8|max:11'
        ];
//        }

        return $rules;
    }

    private function customMessages(){

        $customMessages = [
            'quotationer_id.required' => 'Company Name is required',
            'address_1.required' => 'Address One is required',
            'division_id.required' => 'State is required',
            'phone_1.required' => 'Phone Number One is required',
        ];

        return $customMessages;
    }
}