import BaseApp from 'BaseApp'

class AppCatalogacaoChecklist extends BaseApp {
  initialize() {
    super.initialize()

    this.$arrGrid = $('#arrangement-grid')
    this.$arrList = $('#arrangement-list')
    this.$content = $('#mediaContent')
    this.$fileupload = $('#fileupload')

    // states
    this.states = {
      list: false,
      checked: false
    }
  }
  process() {
    super.process()

    this.steupArrangement()
    this.bindListChecked()
    this.bindAction()
    this.bindDropdownAction()
  }

  list(active) {
    if (active) {
      this.$arrGrid.removeClass('active')
      this.$arrList.addClass('active')
      $('.media-list').removeClass('is-grid').addClass('is-list')
      $('.media-list>ul>li').removeClass('animation-scale-up').addClass('animation-fade')
    } else {
      this.$arrList.removeClass('active')
      this.$arrGrid.addClass('active')
      $('.media-list').removeClass('is-list').addClass('is-grid')
      $('.media-list>ul>li').removeClass('animation-fade').addClass('animation-scale-up')
    }

    this.states.list = active
  }

  checked(checked) {
    this.states.checked = checked
  }

  steupArrangement() {
    const self = this
    this.$arrGrid.on('click', function () {
      if ($(this).hasClass('active')) {
        return
      }

      self.list(false)
    })
    this.$arrList.on('click', function () {
      if ($(this).hasClass('active')) {
        return
      }

      self.list(true)
    })
  }

  bindListChecked() {
    this.$content.on('asSelectable::change', (e, api, checked) => {
      this.checked(checked)
    })
  }

  bindDropdownAction() {
    $('.info-wrap>.dropdown').on('show.bs.dropdown', function () {
      $(this).closest('.media-item').toggleClass('item-active')
    }).on('hidden.bs.dropdown', function () {
      $(this).closest('.media-item').toggleClass('item-active')
    })

    $('.info-wrap .dropdown-menu').on('`click', (e) => {
      e.stopPropagation()
    })
  }

  bindAction() {
    $('[data-action="trash"]', '.site-action').on('click', () => {
      console.log('trash')
    })

    $('[data-action="download"]', '.site-action').on('click', () => {
      console.log('download')
    })
  }
}

let instance = null

function getInstance() {
  if (!instance) {
    instance = new AppCatalogacaoChecklist()
  }
  return instance
}

function run() {
  const app = getInstance()
  app.run()
}

export default AppCatalogacaoChecklist
export {
  AppCatalogacaoChecklist,
  run,
  getInstance
}
