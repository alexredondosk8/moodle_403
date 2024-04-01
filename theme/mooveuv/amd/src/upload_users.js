/*
 * @module      theme_mooveuv/upload_users
 * @author      2023 Paul Rodrigo Rojas Guerrero <paul.rojas@correounivalle.edu.co> <PaulRodrigoRojasECL@gmail.com>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

export const init = () => {

    let urlLocation = window.location.pathname;

    if (urlLocation.includes('tool/uploaduser/index.php')) {
        $('#id_delimiter_name').find('option[value="comma"]').prop('selected', true);

        $('#id_uutype').find('option[value="2"]').prop('selected', true);


        // Activate     components manually

        let components = [
                        'fitem_id_uuupdatetype', 'id_uuupdatetype_label', 'id_uuupdatetype',
                        'fitem_id_uumatchemail', 'id_uumatchemail_label', 'id_uumatchemail',
                        'fitem_id_uunoemailduplicates', 'id_uunoemailduplicates_label', 'id_uunoemailduplicates',
                        'fitem_id_uuallowrenames', 'id_uuallowrenames_label', 'id_uuallowrenames',
                        'fitem_id_uuallowdeletes', 'id_uuallowdeletes_label', 'id_uuallowdeletes',
                        'fitem_id_uuallowsuspends', 'id_uuallowsuspends_label', 'id_uuallowsuspends'
                        ];

        components.forEach(e=>{
            let componentSelector = `#${e}`;
            $(componentSelector).attr('style', '');
            $(componentSelector).removeAttr('hidden');
            $(componentSelector).removeAttr('disabled');
        });

        $('#id_uuupdatetype').find('option[value="3"]').prop('selected', true);

        $('#id_uunoemailduplicates').find('option[value="0"]').prop('selected', true);


        // Results Upload Users Process Table Responsiveness

        let responsiveDivTable = $('<div>', {
            class: 'table-responsive',
        });

        let currentTable = $('#uuresults');

        $('#uuresults').remove();

        responsiveDivTable.append(currentTable);

        let uploadSummary = $('#uploadresults');

        responsiveDivTable.insertBefore(uploadSummary);

    }

};