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
require_once('../../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('qtype_gapfill_theme_edit');

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
        global $PAGE;
        $mform = $this->_form;
        $PAGE->requires->css('/question/type/gapfill/amd/src/codemirror/lib/codemirror.css');
        $PAGE->requires->css('/question/type/gapfill/amd/src/codemirror/addon/hint/show-hint.css');
        $PAGE->requires->js_call_amd('qtype_gapfill/theme_edit', 'init');
        $themes = get_config('qtype_gapfill', 'themes');
        $mform->addElement('textarea', 'theme', get_string('themes'), ['rows' => 30, 'cols' => 60]);

        $mform->setDefault('theme', $themes);
        $mform->setType('theme', PARAM_RAW);
        $navbuttons = [];
        $navbuttons[] = $mform->createElement('submit', 'previous', 'Previous');
        $navbuttons[] = $mform->createElement('submit', 'next', 'Next');
        $mform->addGroup($navbuttons);
        $this->add_action_buttons();
    }


}

$mform = new gapfill_theme_edit_form(new moodle_url('/question/type/gapfill/theme_edit.php/'));
$message = '';
if ($data = $mform->get_data()) {
    if (isset($data->Next)) {
        $message = 'Next';
    }
    if (isset($data->Previous)) {
        $message = 'Previous';
    }

}

echo $OUTPUT->header();
$mform->display();
$OUTPUT->heading('Hello');


echo $OUTPUT->footer();

