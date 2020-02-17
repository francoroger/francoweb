import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();

  var $camera1 = $('#capture_cam');
  var CameraClass1 = new Plugin.getPlugin('camera');
  var api1 = new CameraClass1($camera1, $camera1.data());
  api1.render();

  $('#btn-capturar').on('click', function() {
    api1.upload(function(fname, fpath) {
      $('#capturaModal').modal('hide')

      let elem = '<li>\
        <div class="panel">\
          <figure class="overlay overlay-hover animation-hover">\
            <img class="caption-figure overlay-figure" src="'+fpath+'">\
            <figcaption class="overlay-panel overlay-background overlay-fade text-center vertical-align">\
              <div class="btn-group">\
                <button type="button" class="btn btn-icon btn-pure btn-default" title="Excluir" data-tag="project-delete">\
                  <i class="icon wb-trash" aria-hidden="true"></i>\
                </button>\
              </div>\
            </figcaption>\
          </figure>\
        </div>\
      </li>';

      $('#foto-container').append(elem)

    });

  });

  $('#capturaModal').on('shown.bs.modal', function (e) {
    api1.turnOn()
  })

  $('#capturaModal').on('hide.bs.modal', function (e) {
    api1.turnOff()
  })

});
