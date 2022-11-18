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

namespace tool_fileredact\local\jpg;

use tool_fileredact\local\redaction_method;

/**
 * Strips exif data from JPG files using exiftool
 *
 * @package   tool_fileredact
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class strip_exif implements redaction_method {

    /**
     * Strips the EXIF data from a given JPG file
     *
     * @param \stdClass $filerecord
     * @param array $hookargs
     * @return bool as to whether the operation was successful
     */
    public function run(\stdClass $filerecord, array $hookargs): bool {
        $src = $hookargs['pathname'];
        $temparea = make_request_directory();
        $dst = $temparea . DIRECTORY_SEPARATOR . $filerecord->filename;

        // Prepare the exiftool command.
        $command = $this->get_exiftool_command($src, $dst);

        // Apply redaction.
        exec($command, $output);

        // Test to ensure redaction succeeded or not.
        if (!file_exists($dst)) {
            // Something has gone wrong in the redaction.
            debugging('tool_fileredact: ' . implode($output));
            return false;
        }

        // TODO: Any further testing to ensure the output file is valid / correct / etc.

        // Removal of EXIF data was successful, replace the original with the new in one op.
        rename($dst, $src);
        return true;
    }

    /**
     * Gets the exiftool command to strip the JPG file of all EXIF data.
     *
     * @param string $src The source path of the file.
     * @param string $dst The source path of the file.
     * @return string The command to use to remove all EXIF data from the file
     */
    private function get_exiftool_command(string $src, string $dst): string {
        $exiftoolexec = \escapeshellarg('exiftool');
        $tempdstarg = \escapeshellarg($dst);
        $tempsrcarg = \escapeshellarg($src);
        $command = "$exiftoolexec -all= -o $tempdstarg $tempsrcarg";
        return $command;
    }
}
