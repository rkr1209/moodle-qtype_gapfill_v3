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

/*
 * JavaScript to add popup sql hints in the editor
 *
 * @package report_customsql
 * @copyright 2021 Marcus Green
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import CodeMirror from 'qtype_gapfill/codemirror/lib/codemirror';
import  'qtype_gapfill/codemirror/addon/hint/show-hint';
import  'qtype_gapfill/codemirror/addon/hint/css-hint';
import  'qtype_gapfill/codemirror/mode/css/css';
// import  'qtype_gapfill/codemirror/mode/javascript/javascript';
// import  'qtype_gapfill/codemirror/mode/xml/xml';

export const init = () => {
    var editor = CodeMirror.fromTextArea(document.getElementById("id_theme"), {
        lineNumbers: true,
        extraKeys: {"Ctrl-Space": "autocomplete"}
          });
    editor.setSize('100%', 400);

};
