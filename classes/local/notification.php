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

namespace tool_fileredact\local;

/**
 * Notification handler
 *
 * @package   tool_fileredact
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class notification {

    /** @var string */
    const TARGET_EVERYONE = 'everyone';

    /** @var string */
    const TARGET_NOONE = 'no-one';

    /** @var string */
    const TARGET_ADMINS = 'admins';

    /**
     * Returns whether or not the notification should be displayed to the user
     *
     * @return bool
     */
    public function should_notify() {
        // Check if errors / warnings should notify the user.
        $notifytarget = get_config('notifytarget', 'tool_fileredact');
        $shouldnotify = $notifytarget === self::TARGET_EVERYONE;
        if ($notifytarget === self::TARGET_ADMINS && is_siteadmin()) {
            $shouldnotify = true;
        }

        return $shouldnotify;
    }
}
