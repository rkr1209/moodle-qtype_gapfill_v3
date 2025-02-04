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
 * Data to control defaults when creating and running a question
 *
 * @package    qtype_gapfill_v3_v3
 * @copyright  2013 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

$settings = null;

if (is_siteadmin()) {
    $ADMIN->add('qtypesettings', new admin_category('qtype_gapfill_v3_category', get_string('pluginname', 'qtype_gapfill_v3')));
    $conf = get_config('qtype_gapfill_v3');
    $settingspage = new admin_settingpage('gf_v3_settings' , get_string('settings'));
    $ADMIN->add('qtype_gapfill_v3_category', $settingspage);
     $settingspage->add(new admin_setting_configcheckbox('qtype_gapfill_v3/disableregex',
        get_string('disableregex', 'qtype_gapfill_v3'),
        get_string('disableregexset_text', 'qtype_gapfill_v3'), 1));
    $settingspage->add(new admin_setting_configcheckbox('qtype_gapfill_v3/singleuse',
            get_string('singleuse', 'qtype_gapfill_v3'),
            get_string('singleuse_text', 'qtype_gapfill_v3'), 0));
    $settingspage->add(new admin_setting_configcheckbox('qtype_gapfill_v3/fixedgapsize',
        get_string('fixedgapsize', 'qtype_gapfill_v3'),
        get_string('fixedgapsizeset_text', 'qtype_gapfill_v3') , 1));
    $settingspage->add(new admin_setting_configcheckbox('qtype_gapfill_v3/casesensitive',
        get_string('casesensitive', 'qtype_gapfill_v3'),
        get_string('casesensitive_help', 'qtype_gapfill_v3') , 0));
    $settingspage->add(new admin_setting_configcheckbox('qtype_gapfill_v3/optionsaftertext',
        get_string('optionsaftertext', 'qtype_gapfill_v3'),
        get_string('optionsaftertext_text', 'qtype_gapfill_v3') , 0));
    $settingspage->add(new admin_setting_configcheckbox('qtype_gapfill_v3/manualgrading',
        get_string('manualgrading', 'qtype_gapfill_v3'),
        get_string('manualgrading_text', 'qtype_gapfill_v3') , 0));
    $settingspage->add(new admin_setting_configcheckbox('qtype_gapfill_v3/letterhints',
        get_string('letterhints', 'qtype_gapfill_v3'),
        get_string('letterhints_text', 'qtype_gapfill_v3') , 0));
    $settingspage->add(new admin_setting_configcheckbox('qtype_gapfill_v3/addhinttext',
        get_string('addhinttext', 'qtype_gapfill_v3'),
        get_string('addhinttext_text', 'qtype_gapfill_v3') , 0));
    $settingspage->add(new admin_setting_configtextarea('qtype_gapfill_v3/delimitchars',
         get_string('delimitchars', 'qtype_gapfill_v3'),
         get_string('delimitset_text', 'qtype_gapfill_v3'),
         "[ ],{ },# #,@ @", PARAM_RAW, 20, 3));
    $ADMIN->add('qtype_gapfill_v3_category',
            new admin_externalpage(
                    'qtype_gapfill_v3_import',
                     get_string('importexamples', 'qtype_gapfill_v3'),
                     new moodle_url('/question/type/gapfill_v3/import_examples.php'),
                    'moodle/site:config'
            ));
}
