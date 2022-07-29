<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

/**
 * Function to upgrade 'local_webhooks'.
 *
 * @param int $oldversion
 *
 * @return bool
 */
function xmldb_local_webhooks_upgrade(int $oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2022072900) {
        //Increase the "token" column size from "char (255)" to "text"
        //https://github.com/valentineus/moodle-webhooks/issues/27
        $table = new xmldb_table('local_webhooks_service');

        $field = new xmldb_field('token');
        $field->set_attributes(XMLDB_TYPE_TEXT, null, null, null, null, null);
        try {
            $dbman->change_field_type($table, $field);
        } catch (moodle_exception $e) {}

        upgrade_plugin_savepoint(true, 2022072900, 'local', 'webhooks');
    }

    return true;
}