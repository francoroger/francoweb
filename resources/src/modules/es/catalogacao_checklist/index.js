import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

// Tabela Check List Catalogação
// ---------------------------
(function() {
  $(document).ready(function() {
    var defaults = Plugin.getDefaults("dataTable");

    var options = $.extend(true, {}, defaults, {
      columns: [
        { data: 'id' },
        { data: 'cliente' },
        { data: 'datacad' },
        { data: 'horacad' },
        { data: 'status', sClass: "text-center" },
        { data: 'actions', sClass: "text-center", orderable: false, searchable: false }
      ],
      order: [[ 0, 'desc' ]],
      pageLength: 50,
    });

    $('#catalogacao-checklist-table').DataTable(options);
  });
})();
