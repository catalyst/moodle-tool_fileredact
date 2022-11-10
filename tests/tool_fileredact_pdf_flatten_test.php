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

use tool_fileredact\local\pdf;

/**
 * Defines names of plugin types and some strings used at the plugin managment
 *
 * @package   tool_fileredact
 * @covers    \tool_fileredact\classes\local\pdf\flatten
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_fileredact_pdf_flatten_test extends \advanced_testcase {

    /**
     * Check and ensure the fixture file has the markers we're testing for.
     */
    public function test_fixture_has_js_markers() {
        $pathname = __DIR__ . '/fixtures/js-link.pdf';

        // Ensure example pdf DOES contain javascript markers.
        exec('strings ' . $pathname, $output);
        $this->assertStringContainsString('/JavaScript', implode($output));
    }

    /**
     * Test to ensure the pdf comes out flattened, and contain no JS markers.
     */
    public function test_flattening_removes_js_markers() {
        $pathname = $this->get_copy_of(__DIR__ . '/fixtures/js-link.pdf');

        // Convert example PDF.
        $flattener = new pdf\flatten((object) ['filename' => 'test.pdf'], ['pathname' => $pathname]);
        $flattener->run();

        // Ensure the final pdf does NOT contain javascript markers, thus indicating it was flattened.
        exec('strings ' . $pathname, $output);
        $this->assertStringNotContainsString('/JavaScript', implode($output));
    }

    /**
     * Returns a copy of a file to allow for temporary operations on said file without affecting original.
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
