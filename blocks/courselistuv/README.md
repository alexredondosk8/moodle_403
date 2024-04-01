# Moodle plugin: block_courselistuv #

## Motivation for this plugin
Course List UV is a customized Moodle plugin that renders the list of courses that a user has enrolled. This plugin was based on the block <i>block_course_list</i> that comes by default with Moodle, but tailored to the institution needs.

For regular users (i.e. without admin capabilities) the plugin groups courses in 3 lists:
- Current semester courses
- Past semesters courses
- Non-regular courses (current and past semesters combined)

For admin users the plugin renders all the root course categories and a link to all courses in the platform.

## Installation
Run the following commands under Moodle root directory:
1. Clone this repository to <i>/path/to/moodle/blocks/courselistuv</i>:
```
git clone https://github.com/desarrolloant/moodle-block_courselistuv.git blocks/courselistuv
```
2. Upgrade the Moodle site using Moodle CLI:
```
php admin/cli/upgrade
```

## Configuration
As admin you have to reset the dashboard for all users in the platform so they can visualize this block. To do this, go to /path/to/moodle/my/indexsys.php and follow the next steps:

1. Turn on Edit mode
2. Click on Add a block
3. Click on Course List UV
4. Once added and relocated to the needs, turn off Edit mode
5. Click on Reset Dashboard for all users
6. Disable the block <i>block_course_list</i> in /path/to/moodle/admin/blocks.php that is under the name of "Courses" by clicking on the eye to hide it

Note: the 5th step could take at least 1 hour to finish. You should not make any changes until the process is completed.

## Copyright
Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
