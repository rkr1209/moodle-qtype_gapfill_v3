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

require_login();

global $DB,$PAGE;
$id = optional_param('id',0,PARAM_INT);

$action = optional_param('action','', PARAM_ALPHANUMEXT);
$params = ['id' => $id, 'action' => $action];

$PAGE->set_url('/question/type/gapfill/admin/style.php', $params);


if(! $DB->count_records('question_gapfill_style')){
  $id = $DB->insert_record('question_gapfill_style',['name' =>'','style'=>'']);
}
$maxmin = "";
if(!$id){
  $style = $DB->get_record_sql('select * from {question_gapfill_style} order by id limit 1');
} else {
  $style = $DB->get_record('question_gapfill_style', ['id' => $id]);
  $sql = "SELECT max(id) maxid FROM {question_gapfill_style}";
  $record = $DB->get_record_sql($sql);
  if($style->id == $record->maxid){
    $maxmin='max';
  }
  $sql = "SELECT min(id) minid FROM {question_gapfill_style}";
  $record = $DB->get_record_sql($sql);
  if($style->id == $record->minid){
    $maxmin='min';
  }
}

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

      $style = $this->_customdata['style'];
      $maxmin = $this->_customdata['maxmin'];
      $id = $style->id;
     if (!empty($this->_customdata['id'])) {
       $mform->addElement('hidden', 'action', 'edit');
       $mform->setType('action', PARAM_ALPHANUMEXT);
      }


      $nextdisabled = [];
      $previousdisabled =[];
      if($maxmin == 'max'){
        $nextdisabled = ['disabled' => true];
      }
      if($maxmin == 'min'){
        $previousdisabled = ['disabled' => true];
      }
      $itemrepeatsatstart = 1;
      $nextprevious[] = $mform->createElement('submit','previous',get_string('previous','qtype_gapfill'),$previousdisabled);

      $nextprevious[] = $mform->createElement('submit','next',get_string('next','qtype_gapfill'),$nextdisabled);
      $mform->addGroup($nextprevious, 'nextprevious', '', [''], false);

      $mform->addElement('hidden','id','', ['readonly' => 'true']);

      $mform->setDefault('id',$id);
      $mform->setType('id', PARAM_INT);

      $mform->addElement('text', 'name', get_string('name'));
      $mform->setType('name',PARAM_TEXT);
      $mform->addElement('textarea', 'style', get_string('css','qtype_gapfill'),['rows' =>'15','cols' => '80']);

       $this->add_action_buttons();

      $createdelete[] = $mform->createElement('submit','create',get_string('create','qtype_gapfill'));
      $createdelete[] = $mform->createElement('submit','delete',get_string('delete','qtype_gapfill'));
      $mform->addGroup($createdelete, 'createdelete', '', [''], false);

      if($style){
        $mform->setDefault('name',$style->name);
        $mform->setDefault('style',$style->style);
      }


  }

  public function get_style($mform,$id){
    global $DB;
    if ($id) {
      $style = $DB->get_record('question_gapfill_style', ['id' => $id], '*', MUST_EXIST);
    } else {
      $sql = 'SELECT * FROM {question_gapfill_style} WHERE id IN (SELECT MAX(id) FROM {question_gapfill_style})';
      $style = $DB->get_record_sql($sql);
      if($style){
        return $style;
      }
    }
  }

}
$id = $style->id;

$mform = new gapfill_styles_form(null, ['style' =>$style,'maxmin'=>$maxmin]);




if ($data = $mform->get_data()) {
  global $DB;
  if(isset($data->create)){
    $params = ["name"=> "", "style" => ""];
    $id = $DB->insert_record('question_gapfill_style',$params);
    $params['id'] =$id;
    redirect(new moodle_url('/question/type/gapfill/admin/styles.php', $params));
  }
  if(isset($data->submitbutton)){
    $params = ['id'=>$style->id, "name"=> $data->name, "style" => $data->style];
    $id = $DB->update_record('question_gapfill_style',$params);
    \core\notification::add('Saved', \core\notification::INFO);

  }
  if(isset($data->next)){
    $sql = "SELECT id FROM {question_gapfill_style} WHERE id > '$id' ORDER BY id ASC LIMIT 1";
    $result = $DB->get_record_sql($sql);
    if($result){
       $params=['id' =>$result->id, 'action'=>'next'];
    }
    redirect(new moodle_url('/question/type/gapfill/admin/styles.php', $params));
  }
  if(isset($data->previous)){
    $sql = "SELECT id FROM {question_gapfill_style} WHERE id < '$id' ORDER BY id ASC LIMIT 1";
    $result = $DB->get_record_sql($sql);
    if($result){
      $params=['id' =>$result->id, 'action'=>'previous'];
    }
    redirect(new moodle_url('/question/type/gapfill/admin/styles.php', $params));
  }
  if(isset($data->delete)){
    $DB->delete_records('question_gapfill_style', ['id'=>$id]);
    $params['id'] = '';
    redirect(new moodle_url('/question/type/gapfill/admin/styles.php', $params));
  }

}
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();



