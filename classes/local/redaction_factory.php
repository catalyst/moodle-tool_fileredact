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
 * Redaction Factory class
 *
 * @package   tool_fileredact
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class redaction_factory {

    /**
     * Returns a list of applicable redaction methods given the filetype.
     *
     * @param  string $filemimetype e.g. 'application/pdf', 'text/html'
     * @return redaction_method[]
     */
    public static function get_methods(string $filemimetype): array {
        $mimetypes = get_mimetypes_array();

        // Define all redaction methods applicable based on the file type.
        // The key is the plugin config flag which determines whether the redaction method is enabled or not.
        static $allmethods = [
            'pdf' => [
                'pdfflattenenabled' => pdf\flatten::class,
            ],
            'jpg' => [
                'jpgstripexifenabled' => jpg\strip_exif::class,
            ],
        ];

        // Determine the file type based on the mime type provided (e.g. pdf, jpg, etc).
        $type = null;
        foreach ($mimetypes as $filetype => $mimetype) {
            // The isset condition is due to several base file types, being listed for the same mimetype (e.g. jpg, jpeg, jpe).
            if ($mimetype['type'] === $filemimetype && isset($allmethods[$filetype])) {
                $type = $filetype;
                break;
            }
        }

        // Returns the redaction methods available for the determined file type.
        return $allmethods[$type] ?? [];
    }
}
