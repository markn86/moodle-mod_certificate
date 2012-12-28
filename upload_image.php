<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * Handles uploading files
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->dirroot.'/mod/certificate/lib.php');
require_once($CFG->dirroot.'/mod/certificate/upload_image_form.php');

require_login();

$context = get_system_context();
require_capability('moodle/site:config', $context);

$struploadimage = get_string('uploadimage', 'certificate');

$PAGE->set_url('/admin/settings.php', array('section' => 'modsettingcertificate'));
$PAGE->set_pagetype('admin-setting-modsettingcertificate');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);
$PAGE->set_title($struploadimage);
$PAGE->set_heading($SITE->fullname);
$PAGE->navbar->add($struploadimage);

$imagetypes = array(
    CERT_IMAGE_BORDER => get_string('border', 'certificate'),
    CERT_IMAGE_WATERMARK => get_string('watermark', 'certificate'),
    CERT_IMAGE_SIGNATURE => get_string('signature', 'certificate'),
    CERT_IMAGE_SEAL => get_string('seal', 'certificate')
);
$upload_form = new mod_certificate_upload_image_form();

if ($upload_form->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php?section=modsettingcertificate'));
} else if ($data = $upload_form->get_data()) {
    // Ensure the directory for storing is created
    foreach($imagetypes as $imagetype=>$label) {
        file_save_draft_area_files($data->$imagetype, $context->id, 'mod_certificate', $imagetype,
            0, array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 50));
    }

    //$uploaddir = "mod/certificate/pix/$data->imagetype";
    //$filename = $upload_form->get_new_filename('certificateimage');
    //make_upload_directory($uploaddir);
    //$destination = $CFG->dataroot . '/' . $uploaddir . '/' . $filename;
    //if (!$upload_form->save_file('certificateimage', $destination, true)) {
    //    throw new coding_exception('File upload failed');
    //}

    redirect(new moodle_url('/admin/settings.php?section=modsettingcertificate'), get_string('changessaved'));
}

echo $OUTPUT->header();
echo $upload_form->display();
echo $OUTPUT->footer();
?>
