<?php

/**
 * VueListPlugin.class.php
 *
 * Plugin for listing things using vue.js.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Thomas Hackl <thomas.hackl@uni-passau.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    VueList
 */

class VueListPlugin extends StudIPPlugin implements SystemPlugin
{

    public function __construct()
    {
        parent::__construct();

        // Plugin only available if there are corresponding permissions.
        if ($GLOBALS['perm']->have_perm('root')) {
            $navigation = new Navigation('VueList',
                PluginEngine::getURL($this, [], 'users'));

            Navigation::addItem('/admin/vuelist', $navigation);
        }
    }

}
