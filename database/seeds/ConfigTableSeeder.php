<?php

use Illuminate\Database\Seeder;
use App\Config;
class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ArrConfig = array(
            [
                'id' => 1,
                'name' => 'GET_VERSION',
                'value' => '{
                                "ios": {
                                    "version": "1.0.0",
                                    "is_force_update": 0,
                                    "msg": "A new version is available, Please update before proceeding.",
                                    "feature": "Able to complete Job from history page details,MiCoin not update issue solved."
                                },
                                "android": {
                                    "version": "1.0.0",
                                    "is_force_update": 0,
                                    "msg": "A new version is available, Please update before proceeding.",
                                    "feature": "Able to complete Order from history page details,Order not update issue solved."
                                }
                            }',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'id' => 2,
                'name' => 'DELIVERY_CHARGE',
                'value' => 2,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'id' => 3,
                'name' => 'GST_CHARGE',
                'value' => 10,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'id' => 4,
                'name' => 'ABOUT_US',
                'value' => '{
                            "text": "PHPDots is one of the IT solution providers that has skyrocketed in growth amongst its peers within a short tenure of its establishment. Founded in 2017, the company has been pretty clear in its vision and mission – the best of technologies woven into making a professional website which is secure and pretty!",
                            "text_1" : "PHPDots is one of the IT solution providers that has skyrocketed in growth amongst its peers within a short tenure of its establishment. Founded in 2017, the company has been pretty clear in its vision and mission – the best of technologies woven into making a professional website which is secure and pretty!"
                            }',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'id' => 5,
                'name' => 'CONTACT_US',
                'value' => '{
                            "Call_Us" : "+91 9825096687",
                            "WhatsApp" : "+91 9825096687",
                            "Email_Us": "contact@phpdots.com",
                            "Location" : "1101, Times Square 1, Opposite Rambag, 59, Thaltej - Shilaj Rd, near Ravija Plaza, Thaltej, Shilaj, Gujarat 380059."
                            }',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'id' => 6,
                'name' => 'REFERRAL_MONEY',
                'value' => 50,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'id' => 7,
                'name' => 'PAYMENT_OPTIONS',
                'value' => '{
                                "1": {
                                    "payment_type": "Google Pay",
                                    "payment_number": 9825096687,
                                    "description": "After payment send screenshot on 9825096687 whatsapp number",
                                    "logo": "/images/paymentoption/Google-Pay.jpg"
                                },
                                "2": {
                                    "payment_type": "Paytm",
                                    "payment_number": 9825096687,
                                    "description": "After payment send screenshot on 9825096687 whatsapp number",
                                    "logo": "/images/paymentoption/Paytm-Logo.png"
                                },
                                "3": {
                                    "payment_type": "UPI ID",
                                    "payment_number": "jitendrarathod@test.ok",
                                    "description": "After payment send screenshot on 9825096687 whatsapp number",
                                    "logo": "/images/paymentoption/Upi-id.png"
                                }
                            }',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ]
        );
        foreach($ArrConfig as $config) {
            $isexist = Config::find($config['id']);
            if (!$isexist) {
                Config::Create($config);
            }
        }
    }
}
