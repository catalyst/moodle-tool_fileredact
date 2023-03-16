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
 * Lib
 *
 * @package   tool_fileredact
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

use tool_fileredact\local\notification;
use tool_fileredact\local\redaction_controller;

/**
 * Processes a file (e.g. redaction) before the file is created.
 *
 * @param stdClass $filerecord New file record
 * @param array $more Optionally contains the content or path to the file
 */
function tool_fileredact_before_file_created(stdClass $filerecord = null, array $more) {
    // Continue only if the plugin is enabled.
    $enabled = get_config('tool_fileredact', 'enabled');
    if (!$enabled) {
        return;
    }

    if (empty($filerecord)) {
        return;
    }

    // Skip hook if it's a unit test.
    if (PHPUNIT_TEST) {
        return;
    }

    // Initialise and run the redactions, if required.
    $redactor = new redaction_controller($filerecord, $more);
    $redactor->run();

    if ($redactor->has_errors()) {
        $notification = new notification();
        if ($notification->should_notify()) {
            // List any / all errors that have occurred, if required.
            foreach ($redactor->errors() as $e) {
                \core\notification::warning($e->getMessage());
                // TODO: Add policy check, to change these 'warnings' into 'errors'
                // if the policy will not allow the files to be kept.
            }
        }

        // TODO: Add policy checks, whether or not to allow the file to be kept
        // or to remove the sensitive file. By default, it will preserve
        // existing behaviour / functionality of keeping the file even if
        // redaction fails.
    }
}

