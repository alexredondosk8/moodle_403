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
 * Provides unit tests for user course manager class IRACV.
 *
 * @package     block_courselistuv
 * @category    PHPUnit
 * @author      2022 Iader E. García Gómez <iadergg@gmail.com>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_courselistuv;

/**
 * Unit tests for user_course_manager class.
 *
 * @package     block_courselistuv
 * @author      2022 Iader E. García Gómez <iadergg@gmail.com>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user_course_manager_test extends \advanced_testcase {

    /**
     * Test get courses.
     *
     * @covers ::get_user_courses
     */
    public function test_get_courses() {

        $this->resetAfterTest(true);

        // Creating users.
        $user1 = $this->getDataGenerator()->create_user();
        $user2 = $this->getDataGenerator()->create_user();

        // Creating semesters.
        $testgenerator = $this->getDataGenerator()->get_plugin_generator('block_courselistuv');
        $semester1 = $testgenerator->create_semester(array('name' => 'SEPTIEMBRE/2021- MARZO/2022',
                                                                        'code' => '202109061',
                                                                        'timestart' => 1632978000,
                                                                        'timecompletion' => 1646370000,
                                                                        'active' => 0));

        $semester2 = $testgenerator->create_semester(array('name' => 'ABRIL/2022 - AGOSTO/2022',
                                                                        'code' => '202204041',
                                                                        'timestart' => 1649048400,
                                                                        'timecompletion' => 1659675600,
                                                                        'active' => 1));

        $semester3 = $testgenerator->create_semester(array('name' => 'AGOSTO/2020 - FEBRERO/2021',
                                                                        'code' => '202008061',
                                                                        'timestart' => 1596258000,
                                                                        'timecompletion' => 1614488400,
                                                                        'active' => 0));

        // Creating categories.
        $category = $this->getDataGenerator()->create_category(array("idnumber" => 'regular_courses'));

        // Creating courses.
        // Regular courses.
        $course = $this->getDataGenerator()->create_course(array("shortname" => '00-701002C-06-202204041',
                                                                "category" => $category->id));

        $this->getDataGenerator()->enrol_user($user1->id, $course->id);

        $course = $this->getDataGenerator()->create_course(array("shortname" => '00-801002C-06-202109061',
                                                                "category" => $category->id));

        $this->getDataGenerator()->enrol_user($user1->id, $course->id);

        $course = $this->getDataGenerator()->create_course(array("shortname" => '00-901002C-06-202008061',
                                                                "category" => $category->id));

        $this->getDataGenerator()->enrol_user($user1->id, $course->id);

        // Non-regular courses.
        $course = $this->getDataGenerator()->create_course();
        $this->getDataGenerator()->enrol_user($user1->id, $course->id);
        $course = $this->getDataGenerator()->create_course();
        $this->getDataGenerator()->enrol_user($user1->id, $course->id);

        // Courses not visible.
        $course = $this->getDataGenerator()->create_course(array("visible" => 0));
        $this->getDataGenerator()->enrol_user($user1->id, $course->id);
        $course = $this->getDataGenerator()->create_course(array("visible" => 0));
        $this->getDataGenerator()->enrol_user($user1->id, $course->id);

        $usercoursemanager = new user_course_manager($user1->id);

        $courses = $usercoursemanager->get_courses();

        $this->assertInstanceOf(user_course_manager::class, $usercoursemanager);
        $this->assertSame((int)$user1->id, $usercoursemanager->get_userid());

        // Type of return values.
        $this->assertIsArray($courses);
        $this->assertIsArray($courses['regular_courses']);
        $this->assertIsArray($courses['non_regular_courses']);
        $this->assertIsArray($courses['regular_courses']['current_semester']);

        // Two course groups: Regular and non-regular courses.
        $this->assertSame(2, count($courses));

        // Test counter non-regular courses.
        $this->assertSame(2, count($courses['non_regular_courses']));

        // Users without courses.
        $usercoursemanager = new user_course_manager($user2->id);
        $courses = $usercoursemanager->get_courses();

        $this->assertIsArray($courses);
        $this->assertIsArray($courses['regular_courses']);
        $this->assertIsArray($courses['non_regular_courses']);
        $this->assertIsArray($courses['regular_courses']['current_semester']);
    }
}
