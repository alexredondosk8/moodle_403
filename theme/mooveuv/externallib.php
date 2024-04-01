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
 * External theme_mooveuv API
 *
 * @package    theme_mooveuv
 * @category   external
 * @author     2022 Iader E. Garcia Gomez <iadergg@gmail.com>
 * @copyright  2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

class theme_mooveuv_external extends external_api {

    /**
     * Get messagearea message search users parameters.
     *
     * @return external_function_parameters
     * @since  Moodle 4.0
     */
    public static function get_course_parameters() {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'The course ID to search.'),
            )
        );
    }


    /**
     * Get course by id.
     *
     * @param  int $courseid
     * @return array with course data
     * @since  Moodle 4.0
     */
    public static function get_course($courseid) {

        global $DB;

        if ($courseid > 1) {
            $course = $DB->get_record("course",
                                      array("id" => $courseid));
        }

        return array(
            "result" => json_encode($course),
            "warnings" => array(),
        );

    }

    /**
     * Get messagearea message search users returns.
     *
     * @return external_single_structure
     * @since  Moodle 4.0
     */
    public static function get_course_returns() {
        return new external_single_structure(
            array(
                "result" => new external_value(PARAM_RAW, "JSON course"),
                "warnings" => new external_warnings(),
            )
        );
    }


    /**
     * Get array that contains a key-value element where 'value' is a list of the entered questionnaries' ids.
     *
     * @return array
     * @since  Moodle 4.0
     * @author Paul Rodrigo Rojas G (paul.rojas@correounivalle.edu.co)
     */
    public static function get_questionnaries() {

        global $DB;

        $sql = "SELECT value FROM mdl_config_plugins
        WHERE plugin='theme_mooveuv' AND name='questionnaire_identifiers'";

        $questionnariesSQL =  $DB->get_record_sql($sql);

        $questionnaries = explode(", ", $questionnariesSQL->value);

        return array('value' => $questionnaries);
    }


    /**
     * Specifies what get_questionnaries() function's parameters are.
     *
     * @return external_function_parameters
     * @since  Moodle 4.0
     * @author Paul Rodrigo Rojas G (paul.rojas@correounivalle.edu.co)
     */
    public static function get_questionnaries_parameters() {
        return new external_function_parameters(
            array()
        );
    }


    /**
     * Specifies what get_questionnaries() function returns.
     *
     * @return external_single_structure
     * @since  Moodle 4.0
     * @author Paul Rodrigo Rojas G (paul.rojas@correounivalle.edu.co)
     */
    public static function get_questionnaries_returns() {
        return new external_single_structure(
            array('value'=> new external_multiple_structure(new external_value(PARAM_TEXT, 'El valor')))
        );
    }

}