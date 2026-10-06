/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{vue,js}'],
  theme: {
    extend: {
      colors: {
        paper: '#EDE7D9',
        paperdark: '#E1D9C6',
        card: '#FBF8F1',
        ink: '#1F2A44',
        inksoft: '#4A5578',
        muted: '#8A8371',
        amber: {
          DEFAULT: '#E8A33D',
          soft: '#F3C578',
          deep: '#C97F1F',
        },
        leaf: '#4C7A5D',
        rust: '#B4472A',
      },
      fontFamily: {
        mono: ['"IBM Plex Mono"', 'ui-monospace', 'monospace'],
        sans: ['"Inter"', 'ui-sans-serif', 'system-ui'],
      },
      boxShadow: {
        ticket: '0 1px 0 rgba(31,42,68,0.06), 0 8px 20px -8px rgba(31,42,68,0.18)',
      },
      borderRadius: {
        ticket: '10px',
      },
    },
  },
  plugins: [],
}
