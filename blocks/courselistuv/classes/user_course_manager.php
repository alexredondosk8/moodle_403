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
 * Course manager class for the plugin Course List UV.
 *
 * @package     block_courselistuv
 * @author      2022 Iader E. García Gómez <iadergg@gmail.com>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_courselistuv;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/courselistuv/lib.php');

/**
 * User Course Manager for Block Course List UV.
 *
 * @package     block_courselistuv
 * @author      2022 Iader E. García Gómez <iadergg@gmail.com>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user_course_manager {

    /** @var int  User ID */
    protected int $userid;

    /** @var array  User courses*/
    protected array $courses;

    /**
     * Explicit constructor to set the properties of the class.
     *
     * @param   int $userid
     * @author  2022 Iader E. García Gómez <iadergg@gmail.com>
     */
    public function __construct(int $userid = 0) {
        $this->userid = $userid;
        $this->courses = $this->get_user_courses();
    }

    /**
     * Returns the courses of a user sorted and grouped.
     * If the user is not enrolled in any course, return an empty array.
     *
     * @return  array stdClass $courses
     * @author  2022 Iader E. García Gómez <iadergg@gmail.com>
     */
    private function get_user_courses() {

        global $DB;

        $courses = array();

        $sqlquery = "SELECT DISTINCT c.id as courseid,
                                     c.fullname as coursefullname,
                                     c.shortname as courseshortname,
                                     c.category as coursecategory,
                                     c.startdate as coursestardate,
                                     c.enddate as courseenddate,
                                     c.timecreated as coursetimecreated,
                                     cc.path as categorypath
                     FROM {role_assignments} ra
                        INNER JOIN {context} ctx ON ra.contextid = ctx.id
                        INNER JOIN {course} c ON ctx.instanceid = c.id
                        INNER JOIN {course_categories} cc ON c.category = cc.id
                     WHERE ctx.contextlevel = 50
                        AND ra.userid = :userid";
        //                AND c.visible = 1";

        $courses = $DB->get_records_sql($sqlquery, array("userid" => $this->userid));

        $courses = $this->group_courses($courses);

        return $courses;
    }

    /**
     * Receives a courses array as a parameter.
     * Returns a grouped and sorted courses array.
     *
     * Example of param:
     * {
     *  stdObject(
     *      courseid => 123
     *      coursefullname => "Test Course 1"
     *      courseshortname => "tc1"
     *      coursecategory => 6
     *      coursestartdate => 123456789
     *      courseenddate => 123456789
     *      coursetimecreated => 123456789
     *      categorypath => /6/89/
     *  )
     * }
     *
     * @param   array stdClass $courses
     * @return  array stdClass $sortedcourses
     */
    private function group_courses($courses) {

        global $DB;

        $coursestoreturn = array();
        $coursestoreturn['regular_courses'] = array();
        $coursestoreturn['non_regular_courses'] = array();

        $idregularcoursescategory = $DB->get_record('course_categories', array('idnumber' => 'regular_courses'))->id;

        // Group courses in regular and non-regular.
        foreach ($courses as $course) {

            // Validations for regular courses.
            // Path of the first level category.
            $firstlevelcategorypath = explode('/', $course->categorypath)[1];

            if ($firstlevelcategorypath == $idregularcoursescategory) {
                array_push($coursestoreturn['regular_courses'], $course);
            } else {
                array_push($coursestoreturn['non_regular_courses'], $course);
            }
        }

        array_sort_by($coursestoreturn['regular_courses'], 'coursetimecreated', $order = SORT_DESC);
        array_sort_by($coursestoreturn['non_regular_courses'], 'coursetimecreated', $order = SORT_DESC);

        $regularcoursesgrouped = array();
        $counter = -1;
        $currentsemester = '';

        // Group regular courses by semesters.
        foreach ($coursestoreturn['regular_courses'] as $coursekey => $regularcourse) {

            // Current semester.
            $semestercode = explode('-', $regularcourse->courseshortname)[3];
            $semesterobject = $DB->get_record('iracv_academic_periods', array('code' => $semestercode));

            if ($semesterobject && $semesterobject->active) {
                array_push($regularcoursesgrouped, $regularcourse);
                unset($coursestoreturn['regular_courses'][$coursekey]);
            } else {
                // Example of semester: 202109061.
                $year = substr($semestercode, 0, 4);
                $month = substr($semestercode, 4, 2);

                $semestertag = get_string('semester', 'block_courselistuv') . ' ' . $year . ' ';
                $semesterkey = 'semester_' . $year . '_';

                if (intval($month) <= 6) {
                    $semestertag .= get_string('first_semester_of_year', 'block_courselistuv');
                    $semesterkey .= get_string('first_semester_of_year', 'block_courselistuv');
                } else {
                    $semestertag .= get_string('second_semester_of_year', 'block_courselistuv');
                    $semesterkey .= get_string('second_semester_of_year', 'block_courselistuv');
                }

                if ($semesterkey == $currentsemester) {
                    array_push($coursestoreturn['regular_courses']['past_semesters'][$counter]['courses'], $regularcourse);
                } else {
                    $coursestoreturn['regular_courses']['past_semesters'][$counter + 1] = array();

                    $coursestoreturn['regular_courses']['past_semesters'][$counter + 1]['tag'] = $semestertag;
                    $coursestoreturn['regular_courses']['past_semesters'][$counter + 1]['key'] = $semesterkey;
                    $coursestoreturn['regular_courses']['past_semesters'][$counter + 1]['courses'] = array();

                    array_push($coursestoreturn['regular_courses']['past_semesters'][$counter + 1]['courses'], $regularcourse);
                    $currentsemester = $semesterkey;
                    $counter++;
                }

                unset($coursestoreturn['regular_courses'][$coursekey]);
            }
        }

        $coursestoreturn['regular_courses']['current_semester'] = $regularcoursesgrouped;

        return $coursestoreturn;
    }

    /**
     * Get user id property.
     *
     * @return  int $userid
     */
    public function get_userid() {
        return $this->userid;
    }

    /**
     * Get courses array property.
     *
     * @return  array stdClass $courses
     */
    public function get_courses() {
        return $this->courses;
    }
}
