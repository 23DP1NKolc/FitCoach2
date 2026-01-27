import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import '@mdi/font/css/materialdesignicons.css'

const light = {
  dark: false,
  colors: {
    background: '#F6F7FB',
    surface: '#FFFFFF',
    primary: '#6D28D9',      // violets
    secondary: '#06B6D4',    // cyan
    accent: '#F97316',       // orange
    error: '#EF4444',
    info: '#0EA5E9',
    success: '#22C55E',
    warning: '#F59E0B',
  },
}

const dark = {
  dark: true,
  colors: {
    background: '#0B1020',
    surface: '#121A2F',
    primary: '#8B5CF6',
    secondary: '#22D3EE',
    accent: '#FB923C',
    error: '#F87171',
    info: '#38BDF8',
    success: '#4ADE80',
    warning: '#FBBF24',
  },
}

export default createVuetify({
  theme: {
    defaultTheme: 'light',
    themes: { light, dark },
  },
  icons: { defaultSet: 'mdi' },
})
