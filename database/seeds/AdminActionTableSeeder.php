<?php

use Illuminate\Database\Seeder;
use App\AdminAction;

class AdminActionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('admin_action')->delete();

        $actions = [
            ['id' => 1, 'title' => 'Logout', 'remark' => 'User Logout','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 2, 'title' => 'Login', 'remark' => 'User Login','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 3, 'title' => 'Update Profile', 'remark' => 'User Update Profile','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 4, 'title' => 'Add User', 'remark' => 'Add User','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 5, 'title' => 'Edit User', 'remark' => 'Edit User','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 6, 'title' => 'Delete User', 'remark' => 'Delete User','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 7, 'title' => 'Change Password', 'remark' => 'User Change Password','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 8, 'title' => 'Add Money', 'remark' => 'User Add Money','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 9, 'title' => 'Add Product', 'remark' => 'Add Product','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 10, 'title' => 'Edit Product', 'remark' => 'Edit Product','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 11, 'title' => 'Add Category', 'remark' => 'Add Category','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 12, 'title' => 'Edit Category', 'remark' => 'Edit Category','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 13, 'title' => 'Add Delivery User', 'remark' => 'Add Delivery User','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 14, 'title' => 'Edit Delivery User', 'remark' => 'Edit Delivery User','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 15, 'title' => 'Order Status', 'remark' => 'Change Order Status','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 16, 'title' => 'Assign Delivery User', 'remark' => 'Assign Delivery User','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 17, 'title' => 'Edit Order', 'remark' => 'Edit Order','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 18, 'title' => 'Delete Order Product', 'remark' => 'Delete Order Product','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')], 
            ['id' => 19, 'title' => 'Add New Product Of Order', 'remark' => 'Add New Product Of Order','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 20, 'title' => 'Add New Order', 'remark' => 'Add New Order','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 21, 'title' => 'Add Offer', 'remark' => 'Add Offer','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 22, 'title' => 'Edit Offer', 'remark' => 'Edit Offer','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 23, 'title' => 'Delete offer', 'remark' => 'Delete offer','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 24, 'title' => 'Create new bill details', 'remark' => 'Create new bill details','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
            ['id' => 25, 'title' => 'Delete Bills', 'remark' => 'Delete Bills','created_at' => date('Y-m-d h:i:s'),'updated_at' => date('Y-m-d h:i:s')],
 		];
        foreach($actions as $action) {
            $isexist = AdminAction::find($action['id']);
            if (!$isexist) {
                AdminAction::Create($action);
            }
        }
        // AdminAction::insert($actions);
    }
}