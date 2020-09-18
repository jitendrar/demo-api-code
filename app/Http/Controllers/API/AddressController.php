<?php

namespace App\Http\Controllers\API;

use App\Address;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AddressResource;
use Validator;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();
        $user_id = $request->get('user_id');
        if(!empty($user_id)) {
            $PAGINATION_VALUE = env('PAGINATION_VALUE');
            $addressdata      = Address::where('user_id',$user_id)->paginate($PAGINATION_VALUE);
            if($addressdata->count()) {
                $status         = 1;
                $StatusCode     = 200;
                $msg            = __('words.retrieved_successfully');
                $data           = $addressdata;
            }
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

        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'address_line_1' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'primary_address' => 'required',
        ]);
        if ($RegisterData->fails()) {
            $messages = $RegisterData->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message) {
                $msg = $message;
                $StatusCode     = 409;
                break;
            }
        } else {
            $requestData = $request->all();
            $requestData['status'] = 1;
            $OldAddress = Address::where('user_id',"=",$requestData['user_id'])->count();
            $requestData['primary_address'] = 0;
            if($OldAddress == 0) {
                $requestData['primary_address'] = 1;
            }
            $Address = Address::create($requestData);
            if($Address) {
                // if($requestData['primary_address'] == 1) {
                //     $address_id = $Address->id;
                //     $user_id    = $Address->user_id;
                //     Address::where('id',"!=",$address_id)->where('user_id',"=",$user_id)
                //             ->update(['primary_address' => 0]);
                // }
                $StatusCode     = 200;
                $status         = 1;
                $msg            = __('words.address_added');
                $data           = new AddressResource($Address);
            } else {
                $StatusCode     = 403;
                $status         = 0;
                $msg            = "Something wrong. Please try again.";
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($address_id=0)
    {
        
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();
        if(!empty($address_id)) {
            $addressdata       = Address::where('id',$address_id)->first();
            if($addressdata) {
                $status         = 1;
                $StatusCode     = 200;
                $msg            = __('words.retrieved_successfully');
                $data           = new AddressResource($addressdata);
            }
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'address_line_1' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'primary_address' => 'required',
        ]);
        if ($RegisterData->fails()) {
            $messages = $RegisterData->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message) {
                $msg = $message;
                $StatusCode     = 409;
                break;
            }
        } else {
            $requestData = $request->all();
            $requestData['status'] = 1;
            $address->update($requestData);
            if($address->save()) {
                if($address->primary_address == 1) {
                    $address_id = $address->id;
                    $user_id    = $address->user_id;
                    Address::where('id',"!=",$address_id)->where('user_id',"=",$user_id)
                            ->update(['primary_address' => 0]);
                }
                $StatusCode     = 200;
                $status         = 1;
                $msg            = __('words.address_updated');
                $data           = new AddressResource($address);
            } else {
                $StatusCode     = 403;
                $status         = 0;
                $msg            = "Something wrong. Please try again.";
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        if($address->delete()) {
            $StatusCode     = 200;
            $status         = 1;
            $msg            = __('words.address_deleted');
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }


    public function listaddressbyuser(Request $request)
    {

        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
        ]);
        if ($RegisterData->fails()) {
            $messages = $RegisterData->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message) {
                $msg = $message;
                $StatusCode     = 409;
                break;
            }
        } else {
            $user_id = $request->get('user_id');
            if(!empty($user_id)) {
                $PAGINATION_VALUE = env('PAGINATION_VALUE');
                $addressdata      = Address::where('user_id',$user_id)->paginate($PAGINATION_VALUE);
                if($addressdata->count()) {
                    $status         = 1;
                    $StatusCode     = 200;
                    $msg            = __('words.retrieved_successfully');
                    $data           = $addressdata;
                }
            }
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

}
