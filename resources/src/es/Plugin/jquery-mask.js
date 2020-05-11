import $ from 'jquery'
import Plugin from 'Plugin'

const NAME = 'mask'

class Mask extends Plugin {
  getName() {
    return NAME
  }

  static getDefaults() {
    return {}
  }

  render() {
    if (!$.fn.mask) {
      return
    }

    let $el = this.$el,
      options = this.options

    if (this.options.type === 'cellphone') {

      let maskBehavior = function (val) {
          return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009'
        },
        maskOptions = {
          onKeyPress(val, e, field, options) {
            field.mask(maskBehavior.apply({}, arguments), options)
          }
        }

      $el.mask(maskBehavior, maskOptions)
    } else {
      $el.mask(options.pattern)
    }
  }
}

Plugin.register(NAME, Mask)

export default Mask
