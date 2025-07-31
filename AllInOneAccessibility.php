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
        // Use static values instead of fetching from Admin
        $username = 'Dear Customer';
        $userEmail = 'no-reply@thelia.com';

        $websitename = $_SERVER['SERVER_NAME'];
        $packageType = "free-widget";

        // Prepare payload for add-user-domain
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
            'platform' => 'Thelia',
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
        // Directly call add-user-domain API
        $secondApiUrl = "https://ada.skynettechnologies.us/api/add-user-domain";
        $ch = curl_init($secondApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrDetails));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);

        $data = json_decode($response, true);
        // Optional: Handle response if needed

        // Configuration fallback
        if (ConfigQuery::read("all-in-one-accessibility-hook-all-page")) {
            AllInOneAccessibility::setConfigValue("all-in-one-accessibility-hook-all-page", ConfigQuery::read("all-in-one-accessibility-hook-all-page"));
        } elseif (!AllInOneAccessibility::getConfigValue("all-in-one-accessibility-hook-all-page")) {
            AllInOneAccessibility::setConfigValue("all-in-one-accessibility-hook-all-page", false);
        }

        if (ConfigQuery::read(AllInOneAccessibility::CONF_API_KEY)) {
            AllInOneAccessibility::setConfigValue(AllInOneAccessibility::CONF_API_KEY, ConfigQuery::read(AllInOneAccessibility::CONF_API_KEY));
        }
    }



    public function getHooks(): array
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
