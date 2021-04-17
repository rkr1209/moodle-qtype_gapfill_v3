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
 *
 * Import sample Gapfill questions f.
 *
 * This does the same as the standard xml import but easier
 * @package    qtype_gapfill
 * @copyright  2015 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/xmlize.php');
require_once($CFG->libdir . '/questionlib.php');
require_once($CFG->dirroot . '/question/format/xml/format.php');

admin_externalpage_setup('qtype_gapfill_import');

/**
 *  Edit gapfill question type themes
 *
 * @copyright Marcus Green 2021
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * Form for editing gapfill quesiton type themes
 */
class gapfill_theme_edit_form extends moodleform {
    /**
     *
     * @var number
     */
    public $questioncategory;
    /**
     *
     * @var number
     */
    public $course;
    /**
     * mini form for entering the import details
     */
    protected function definition() {
        $mform = $this->_form;
        $themes = get_config('qtype_gapfill', 'themes');
        $mform->addElement('textarea', 'themes', get_string('themes'), ['rows' => 30, 'cols' => 60]);

        $mform->setDefault('themes', $themes);
        $mform->setType('themes', PARAM_RAW);
        //$mform->addElement('submit', 'submitbutton', get_string('import'));
        $this->add_action_buttons();
    }


}

$mform = new gapfill_theme_edit_form(new moodle_url('/question/type/gapfill/import_examples.php/'));
if ($fromform = $mform->get_data()) {
    $category = $mform->questioncategory;
    $categorycontext = context::instance_by_id($category->contextid);
    $category->context = $categorycontext;

    $qformat = new qformat_xml();
    $file = $CFG->dirroot . '/question/type/gapfill/examples/'.current_language().'/gapfill_examples.xml';
    $qformat->setFilename($file);

    $qformat->setCategory($category);
    echo $OUTPUT->header();
    // Do anything before that we need to.
    if (!$qformat->importpreprocess()) {
        print_error('cannotimport', 'qtype_gapfill', $PAGE->out);
    }
    // Process the uploaded file.
    if (!$qformat->importprocess($category)) {
        print_error(get_string('cannotimport', ''), '', $PAGE->url);
    } else {
        /* after the import offer a link to go to the course and view the questions */
        $visitquestions = new moodle_url('/question/edit.php?courseid=' . $mform->course->id);
        echo $OUTPUT->notification(get_string('visitquestions', 'qtype_gapfill', $visitquestions->out()), 'notifysuccess');
        echo $OUTPUT->continue_button(new moodle_url('import_examples.php'));
        echo $OUTPUT->footer();
        return;
    }
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
