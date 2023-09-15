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

use tool_fileredact\local\redaction_method;

/**
 * Flattens the file using ghostscript.
 *
 * @package   tool_fileredact
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class flatten implements redaction_method {

    /**
     * Flattens the file using ghostscript
     *
     * @param \stdClass $filerecord
     * @param array $hookargs
     */
    public function run(\stdClass $filerecord, array $hookargs) {
        // Get the conversion command.
        $src = $hookargs['pathname'];
        $temparea = make_request_directory();
        $dst = $temparea . DIRECTORY_SEPARATOR . $filerecord->filename;

        // Prepare the ghostscript (gs) command.
        $command = $this->get_gs_command($src, $dst);

        // Apply conversion.
        exec($command, $output, $code);

        // Test redaction process issues.
        if ($code !== 0           // Non-zero return code.
            || !file_exists($dst) // Ensure new file exists.
        ) {
            // Something has gone wrong in the redaction process.
            debugging('tool_fileredact: ' . implode($output));
            throw new \moodle_exception('redactionfailed:failedtoprocess', 'tool_fileredact', '', get_class($this));
        }

        // Conversion was successful, so replace the original with the new in one op.
        rename($dst, $src);
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
        $intermediate = get_request_storage_directory() . '/pdftemp';
        $tempdstarg = \escapeshellarg($dst);
        $tempsrcarg = \escapeshellarg($src);
        $sharedflags = '-dSAFER -dBATCH -dNOPAUSE';
        // Use a 2 stage execution pipeline via a GS intermediate language that does not support active elements like Javascript.
        return "$gsexec -sDEVICE=ps2write $sharedflags -sOutputFile=$intermediate $tempsrcarg && $gsexec -sDEVICE=pdfwrite $sharedflags -sOutputFile=$tempdstarg $intermediate";
    }
}
