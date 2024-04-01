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

use block_courselistuv\user_course_manager;

/**
 * Course List UV block renderer.
 *
 * @package     block_courselistuv
 * @author      2022 Brayan Sanchez <brayan.sanchez.leon@correounivalle.edu.co>
 * @author      2022 Iader E. Garcia Gomez <iadergg@gmail.com>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_courselistuv_renderer extends plugin_renderer_base {

    /**
     * Function that returns the HTML template based on the user ID.
     *
     * @param   int $userid
     * @return  string $template
     * @author  2022 Brayan Sanchez <brayan.sanchez.leon@correounivalle.edu.co>
     */
    public function get_html($userid) {

        global $USER, $DB, $CFG;

        $data = new stdClass();

        $isadmin = has_capability('moodle/site:config', context_system::instance());

        if ($isadmin && $userid == $USER->id) {

            $data->course_categories = array();
            $coursecategories = $DB->get_records('course_categories', array('parent' => 0), 'sortorder');

            foreach ($coursecategories as $category) {
                $categorytolist = new stdClass();
                $categorytolist->category_id = $category->id;
                $categorytolist->url_to_category = new moodle_url("/course/index.php", array('categoryid' => $category->id));
                $categorytolist->category_name = $category->name;
                array_push($data->course_categories, $categorytolist);
            }

            $data->url_to_all_courses = new moodle_url("/course/index.php");
            $template = $this->render_from_template('block_courselistuv/courselistuv_admin', $data);
        } else {

            $usercoursemanager = new user_course_manager($userid);
            $usercourses = $usercoursemanager->get_courses();
            $data->regular_courses = $usercourses['regular_courses'];
            $data->non_regular_courses = $usercourses['non_regular_courses'];
            $data->wwwroot = $CFG->wwwroot;
            $template = $this->render_from_template('block_courselistuv/courselistuv_regular_user', $data);
        }

        return $template;
    }
}
