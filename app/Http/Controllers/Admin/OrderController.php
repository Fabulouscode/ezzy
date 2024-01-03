<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Repositories\ShopMedicineDetailsRepository;
use App\Repositories\UserTransactionRepository;

class OrderController extends Controller
{
    
    private $order_repo, $shop_medicine_repo, $user_transaction_repo;

    public function __construct(OrderRepository $order_repo, ShopMedicineDetailsRepository $shop_medicine_repo, UserTransactionRepository $user_transaction_repo)
    {
        $this->order_repo = $order_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->user_transaction_repo = $user_transaction_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        if($request->all()){
            return $this->order_repo->getDatatable($request);
        }
        $statuses = $this->order_repo->getStatusValue();
        return view('admin.order.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.order.view');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect('/donotezzycaretouch/pharmacy/orders');
    }

      /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $currency_symbol = $this->order_repo->currency_symbol;
        $data = $this->order_repo->getbyEditId($id);
        // dd($data);
        return view('admin.order.view',compact('data','currency_symbol'));
    }

      /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getInvoice($id)
    {
        $currency_symbol = $this->order_repo->currency_symbol;
        $data = $this->order_repo->getbyEditId($id);
        // dd($data);
        return view('admin.order.invoice',compact('data','currency_symbol'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->order_repo->getById($id);
        return view('admin.order.view',compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->order_repo->getById($id);
        try{
            if(!empty($data)){
                $this->order_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this order'], 500);
        }  
        return response()->json(['msg'=>'Data Not success'], 500);
    }

    public function getOrderReviews(Request $request)
    {
        if ($request->all()) {
            return $this->order_repo->getReviewDatatable($request);
        }
        return view('admin.order.reviews');
    }
}
