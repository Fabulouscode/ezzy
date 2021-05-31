<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\VoucherCodeRequest;
use App\Repositories\VoucherCodeRepository;
use Carbon\Carbon as Carbon;

class VoucherCodeController extends Controller
{
     private $voucher_code_repo;

    public function __construct(VoucherCodeRepository $voucher_code_repo)
    {
        $this->voucher_code_repo = $voucher_code_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->voucher_code_repo->getDatatable($request);
        }
        return view('admin.voucher_code.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status = $this->voucher_code_repo->getStatusValue();
        $voucher_type = $this->voucher_code_repo->getVoucherTypeValue();
        $voucher_used = $this->voucher_code_repo->getVoucherUsedValue();
        return view('admin.voucher_code.add',compact('voucher_type','status','voucher_used'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherCodeRequest $request)
    {
        $data = [
                    'voucher_name' => $request->voucher_name,
                    'voucher_code' => $request->voucher_code,
                    'description' => $request->description,
                    'quantity' => $request->quantity,
                    'expiry_date' => $request->expiry_date,
                    'percentage' => $request->percentage,
                    'fix_amount' => $request->fix_amount,
                    'min_amount' => $request->min_amount,
                    'voucher_type' => $request->voucher_type,
                    'voucher_used' => $request->voucher_used,
                    'status' => $request->status
                ];

        if(!empty($request->id)){
            $voucher = $this->voucher_code_repo->getById($request->id);
            if(!empty($voucher)){
                $this->voucher_code_repo->dataCrud($data, $request->id);
            } 
        } else{
            $this->voucher_code_repo->dataCrud($data);
        }

        return redirect('/voucher_code');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = $this->voucher_code_repo->getStatusValue();
        $voucher_type = $this->voucher_code_repo->getVoucherTypeValue();
        $voucher_used = $this->voucher_code_repo->getVoucherUsedValue();
        $data = $this->voucher_code_repo->getById($id);
        $data->expiry_date = Carbon::parse($data->expiry_date)->format('Y-m-d\TH:i');
        return view('admin.voucher_code.add',compact('data','status','voucher_type','voucher_used'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->voucher_code_repo->getById($id);
        try{
            if(!empty($data)){
                $this->voucher_code_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this voucher'], 500);
        }  
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
