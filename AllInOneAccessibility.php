<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace AllInOneAccessibility;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Model\ConfigQuery;
use Thelia\Module\BaseModule;
use Thelia\Model\Admin;
use Thelia\Model\AdminQuery;

class AllInOneAccessibility extends BaseModule
{
    const MESSAGE_DOMAIN = "allinoneaccessibility";
    const ROUTER = "router.allinoneaccessibility";
    const CONF_API_KEY = "allinoneaccessibility_api_key";

    /**
     * This method is called just after the module was successfully activated.
     *
     * @param ConnectionInterface $con
     */
    public function postActivation(ConnectionInterface $con = null): void
    {
        $admin = AdminQuery::create()
        ->findOne(); // Fetch the first admin, or you can apply filters if necessary

    if ($admin) {
        // Get admin details
        $username = $admin->getUsername(); // Admin's username
        $userEmail = $admin->getEmail(); // Admin's email

        // Log the details for debugging purposes
        error_log("Admin's username: " . $username . ", email: " . $userEmail);
    } else {
        // Handle case where no admin exists in the database
        error_log("No admin user found.");
    }
        //end user detail
         $websitename = $_SERVER['SERVER_NAME'];

        $packageType = "free-widget";
            
        // Array of details to send
        $arrDetails = array(
            'name' => $username,
            'email' => $userEmail,
            'company_name' => '',
            'website' => base64_encode($websitename),
            'package_type' => $packageType,
            'start_date' => date(DATE_ISO8601),
            'end_date' => '',
            'price' => '',
            'discount_price' => '0',
            'platform' => 'Craft',
            'api_key' => '',
            'is_trial_period' => '',
            'is_free_widget' => '1',
            'bill_address' => '',
            'country' => '',
            'state' => '',
            'city' => '',
            'post_code' => '',
            'transaction_id' => '',
            'subscr_id' => '',
            'payment_source' => ''
        );
        
        // First API URL to fetch autologin link
        $apiUrl = "https://ada.skynettechnologies.us/api/get-autologin-link";
        
        // Set up cURL for the first API request
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['website' => base64_encode($websitename)]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        
        // Execute the request and get the response
        $response = curl_exec($ch);
        if(curl_errno($ch)) {
          
        }
        curl_close($ch);
        
        // Decode the response to check if the link is present
        $result = json_decode($response, true);
        if (isset($result['link'])) {
            // Successfully got the link
        
            
        } else {
            // Link not found, proceed with second API call
          
        
            // Second API URL to add user domain
            $secondApiUrl = "https://ada.skynettechnologies.us/api/add-user-domain";
        
            // Set up cURL for the second API request
            $ch = curl_init($secondApiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrDetails));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));
        
            // Execute the second request and get the response
            $response = curl_exec($ch);
            if(curl_errno($ch)) {
             
               
            }
            curl_close($ch);
        
            // Decode the second response to handle the result
            $data = json_decode($response, true);
            
           
         }

        //end user
        if (ConfigQuery::read("all-in-one-accessibility-hook-all-page")) {
            AllInOneAccessibility::setConfigValue("all-in-one-accessibility-hook-all-page", ConfigQuery::read("all-in-one-accessibility-hook-all-page"));
        } elseif (!AllInOneAccessibility::getConfigValue("all-in-one-accessibility-hook-all-page")) {
            AllInOneAccessibility::setConfigValue("all-in-one-accessibility-hook-all-page", false);
        }
        if (ConfigQuery::read(AllInOneAccessibility::CONF_API_KEY)) {
            AllInOneAccessibility::setConfigValue(AllInOneAccessibility::CONF_API_KEY, ConfigQuery::read(AllInOneAccessibility::CONF_API_KEY));
        }
    }


    public function getHooks()
    {
        return [
            [
                'type' => TemplateDefinition::FRONT_OFFICE,
                'code' => 'allinoneaccessibility.front.insertjs',
                'title' => 'Hook to insert all in one accessibility api js',
                'active' => true,
                'block' => false,
                'module' => true
            ],
        ];
    }
}
