/*
 * @module      theme_mooveuv/edit_styles
 * @author      2023 Paul Rodrigo Rojas Guerrero <paul.rojas@correounivalle.edu.co> <PaulRodrigoRojasECL@gmail.com>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

export const init = () => {

    let urlLocation = window.location.pathname;
    let urlSplit = urlLocation.split("/");

    if (urlSplit.includes('course')) {
        if (urlSplit[urlSplit.length - 1].includes('edit.php')) {
            // Url pattern matches create and editing course form page

            $('#id_fullname').css('width', '75%');
            $('#id_shortname').css('width', '75%');
            $('#id_idnumber').css('width', '75%');
        } else if (urlSplit.includes('search.php')) {
            // Url pattern matches search course form page

            $('.input-group').css('width', '100%');
        } else if (urlSplit.includes('index.php') || urlSplit[urlSplit.length - 1].includes('management.php')) {
            // Url pattern matches index course form page

            let searchNavItem = $('div.navitem:has([id^="searchinput-"])');
            $('div.navitem:has([id^="searchinput-"])').css('flex', 1);
            searchNavItem.find('div.simplesearchform').first().css('width', '100%');
            searchNavItem.find('div.input-group').first().css('width', '100%');
        }
    }
};
