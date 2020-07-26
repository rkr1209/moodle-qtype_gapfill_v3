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
 * Manage the question type css styles
 *
 * This does the same as the standard xml import but easier
 * @package    qtype_gapfill
 * @copyright  2020 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../../../config.php');

require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('qtype_gapfill_styles');


/**
 *  Manage css for styling instances of the gapfill quesiton
 *
 * @copyright Marcus Green 2020
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * Form for importing example questions
 */
class gapfill_styles_form extends moodleform {

  protected function definition() {
      $mform = $this->_form;
      $itemrepeatsatstart = 1;
      $mform->addElement('text', 'name', get_string('name'));
      $mform->addElement('textarea', 'style', get_string('css','qtype_gapfill'),['cols'=>'80']);

     // $this->definition_question_style($mform, $itemrepeatsatstart);
      $mform->addElement('submit', 'submitbutton', get_string('save'));
  }
  function definition_question_style($mform, $itemrepeatsatstart){
      $repeatarray =[];
      $repeatarray[] = $mform->createElement('text', 'name', get_string('name'));
      $repeatarray[]=  $mform->createElement('textarea', 'css', get_string('css','qtype_gapfill'),['cols'=>'80']);
      $repeatno = 1;
      $repeateloptions = [];
      $this->repeat_elements($repeatarray, $repeatno,
           $repeateloptions, 'style_repeats', 'option_add_fields', 1);
      $mform->setType('name', PARAM_TEXT);
      $mform->setType('css', PARAM_RAW);

  }

}
$mform = new gapfill_styles_form();

if ($data = $mform->get_data()) {
  global $DB;
  if($data->style > "" ){

    $params = ["name"=>$data->name, "style" => $data->style];
    $DB->insert_record('question_gapfill_style',$params);
  }
}


echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
