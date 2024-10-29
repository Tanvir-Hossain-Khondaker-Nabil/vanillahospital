<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShareHolder;
use App\Models\OutDoorRegistration;
use App\Http\Traits\CompanyInfoTrait;

class ShareHolderController extends Controller
{
    use CompanyInfoTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['datas'] = ShareHolder::get();


        // dd($data);
        return view('member.share_holder.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.share_holder.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $this->validate($request, $this->validationRules());

        $user_id = \Auth::user()->id;
        $company_id = \Auth::user()->company_id;

        $inputs['name'] = $request->name;
        $inputs['father_name'] = $request->father_name;
        $inputs['mother_name'] = $request->mother_name;
        $inputs['nominee'] = $request->nominee;
        $inputs['phone'] = $request->phone;
        $inputs['email'] = $request->email;
        $inputs['address'] = $request->address;
        $inputs['passport_number'] = $request->passport_number;
        $inputs['nid_number'] = $request->nid_number;
        $inputs['type'] = $request->type;
        $inputs['share_number'] = $request->share_number?? 1;
        $inputs['created_by'] = $user_id;
        $inputs['company_id'] = $company_id;



        if($request->file('image')){
            $file = $request->file('image');
            $filename = time() . '.' . $request->file('image')->extension();
            $filePath = public_path() . '/uploads/share_holder/';
            $file->move($filePath, $filename);
            $inputs['image'] = $filename;
        }

        if($request->file('share_signature_image')){
            $file = $request->file('share_signature_image');
            $filename = time() . '.' . $request->file('share_signature_image')->extension();
            $filePath = public_path() . '/uploads/share_signature_image/';
            $file->move($filePath, $filename);
            $inputs['signature_image'] = $filename;
        }

        $status_data = ShareHolder::create($inputs);

        if($status_data){
            $status = ['type' => 'success', 'message' => 'Successfully Added'];
            return back()->with('status', $status);
        }else{
            $status = ['type' => 'error', 'message' => 'Something Went Wrong'];
            return back()->with('status', $status);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        $data['model'] = $model = ShareHolder::authCompany()->where('id',$id)->first();

        return view('member.share_holder.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = ShareHolder::authCompany()->where('id',$id)->first();

        $this->validate($request, $this->validationRules());

        $user_id = \Auth::user()->id;


        $inputs['name'] = $request->name;
        $inputs['father_name'] = $request->father_name;
        $inputs['mother_name'] = $request->mother_name;
        $inputs['nominee'] = $request->nominee;
        $inputs['phone'] = $request->phone;
        $inputs['email'] = $request->email;
        $inputs['address'] = $request->address;
        $inputs['passport_number'] = $request->passport_number;
        $inputs['nid_number'] = $request->nid_number;
        $inputs['type'] = $request->type;
        $inputs['share_number'] = $request->share_number?? 1;
        $inputs['updated_by'] = $user_id;
        $inputs['image'] = $data->image;
        $inputs['signature_image'] = $data->signature_image;



        if($request->file('image')){
            $file = $request->file('image');
            $filename = time() . '.' . $request->file('image')->extension();
            $filePath = public_path() . '/uploads/share_holder/';
            $file->move($filePath, $filename);
            $inputs['image'] = $filename;
        }

        if($request->file('share_signature_image')){
            $file = $request->file('share_signature_image');
            $filename = time() . '.' . $request->file('share_signature_image')->extension();
            $filePath = public_path() . '/uploads/share_signature_image/';
            $file->move($filePath, $filename);
            $inputs['signature_image'] = $filename;
        }

        $data->update($inputs);

        $status = ['type' => 'success', 'message' => 'Successfully Updated'];
        return back()->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modal = ShareHolder::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    public function validationRules($id = '')
    {
        $rules = [
            'name' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'phone' => 'required',
            'nominee' => 'required',
            'email' => 'required',
        ];


        return $rules;
    }

    public function fetchSharHolder(Request $request)
    {
        // dd($request->all());

        $data= ShareHolder::authCompany()->where('type',$request->val)->get();

        return response()->json([
        'data'=>$data,
        ]);
    }

    public function opdShareHolderReport()
    {
        // dd('asss');
        $data['share_holder']= ShareHolder::authCompany()->where('type',0)->pluck('name','id')->toArray();
        // dd($data);
        return view('member.opd_discount.share_holder',$data);
    }

    public function opdShareHolderReportSearch(Request $request)
    {

        $total_discount = 0;
        $discount = 0;
        $data['from_date'] = $from_date=  $request->from_date;
        $data['to_date'] = $to_date = $request->to_date;
        $share_holder_id = $request->share_holder_id;
        $holder_data = [];

      if($share_holder_id == 'all'){
          $test_group = ShareHolder::authCompany()->where('type',0)->pluck('name','id')->toArray();

          foreach($test_group as $key=> $t_group){

              $total_discount = 0;
               $holder_data_is[$t_group] = $test_data =  OutDoorRegistration::authCompany()->with(['outdoorPatientTest','shareHolder'])
                ->where('share_holder_id',$key)
               ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
               ->get();

               foreach($test_data as $t_data){


                foreach($t_data->outdoorPatientTest as $per_data){
                //   dd($per_data);
                $total_discount =  $total_discount + $per_data->discount;
                }

                }

                 $discount = $discount + $total_discount;

           }

          $data['discount'] =  $discount;
          $holder_data['data_is'] =  $holder_data_is;

          $data = $this->company($data);


           return view('member.opd_discount.all_share_holder_report',$data,$holder_data);

      }
      else{

          $data['datas'] = $test_data =  OutDoorRegistration::authCompany()->with(['outdoorPatientTest','shareHolder'])->where('share_holder_id',$share_holder_id)
          ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
          ->get();

        //   dd($data['datas']);
              $data = $this->company($data);

             $data['share_holder'] = ShareHolder::authCompany()->where('id',$share_holder_id)->select('name')->first();

              foreach($test_data as $t_data){


              foreach($t_data->outdoorPatientTest as $per_data){

              $total_discount =  $total_discount + $per_data->discount;
              }

              }

              $data['total_discount'] = $total_discount;

            //   dd(   $data['datas']);
              return view('member.opd_discount.share_holder_report',$data);
      }

    }

    public function opdTestDiscountManagementReport()
    {
        // dd('asss');
        $data['managements']= ShareHolder::authCompany()->where('type',1)->pluck('name','id')->toArray();
        // dd($data);
        return view('member.opd_discount.management',$data);
    }


    public function opdTestDiscountManagementSearch(Request $request)
    {

        $total_discount = 0;
        $discount = 0;
        $data['from_date'] = $from_date=  $request->from_date;
        $data['to_date'] = $to_date = $request->to_date;
        $share_holder_id = $request->share_holder_id;
        $holder_data = [];

      if($share_holder_id == 'all'){
          $test_group = ShareHolder::authCompany()->where('type',1)->pluck('name','id')->toArray();

          foreach($test_group as $key=> $t_group){

              $total_discount = 0;
               $holder_data_is[$t_group] = $test_data =  OutDoorRegistration::authCompany()->with(['outdoorPatientTest','shareHolder'])
                ->where('share_holder_id',$key)
               ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
               ->get();

               foreach($test_data as $t_data){


                foreach($t_data->outdoorPatientTest as $per_data){
                //   dd($per_data);
                $total_discount =  $total_discount + $per_data->discount;
                }

                }

                 $discount = $discount + $total_discount;

           }

          $data['discount'] =  $discount;
          $holder_data['data_is'] =  $holder_data_is;

          $data = $this->company($data);


           return view('member.opd_discount.all_management_report',$data,$holder_data);

      }
      else{

          $data['datas'] = $test_data =  OutDoorRegistration::authCompany()->with(['outdoorPatientTest','shareHolder'])->where('share_holder_id',$share_holder_id)
          ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
          ->get();

        //   dd($data['datas']);
              $data = $this->company($data);

             $data['share_holder'] = ShareHolder::authCompany()->where('id',$share_holder_id)->select('name')->first();

              foreach($test_data as $t_data){


              foreach($t_data->outdoorPatientTest as $per_data){

              $total_discount =  $total_discount + $per_data->discount;
              }

              }

              $data['total_discount'] = $total_discount;

            //   dd(   $data['datas']);
              return view('member.opd_discount.management_report',$data);
      }
    }

    public function opdTestDiscountOtherReport()
    {


        return view('member.opd_discount.others');
    }

    public function opdTestDiscountOtherSearch(Request $request)
    {

        // dd('kk');
        $total_discount = 0;
        $discount = 0;
        $data['from_date'] = $from_date=  $request->from_date;
        $data['to_date'] = $to_date = $request->to_date;
        $share_holder_type = $request->share_holder_type;
        $holder_data = [];

      if($share_holder_type == 'all'){

          $test_group= OutDoorRegistration::authCompany()->where('share_holder_type',2)->pluck('discount_ref','id')->toArray();
        // dd($data);
        //   $test_group = ShareHolder::authCompany()->where('type',1)->pluck('name','id')->toArray();

          foreach($test_group as $key=> $t_group){

              $total_discount = 0;
               $holder_data_is[$t_group] = $test_data =  OutDoorRegistration::authCompany()->with(['outdoorPatientTest','shareHolder'])
                ->where('id',$key)
               ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
               ->get();

               foreach($test_data as $t_data){


                foreach($t_data->outdoorPatientTest as $per_data){
                //   dd($per_data);
                $total_discount =  $total_discount + $per_data->discount;
                }

                }

                 $discount = $discount + $total_discount;

           }

          $data['discount'] =  $discount;
          $holder_data['data_is'] =  $holder_data_is;

          $data = $this->company($data);


           return view('member.opd_discount.all_others_report',$data,$holder_data);

      }

    }

}