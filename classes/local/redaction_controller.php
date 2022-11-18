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
 * Redaction Controller
 *
 * Manages and applies different redaction strategies where applicable.
 *
 * @package   tool_fileredact
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class redaction_controller {

    /** @var \stdClass File record */
    private $filerecord;

    /** @var array additional options (path, contents) provided from the before_file_created hook */
    private $hookargs;

    /**
     * Initialise this instance
     *
     * @param \stdClass $filerecord
     * @param array $hookargs
     */
    public function __construct(\stdClass $filerecord, array $hookargs) {
        $this->filerecord = $filerecord;
        $this->hookargs = $hookargs;
    }

    /**
     * Runs a single redaction method for the file
     *
     * @param redaction_method $redactionmethod
     */
    private function run_method(redaction_method $redactionmethod) {
        $redactionmethod->run($this->filerecord, $this->hookargs);
    }

    /**
     * Prepares and runs all enabled redaction methods
     */
    public function run() {
        // For files with empty mimetypes, do nothing.
        if (!isset($this->filerecord->mimetype)) {
            return;
        }

        // Get the associated redaction methods.
        $methods = redaction_factory::get_methods($this->filerecord->mimetype);

        // For the given methods, run them if they are enabled.
        foreach ($methods as $enabledflag => $methodclass) {
            if (get_config('tool_fileredact', $enabledflag)) {
                $this->run_method(new $methodclass());
            }
        }
    }
}
