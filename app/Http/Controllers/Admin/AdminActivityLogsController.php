<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ActivityLogs;
use App\AdminAction;
use App\AdminUser;

class AdminActivityLogsController extends Controller
{
   public function __construct(){
        $this->moduleRouteText = "admin-activity-logs";
        $this->moduleViewName = "admin.admin_activity_logs";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = 'Admin Activity Logs';
        $this->module = $module;

        $this->modelObj = new ActivityLogs();

        $this->addMsg = $module ."has been added successfully!";
        $this->updateMsg = $module ." has been updated successfully!";
        $this->deleteMsg = $module ." has been deleted successfully!";
        $this->deleteErrorMsg = $module . " can not deleted!";

        view()->share("list_url", $this->list_url);
        view()->share("moduleRouteText", $this->moduleRouteText);
        view()->share("moduleViewName", $this->moduleViewName);

    }
    public function index()
    {
        $data = array();
        $data['module_title'] ='Admin Activity Logs'; 
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['addBtnName'] = $this->module;
        $data['btnAdd'] = 1;
        $data['activityTypeList'] = AdminAction::activityTypeList();

        return view($this->moduleViewName.'.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function data(){
        $model = ActivityLogs::select('activity_logs.*','admin_action.title as type_name','admin_user.first_name as user_name')
                    ->leftJoin('admin_action','activity_logs.activity_type_id','admin_action.id')
                    ->leftJoin('admin_user','activity_logs.user_id','admin_user.id');
        $model = $model->orderBy('activity_logs.created_at','desc');
        return \DataTables::eloquent($model)
        ->editColumn('date', function($row) {
            if(!empty($row->created_at))
                return date('Y-m-d h:i:s',strtotime($row->created_at));
            else
                return '';
        })
        ->rawcolumns(['date'])
        ->filter(function ($query)
        {
            $action_id = request()->get("action_id");
            $activity_type = request()->get("activity_type");
            $search_start_date = request()->get("search_start_date");
            $search_end_date = request()->get("search_end_date");
             
            if(!empty($action_id))
            {
                $query = $query->where('activity_logs.action_id','LIKE','%'.$action_id.'%');
            }
            if(!empty($activity_type))
            {
                $query = $query->where('admin_action.id','LIKE','%'.$activity_type.'%');
            }
            if(!empty($search_start_date) && !empty($search_end_date))
            {
                $query = $query->where('activity_logs.updated_at','LIKE',array('%'.$search_start_date.'%','%'.$search_end_date.'%'));
            }
        })
        ->make(true);
        
    }
}
