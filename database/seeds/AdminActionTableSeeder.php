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