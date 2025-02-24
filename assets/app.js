import $ from 'jquery';
import 'bootstrap';
import 'datatables.net-bs5';
import './app.scss';

$(document).ready(function() {
    $('#datatable').DataTable({
        lengthMenu: [[5, 10, 15, 20, -1], [5, 10, 15, 20, 'Toutes']],
        scrollY: '45vh',
        scrollX: true,
        scrollCollapse: true,
        language: {
            lengthMenu: 'Afficher _MENU_ lignes',
            zeroRecords: 'Rien trouvé - désolé',
            info: 'Nombre total de pages _PAGE_ sur _PAGES_',
            infoEmpty: 'Aucun nombre de pages disponible',
            infoFiltered: '(filtré de _MAX_ nombre total de pages)',
            search: 'Rechercher :',
            paginate: {
                next: 'Suivant',
                previous: 'Précédent',
            },
        },
    });
});

