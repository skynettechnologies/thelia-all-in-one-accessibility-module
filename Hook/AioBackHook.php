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

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class AioBackHook
 * @package AllInOneAccessibility\Hook
 */
class AioBackHook extends BaseHook
{
        public function onModuleConfig(HookRenderEvent $event)
        {
            $event->add($this->render("module_configuration.html"));
        }


}
