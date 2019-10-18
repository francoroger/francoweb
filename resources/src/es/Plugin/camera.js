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
        '[data-action="image-upload"]',
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

    let w = $(this.$preview).width()
    let h = $(this.$preview).height()

    this.$cam.width(w)
    this.$cam.height(h)
    this.$cam.addClass('d-none')

    $el.data('cameraAPI', this)
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

  upload() {
    console.log('upload')
  }

}

Plugin.register(NAME, Camera)

export default Camera
