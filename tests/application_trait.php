<?php
// This file is part of Moodle - https://moodle.org/
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
 * Helper unit test methods that are highly related to the application.
 *
 * @package   tool_fileredact
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_fileredact;

/**
 * Helper unit test methods that are highly related to the application.
 *
 * This also includes methods that have been included to allow backwards compatibility.
 *
 * @package   tool_fileredact
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait application_trait {

    // PHPUnit backwards compatible methods which handles the fallback to previous version calls.
    // @codingStandardsIgnoreStart

    /**
     * Asserts whether the needle was found in the given haystack
     *
     * @param  string $needle
     * @param  string $haystack
     * @param  string $message
     */
    public function compatible_assertStringContainsString(string $needle, string $haystack, string $message = ''): void {
        if (method_exists($this, 'assertStringContainsString')) {
            $this->assertStringContainsString($needle, $haystack, $message);
        } else {
            $this->assertContains($needle, $haystack, $message);
        }
    }

    /**
     * Asserts whether the needle was found in the given haystack
     *
     * @param  string $needle
     * @param  string $haystack
     * @param  string $message
     */
    public function compatible_assertStringNotContainsString(string $needle, string $haystack, string $message = ''): void {
        if (method_exists($this, 'assertStringNotContainsString')) {
            $this->assertStringNotContainsString($needle, $haystack, $message);
        } else {
            $this->assertNotContains($needle, $haystack, $message);
        }
    }
    // @codingStandardsIgnoreEnd
}
