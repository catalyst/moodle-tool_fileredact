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
     */
    public function run(\stdClass $filerecord, array $hookargs) {
        $src = $hookargs['pathname'];
        $temparea = make_request_directory();
        $newsrc = $temparea . DIRECTORY_SEPARATOR . 'original_' . $filerecord->filename;
        $dst = $temparea . DIRECTORY_SEPARATOR . $filerecord->filename;

        // Copy the file due to exiftool being rather sensitive about filenames.
        copy($src, $newsrc);

        // Prepare the exiftool command.
        $command = $this->get_exiftool_command($newsrc, $dst);

        // Apply redaction.
        exec($command, $output, $code);

        // If the return code was anything other than zero.
        if ($code !== 0) {
            // Something has gone wrong in the redaction.
            debugging('tool_fileredact: ' . implode($output));
            throw new \moodle_exception('redactionfailed:failedtoprocess', 'tool_fileredact', '', get_class($this));
        }

        // If the file was "unchanged", then return true but don't replace the original.
        if (stripos(implode($output), '1 image files unchanged') !== false) {
            return;
        }

        // Test to ensure redaction succeeded or not.
        if (!file_exists($dst)) {
            // Something has gone wrong in the redaction.
            debugging('tool_fileredact: ' . implode($output));
            throw new \moodle_exception('redactionfailed:failedtoprocess', 'tool_fileredact', '', get_class($this));
        }

        // Extra cautious check to ensure the sensitive EXIF data is no longer present.
        if ($this->is_sensitive($dst)) {
            throw new \moodle_exception('redactionfailed:stillsensitive', 'tool_fileredact', '', get_class($this));
        }

        // Removal of EXIF data was successful, replace the original with the new in one op.
        rename($dst, $src);
    }

    /**
     * Checks if the target file is clean or not
     *
     * Probably a overly cautious check, but will check against a list of known markers considered sensitive
     *
     * @param string $filepath the path of the file to check
     */
    private function is_sensitive(string $filepath) {
        // Ensure the jpg does NOT contain any sensitive markers (e.g. GPS), thus indicating it was redacted.
        $exiftoolexec = \escapeshellarg('exiftool');
        $filepath = \escapeshellarg($filepath);
        exec("$exiftoolexec $filepath", $output);
        $output = implode($output);

        // List of sensitive markers to check for post processing.
        $sensitivemarkers = [
            'gps lat',
            'gps long',
            'gps pos',
        ];

        foreach ($sensitivemarkers as $marker) {
            if (stripos($output, $marker) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Gets the exiftool command to strip the JPG file of all EXIF data.
     *
     * @param string $src The source path of the file.
     * @param string $dst The destination path of the file.
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
