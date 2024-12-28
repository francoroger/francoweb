import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

//Force tipopessoa change
(function() {
  $(document).ready(function() {
    $('#add-data').mask('00/00/0000 00:00');
    $('#add-valorg').mask('#.##0,00', {reverse: true});
  });
})();
