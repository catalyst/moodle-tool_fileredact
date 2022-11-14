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

namespace tool_fileredact\local\pdf;

/**
 * Flattens the file using ghostscript.
 *
 * @package   tool_fileredact
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class flatten {

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
     * Flattens the file using ghostscript
     *
     * @return bool as to whether the operation was successful
     */
    public function run() {
        // Get the conversion command.
        $src = $this->hookargs['pathname'];
        $temparea = make_request_directory();
        $dst = $temparea . DIRECTORY_SEPARATOR . $this->filerecord->filename;

        // Prepare the ghostscript (gs) command.
        $command = $this->get_gs_command($src, $dst);

        // Apply conversion.
        exec($command, $output);

        // Test to ensure conversion succeeded or not.
        if (!file_exists($dst)) {
            // Something has gone wrong in the conversion.
            debugging('tool_fileredact: ' . implode($output));
            return false;
        }

        // Conversion was successful, so replace the original with the new in one op.
        rename($dst, $src);
        return true;
    }

    /**
     * Gets the ghostscript (gs) command to convert the PDF into one without JS / non-basic behaviour.
     *
     * @param string $src The source path of the PDF file.
     * @param string $dst The source path of the PDF file.
     * @return string The ghostscript (gs) command to use to flatten the file
     */
    private function get_gs_command(string $src, string $dst): string {
        global $CFG;

        $gsexec = \escapeshellarg($CFG->pathtogs);
        $tempdstarg = \escapeshellarg($dst);
        $tempsrcarg = \escapeshellarg($src);
        $command = "$gsexec -sDEVICE=pdfwrite -dSAFER -dBATCH -dNOPAUSE -sOutputFile=$tempdstarg $tempsrcarg";
        return $command;
    }
}
