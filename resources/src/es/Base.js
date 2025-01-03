import $ from 'jquery'
import Component from 'Component'
import {
  getPluginAPI,
  pluginFactory
} from 'Plugin'

export default class extends Component {
  initializePlugins(context = false) {
    $('[data-plugin]', context || this.$el).each(function () {
      let $this = $(this),
        name = $this.data('plugin'),
        plugin = pluginFactory(name, $this, $this.data())

      if (plugin) {
        plugin.initialize()
      }
    })
  }

  initializePluginAPIs(context = document) {
    const apis = getPluginAPI()

    for (const name in apis) {
      getPluginAPI(name)(`[data-plugin=${name}]`, context)
    }
  }
}
