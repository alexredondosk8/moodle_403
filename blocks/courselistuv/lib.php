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
 * Course List UV block lib.
 *
 * @package     theme_mooveuv
 * @author      2022 Iader E. García Gómez <iadergg@gmail.com>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Sort an array based on one of its attributes.
 *
 * @param       stdClass array $initialarray
 * @param       string $col
 * @param       string $order Order type. ASC default.
 * @author      2022 Iader E. Garcia Gomez <iadergg@gmail.com>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 */
function array_sort_by(&$initialarray, $col, $order = SORT_ASC) {

    $auxarray = array();

    foreach ($initialarray as $key => $row) {
        $auxarray[$key] = is_object($row) ? $auxarray[$key] = $row->$col : $row[$col];
        $auxarray[$key] = strtolower($auxarray[$key]);
    }

    array_multisort($auxarray, $order, $initialarray);
}
