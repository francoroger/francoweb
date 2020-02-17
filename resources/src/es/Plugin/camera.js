import $ from 'jquery'
import Plugin from 'Plugin'

const NAME = 'camera'

function getCameraAPI($el) {
  if ($el.length <= 0) {
    return
  }
  let api = $el.data('cameraAPI')

  if (api) {
    return api
  }

  api = new Camera($el, $.extend(true, {}, Camera.getDefaults(), $el.data()))
  api.render()
  return api
}

class Camera extends Plugin {
  getName() {
    return
  }

  static getDefaults() {
    return {}
  }

  static api() {
    return () => {
      $(document).on(
        'click',
        '[data-action="camera-toggle-enable"]',
        function (e) {
          e.preventDefault()
          const api = getCameraAPI($(this).closest('.panel'))
          api.toggleEnable()
        }
      )

      $(document).on(
        'click',
        '[data-action="camera-toggle-freeze"]',
        function (e) {
          e.preventDefault()
          const api = getCameraAPI($(this).closest('.panel'))
          api.toggleFreeze()
        }
      )

      $(document).on(
        'click',
        '[data-action="camera-on"]',
        function (e) {
          e.preventDefault()
          const api = getCameraAPI($(this).closest('.panel'))
          api.turnOn()
        }
      )

      $(document).on(
        'click',
        '[data-action="camera-off"]',
        function (e) {
          e.preventDefault()
          const api = getCameraAPI($(this).closest('.panel'))
          api.turnOff()
        }
      )

      $(document).on(
        'click',
        '[data-action="camera-freeze"]',
        function (e) {
          e.preventDefault()
          const api = getCameraAPI($(this).closest('.panel'))
          api.freeze()
        }
      )

      $(document).on(
        'click',
        '[data-action="camera-unfreeze"]',
        function (e) {
          e.preventDefault()
          const api = getCameraAPI($(this).closest('.panel'))
          api.unfreeze()
        }
      )

      $(document).on(
        'click',
        '[data-action="camera-snapshot"]',
        function (e) {
          e.preventDefault()
          const api = getCameraAPI($(this).closest('.panel'))
          api.takeSnapshot()
        }
      )

      $(document).on(
        'click',
        '[data-action="camera-upload"]',
        function (e) {
          e.preventDefault()
          const api = getCameraAPI($(this).closest('.panel'))
          api.upload()
        }
      )

    }
  }

  render(context) {
    const $el = this.$el

    this.isEnabled = false
    this.isFreezed = false

    this.$preview = $el.find('.preview-img')
    this.$cam = $el.find('.live-cam')
    this.$indicator = $el.find('[data-action="camera-toggle-enable"]')
    this.$background = $el.find('.overlay-background')
    this.$ajax_url = $el.data('url')
    this.$token = $el.data('token')
    this.$form_elem_name = 'snapshot'
    this.$uploaded_file_input = $el.find('input[name="uploaded-file"]')
    this.$filename = '';
    this.$filepath = '';

    let w = $(this.$preview).width()
    let h = $(this.$preview).height()

    this.$cam.width(w)
    this.$cam.height(h)
    this.$cam.addClass('d-none')

    $el.data('cameraAPI', this)

    //Webcam events
    Webcam.on('uploadProgress', (progress) => {
      // Upload in progress
      // 'progress' will be between 0.0 and 1.0
    })

    Webcam.on('uploadComplete', (code, text, status, callback) => {
      if (code == 200) {
        let json = JSON.parse(text)
        this.$uploaded_file_input.val(json.filename)
        this.$preview.attr('src', json.path)
        this.$background.addClass('d-none')
        this.turnOff()
        if (callback) callback.apply( self, [json.filename, json.path] );
      }
    })

  }

  toggleEnable() {
    if (this.isEnabled) {
      this.turnOff()
    } else {
      this.turnOn()
    }
  }

  toggleFreeze() {
    if (this.isFreezed) {
      this.unfreeze()
    } else {
      this.freeze()
    }
  }

  turnOn() {
    if (this.isEnabled !== true) {
      //Força a definição de tamanho
      let w = $(this.$preview).width()
      let h = $(this.$preview).height()
      //Limpa atributos de resultado de captura
      this.$filename = '';
      this.$filepath = '';

      this.$cam.width(w)
      this.$cam.height(h)

      this.$preview.addClass('d-none')
      this.$cam.removeClass('d-none')
      this.$indicator.addClass('active')
      this.$indicator.addClass('focus')

      Webcam.attach(this.$cam[0])

      this.isEnabled = true
    }
  }

  turnOff() {
    if (this.isEnabled !== false) {
      this.$preview.removeClass('d-none')
      this.$cam.addClass('d-none')
      this.$indicator.removeClass('active')
      this.$indicator.removeClass('focus')

      Webcam.reset()

      this.isEnabled = false
    }
  }

  takeSnapshot() {
    if (this.isEnabled === true) {
      Webcam.snap((data_uri) => {
        this.$preview.attr('src', data_uri)
        this.$background.addClass('d-none')
        this.turnOff()
      })
    }
  }

  freeze() {
    if (this.isEnabled === true && this.isFreezed !== true) {
      Webcam.freeze()
      this.isFreezed = true
    }
  }

  unfreeze() {
    if (this.isEnabled === true && this.isFreezed === true) {
      Webcam.unfreeze()
      this.isFreezed = false
    }
  }

  upload(callback) {
    if (this.isEnabled === true && this.$ajax_url) {
      Webcam.snap((data_uri) => {
        // detect image format from within image_data_uri
    		let image_fmt = ''
    		if (data_uri.match(/^data\:image\/(\w+)/))
    			image_fmt = RegExp.$1
    		else
    			throw "Cannot locate image format in Data URI"

        // contruct use AJAX object
    		let http = new XMLHttpRequest()
    		http.open("POST", this.$ajax_url, true)
        http.setRequestHeader('X-CSRF-TOKEN', this.$token)

    		// setup progress events
    		if (http.upload && http.upload.addEventListener) {
    			http.upload.addEventListener( 'progress', (e) => {
    				if (e.lengthComputable) {
    					let progress = e.loaded / e.total
    					Webcam.dispatch('uploadProgress', progress, e)
    				}
    			}, false )
    		}

    		// completion handler
    		http.onload = () => {
    			Webcam.dispatch('uploadComplete', http.status, http.responseText, http.statusText, callback)
    		}

        // extract raw base64 data from Data URI
      	let raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '')

        // create a blob and decode our base64 to binary
    		let blob = new Blob( [ this._base64DecToArr(raw_image_data) ], {type: 'image/'+image_fmt} )

    		// stuff into a form, so servers can easily receive it as a standard file upload
    		let form = new FormData()
    		form.append(this.$form_elem_name, blob, this.$form_elem_name+"."+image_fmt.replace(/e/, '') )
        form.append('data_uri', data_uri)
        form.append('image_fmt', image_fmt)

    		// send data to server
    		http.send(form)
      })
    }
  }

  _b64ToUint6(nChr) {
		// convert base64 encoded character to 6-bit integer
		// from: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Base64_encoding_and_decoding
		return nChr > 64 && nChr < 91 ? nChr - 65
			: nChr > 96 && nChr < 123 ? nChr - 71
			: nChr > 47 && nChr < 58 ? nChr + 4
			: nChr === 43 ? 62 : nChr === 47 ? 63 : 0
	}

	_base64DecToArr(sBase64, nBlocksSize) {
		// convert base64 encoded string to Uintarray
		// from: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Base64_encoding_and_decoding
		let sB64Enc = sBase64.replace(/[^A-Za-z0-9\+\/]/g, ""), nInLen = sB64Enc.length,
			nOutLen = nBlocksSize ? Math.ceil((nInLen * 3 + 1 >> 2) / nBlocksSize) * nBlocksSize : nInLen * 3 + 1 >> 2,
			taBytes = new Uint8Array(nOutLen)

		for (var nMod3, nMod4, nUint24 = 0, nOutIdx = 0, nInIdx = 0; nInIdx < nInLen; nInIdx++) {
			nMod4 = nInIdx & 3
			nUint24 |= this._b64ToUint6(sB64Enc.charCodeAt(nInIdx)) << 18 - 6 * nMod4
			if (nMod4 === 3 || nInLen - nInIdx === 1) {
				for (nMod3 = 0; nMod3 < 3 && nOutIdx < nOutLen; nMod3++, nOutIdx++) {
					taBytes[nOutIdx] = nUint24 >>> (16 >>> nMod3 & 24) & 255
				}
				nUint24 = 0
			}
		}
		return taBytes
	}

}

Plugin.register(NAME, Camera)

export default Camera
