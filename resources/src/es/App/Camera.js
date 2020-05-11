import BaseApp from 'BaseApp'

class AppCamera extends BaseApp {
  initialize() {
    super.initialize()

    this.$turnOnBtn = $('.enable-cam')
    this.$takeSnapshotBtn = $('.capture-img')
    this.$cam = '.image-wrap'
    this.$preview = $('.preview-img')
  }
  process() {
    super.process()

    this.handleWebcam()
    this.setupControls()
  }

  handleWebcam() {
    const w = $(this.$preview).width()
    const h = $(this.$preview).height()

    const camOptions = {
      width: w,
      height: h,
      dest_width: 640,
      dest_height: 480,
      image_format: 'jpeg',
      jpeg_quality: 90,
      force_flash: false
    }

    Webcam.set(camOptions)
  }

  setupControls() {
    const self = this

    this.$turnOnBtn.on('click', (e) => {
      this.$preview.addClass('d-none')
      $(this.$cam).removeClass('d-none')
      Webcam.attach(this.$cam)
    })

    this.$takeSnapshotBtn.on('click', (e) => {
      Webcam.snap((data_uri) => {
        this.$preview.removeClass('d-none')
        $(self.$cam).addClass('d-none')
        self.$preview.attr('src', data_uri)
        Webcam.reset()
      })
    })
  }

}

let instance = null

function getInstance() {
  if (!instance) {
    instance = new AppCamera()
  }
  return instance
}

function run() {
  const app = getInstance()
  app.run()
}

export default AppCamera
export {
  AppCamera,
  run,
  getInstance
}
