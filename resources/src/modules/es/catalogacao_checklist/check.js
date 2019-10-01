import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

// Toggle border on check
(function() {
  $('input[type=radio]').change(function() {
    if (this.value == 'true') {
      $(this).parent().parent().addClass('border-success');
      $(this).parent().parent().removeClass('border-danger');
    }
    else if (this.value == 'false') {
      $(this).parent().parent().removeClass('border-success');
      $(this).parent().parent().addClass('border-danger');
    }
});
})();
