/*
 * @module      theme_mooveuv/modal_login
 * @author      2022 Iader E. Garcia Gomez <iadergg@gmail.com>
 * @copyright   2022 Área de Nuevas Tecnologías - DINTEV - Universidad del Valle <desarrollo.ant@correounivalle.edu.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

import ajax from 'core/ajax';

export const init = () => {

    var urlLocation = window.location.pathname;
    var urlSplit = urlLocation.split("/");
    if (urlSplit.includes('questionnaire')) {
        var locationSearch = window.location.search;

        const result = ajax.call([{
            methodname: 'theme_mooveuv_get_questionnaries',
            args: {}
        }])[0];

        result.then((response) => {
            var arrayQuestionnaires = response.value;
            var parameter = locationSearch.split("=")[1];
            if (urlSplit.includes('view.php')) {

                if (arrayQuestionnaires.includes(parameter)) {
                    $('.complete > a').html("Participe del proceso de votación");
                }
            } else if (urlSplit.includes('complete.php') || urlSplit.includes('preview.php')) {
                if (arrayQuestionnaires.includes(parameter)) {
                    $('input[name=submit]').val("Votar");
                    $('.floatprinticon').hide();
                    $('.mod_questionnaire_completepage generalbox>h3').html('Gracias por participar en el proceso de votación.');
                    $('.mod_questionnaire_previewpage>h2').html('Previsualizando');
                }
            }

            return [result];
        }).catch((e) => {
            window.console.log(e);
        });
    } else if (urlLocation.includes('user') && urlLocation.includes('edit')) {
        $('#id_department_label').empty();
        $('#id_department_label').append('Departamento');
    } else if (urlLocation.includes('admin') && urlLocation.includes('user')) {
        $('.mform').attr("autocomplete", "on");
        $('#id_realname').attr("autocomplete", "on");

    }
};