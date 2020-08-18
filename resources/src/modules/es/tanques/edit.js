import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

(function() {
  $(document).ready(function() {
    $('#desconto_milesimo').mask('#.##0,00', {reverse: true});
  });
})();
