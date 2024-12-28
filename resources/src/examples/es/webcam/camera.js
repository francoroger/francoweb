import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();

  var $camera1 = $('#exampleCamera1');
  var CameraClass1 = new Plugin.getPlugin('camera');
  var api1 = new CameraClass1($camera1, $camera1.data());
  api1.render();

});
