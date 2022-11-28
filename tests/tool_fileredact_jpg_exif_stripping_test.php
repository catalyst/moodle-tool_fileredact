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

namespace tool_fileredact;

use tool_fileredact\local\jpg;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/compatibility_trait.php');

/**
 * Defines names of plugin types and some strings used at the plugin managment
 *
 * @package   tool_fileredact
 * @covers    \tool_fileredact\classes\local\jpg\strip_exif
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_fileredact_jpg_exif_stripping_test extends \advanced_testcase {
    use compatibility_trait;

    /**
     * Check and ensure the fixture file has the markers we're testing for.
     */
    public function test_fixture_has_gps_markers() {
        $pathname = __DIR__ . '/fixtures/gps.jpg';

        $exiftoolexec = \escapeshellarg('exiftool');
        exec("$exiftoolexec $pathname", $output);
        $output = implode($output);
        $this->compatible_assertStringContainsString('GPS Latitude', $output);
        $this->compatible_assertStringContainsString('GPS Longitude', $output);
    }

    /**
     * Test to ensure the JPG is processed, and returns no GPS information
     */
    public function test_method_removes_gps_markers() {
        $pathname = $this->get_copy_of(__DIR__ . '/fixtures/gps.jpg');

        // Convert example jpg.
        (new jpg\strip_exif)->run((object) ['filename' => 'test.jpg'], ['pathname' => $pathname]);

        // Ensure the final jpg does NOT contain sensitive markers (e.g. GPS), thus indicating it was redacted.
        $exiftoolexec = \escapeshellarg('exiftool');
        exec("$exiftoolexec $pathname", $output);
        $output = implode($output);
        $this->compatible_assertStringNotContainsString('GPS Latitude', $output);
        $this->compatible_assertStringNotContainsString('GPS Longitude', $output);
    }

    /**
     * Returns a copy of a file to allow for temporary operations on target file without affecting original.
     *
     * @param  string $filepath to file to copy
     * @return string path to copied file
     */
    private function get_copy_of(string $filepath) {
        $filename = basename($filepath);
        $temparea = make_request_directory();
        $tmppath = $temparea . DIRECTORY_SEPARATOR . $filename;
        copy($filepath, $tmppath);

        return $tmppath;
    }
}
