import { en } from '@formkit/i18n'
import { generateClasses } from '/node_modules/@formkit/themes'
import { genesisIcons } from '/node_modules/@formkit/icons'
import genesis from '/node_modules/@formkit/themes/dist/tailwindcss/genesis'

const config = {
  icons: {
    genesisIcons
  },
  classes: generateClasses(genesis),
  locales: { en },
  locale: 'en',
}

export default config