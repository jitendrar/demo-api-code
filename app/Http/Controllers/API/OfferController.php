<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OfferResource;
use App\OfferMaster;
use App\OfferDetail;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();
        $offers = OfferMaster::with('offerDetail.product')->where('status',OfferMaster::$STATUS_ACTIVE)->get();
        if($offers->count()) {
            $status         = 1;
            $StatusCode     = 200;
            $msg            = __('words.retrieved_successfully');
            $data           = OfferResource::collection($offers);
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
