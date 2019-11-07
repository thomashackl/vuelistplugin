<?php

/**
 * Class UsersController
 * Controller for getting and listing users.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Thomas Hackl <thomas.hackl@uni-passau.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    vuelist
 */

class UsersController extends AuthenticatedController
{

    /**
     * Actions and settings taking place before every page call.
     */
    public function before_filter(&$action, &$args)
    {
        $this->plugin = $this->dispatcher->plugin;

        if (!$GLOBALS['perm']->have_perm('root')) {
            throw new AccessDeniedException();
        }

        $this->set_layout(Request::isXhr() ? null : $GLOBALS['template_factory']->open('layouts/base'));

        $this->sidebar = Sidebar::get();
        $this->sidebar->setImage('sidebar/person-sidebar.png');

        $this->flash = Trails_Flash::instance();
    }

    public function index_action()
    {
        Navigation::activateItem('/admin/vuelist');
    }

    public function users_action($start = 0, $limit = 100)
    {
        $stmt = DBManager::get()->prepare("SELECT a.`user_id`, a.`Nachname`, a.`Vorname`, a.`username`, IF(o.`last_lifesign` IS NULL, '-', FROM_UNIXTIME(o.`last_lifesign`, '%d.%m.%Y %h:%i')) AS lastlogin
            FROM `auth_user_md5` a
                LEFT JOIN `user_online` o ON (o.`user_id` = a. `user_id`)
            WHERE a.`Nachname` != ''
                AND a.`Vorname` != ''
                AND a.`Nachname` NOT LIKE '0%'
                AND a.`Nachname` NOT LIKE '1%'
            ORDER BY TRIM(a.`Nachname`), TRIM(a.`Vorname`), TRIM(a.`username`)
            LIMIT :start, :limit");
        $stmt->bindParam('start', $start, PDO::PARAM_INT);
        $stmt->bindParam('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $this->render_json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function count_action()
    {
        $stmt = DBManager::get()->prepare("SELECT COUNT(`user_id`) AS count
            FROM `auth_user_md5`
            WHERE `Nachname` != ''
                AND `Vorname` != ''
                AND `Nachname` NOT LIKE '0%'
                AND `Nachname` NOT LIKE '1%'
            ORDER BY TRIM(`Nachname`), TRIM(`Vorname`), TRIM(`username`)");
        $stmt->execute();
        $this->render_json($stmt->fetch(PDO::FETCH_ASSOC));
    }

}
