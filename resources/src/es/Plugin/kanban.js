import Plugin from 'Plugin'

const NAME = 'kanban'

class Kanban extends Plugin {
  getName() {
    return NAME
  }

  static getDefaults() {
    return {
      group: 'shared',
      animation: 150,
      selectedClass: 'border-danger',
	    fallbackTolerance: 3,
      onEnd: (evt) => {
        doChangeEvent(evt)
      }
    }
  }

  render() {
    const $el = this.$el

    const $kb = Sortable.create(this.$el.get(0), this.options)
  }
}

Plugin.register(NAME, Kanban)

export default Kanban
