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
 * Course List UV block implementation.
 *
 * @package     block_courselistuv
 * @author      2022 Brayan Sanchez <brayan.sanchez.leon@correounivalle.edu.co>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_courselistuv extends block_base {

    /**
     * Initialize the block.
     *
     * @author  2022 Brayan Sanchez <brayan.sanchez.leon@correounivalle.edu.co>
     */
    public function init() {

        $isadmin = has_capability('moodle/site:config', context_system::instance());
        if ($isadmin) {
            $this->title = get_string('title_course_categories', 'block_courselistuv');
        } else {
            $this->title = get_string('title_my_courses', 'block_courselistuv');
        }
    }

    /**
     * Generates the content for the block.
     *
     * @return  stdObject
     * @author  2022 Brayan Sanchez <brayan.sanchez.leon@correounivalle.edu.co>
     */
    public function get_content() {
        global $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $renderer = $this->page->get_renderer('block_courselistuv');
        $this->content = new stdClass();
        $this->content->text = $renderer->get_html($USER->id);

        return $this->content;
    }

    /**
     * Adds multiple blocks of this type to a single course.
     *
     * @return  boolean
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Enables global configuration for the block.
     *
     * @return  boolean
     */
    public function has_config() {
        return true;
    }
}
