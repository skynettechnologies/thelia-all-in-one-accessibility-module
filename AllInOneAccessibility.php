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
