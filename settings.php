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

/**
 * Settings
 *
 * @package    tool_fileredact
 * @author     Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright  Catalyst IT, 2022
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('tool_fileredact_settings', get_string('pluginname', 'tool_fileredact'));
    $ADMIN->add('tools', $settings);


    if ($ADMIN->fulltree) {
        $settings->add(new admin_setting_configcheckbox(
            'tool_fileredact/enabled',
            get_string('enabled', 'tool_fileredact'),
            '',
            '1'
        ));

        // PDF Flatten.
        $settings->add(new admin_setting_configcheckbox(
            'tool_fileredact/pdfflattenenabled',
            get_string('pdfflattenenabled', 'tool_fileredact'),
            get_string('pdfflattenenabled_help', 'tool_fileredact'),
            '1'
        ));

        // JPG EXIF stripping.
        $settings->add(new admin_setting_configcheckbox(
            'tool_fileredact/jpgstripexifenabled',
            get_string('jpgstripexifenabled', 'tool_fileredact'),
            get_string('jpgstripexifenabled_help', 'tool_fileredact'),
            '1'
        ));
    }

    $settings = null;
}
