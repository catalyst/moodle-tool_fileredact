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

/**
 * Language strings
 *
 * @package   tool_fileredact
 * @author    Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright Catalyst IT, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['enabled'] = 'Enable/disable this plugin';
$string['notifytarget'] = 'Display errors to';
$string['jpgstripexifenabled_help'] = 'Removes EXIF data from all incoming JPEG files, which might include GPS / Location, personal information and other sensitive data.';
$string['jpgstripexifenabled'] = 'Enable/disable JPG EXIF stripping';
$string['notifytarget:admins'] = 'Admins';
$string['notifytarget:everyone'] = 'Everyone';
$string['notifytarget:no-one'] = 'No-one';
$string['pdfflattenenabled_help'] = 'Flattening a pdf may remove expected functionality, but will remove javascript, actions, events, etc, and try to preserve basic functionality, such as text search if available in the original PDF.';
$string['pdfflattenenabled'] = 'Enable/disable PDF flatten';
$string['pluginname'] = 'File Redact';
$string['privacy:metadata'] = 'The fileredact tool does not store any data';
$string['redactionfailed:failedtoprocess'] = 'Redaction failed: failed to process file';
$string['redactionfailed:stillsensitive'] = 'Redaction failed: file still contains sensitive markers';
