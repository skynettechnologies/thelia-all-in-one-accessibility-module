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
/*************************************************************************************/

namespace AllInOneAccessibility\Hook;

use AllInOneAccessibility\AllInOneAccessibility;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Log\Tlog;
use Thelia\Model\ConfigQuery;

/**
 * Class AIOFrontHook
 * @package Dealer\Hook
 */
class AIOFrontHook extends BaseHook
{

    /**
     * @param HookRenderEvent $event
     */
    public function onMainStyleSheet(HookRenderEvent $event)
    {
        $event->add($this->addCSS("/assets/css/all-in-one-accessibility.css"));
        $event->add($this->addJS("/assets/js/allinoneaccessibility_compiled.js"));

    }


    /**
     * @param HookRenderEvent $event
     */
    public function onMainAfterJVSIncludes(HookRenderEvent $event)
    {
        if (AllInOneAccessibility::getConfigValue("all-in-one-accessibility-hook-all-page")) {


            $event->add($this->addJS("/assets/js/allinoneaccessibility_compiled.js"));
        }
    }

    /**
     * @param HookRenderEvent $event
     */
    public function onAllInOneAccessibilityinsertJS(HookRenderEvent $event)
    {
        if (!AllInOneAccessibility::getConfigValue("all-in-one-accessibility-hook-all-page")) {


            $event->add($this->addJS("/assets/js/allinoneaccessibility_compiled.js"));
        }
    }
}
