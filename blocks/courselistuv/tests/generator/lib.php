<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Course List UV block test generator.
 *
 * @package     block_courselistuv
 * @category    PHPUnit
 * @author      2022 Iader E. García Gómez <iadergg@gmail.com>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_courselistuv_generator extends testing_module_generator {

    /**
     * Create a semester.
     *
     * @param   array $params
     * @return  stdClass $semester
     */
    public function create_semester($params = array()) {

        global $DB;

        $record = new stdClass();

        if ($params) {
            $record->name = $params['name'];
            $record->code = $params['code'];;
            $record->timestart = $params['timestart'];
            $record->timecompletion = $params['timecompletion'];
            $record->active = $params['active'];
        } else {
            $record->name = 'SEPTIEMBRE/2021- MARZO/2022';
            $record->code = '202109061';
            $record->timestart = 1632978000;
            $record->timecompletion = 1646370000;
            $record->active = 1;
        }

        $idsemester = $DB->insert_record('iracv_academic_periods', $record);
        $semester = $DB->get_record('iracv_academic_periods', array('id' => $idsemester));

        return $semester;
    }
}
