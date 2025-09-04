/** @type {import('tailwindcss').Config} */
export default {
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
    theme: {
        fontFamily: {
            sans: ['Outfit', 'sans-serif']
        },
        extend: {
            boxShadow: {
                sidebar: '0 25px 50px -12px rgb(0 0 0 / 0.25)'
            },
            colors: {
                lime: {
                    DEFAULT: '#F4FFC3',
                    100: '#FBFFE5',
                    200: '#F8FFCC',
                    300: '#F4FFC3', // Primary color
                    400: '#D9FF94',
                    500: '#C3FF66',
                    600: '#A8D94D',
                    700: '#8EB336',
                    800: '#748C20',
                    900: '#5A6611'
                },
                tosca: {
                    DEFAULT: '#0395AF',
                    100: '#CCF5FB',
                    200: '#99EBF7',
                    300: '#66E2F2',
                    400: '#33D8EE',
                    500: '#0395AF', // Primary color
                    600: '#02788C',
                    700: '#015B69',
                    800: '#003E45',
                    900: '#002223'
                },
                orange: {
                    DEFAULT: '#F57313',
                    100: '#FFD7B3',
                    200: '#FFBF80',
                    300: '#FFA74D',
                    400: '#FF8F1A',
                    500: '#F57313', // Primary color
                    600: '#C45D0F',
                    700: '#93460B',
                    800: '#623006',
                    900: '#311803'
                },
                yellow: {
                    DEFAULT: '#F2D394',
                    100: '#FFF9E7',
                    200: '#FFEDC3',
                    300: '#FFE09E',
                    400: '#FFD479',
                    500: '#F2D394', // Primary color
                    600: '#D4B47A',
                    700: '#B49661',
                    800: '#937749',
                    900: '#735830'
                },
                white: {
                    DEFAULT: '#FFFFFF',
                    100: '#FFFFFF', // White remains the same at all levels
                    200: '#FFFFFF',
                    300: '#FFFFFF',
                    400: '#FFFFFF',
                    500: '#FFFFFF', // Primary color
                    600: '#F2F2F2',
                    700: '#E5E5E5',
                    800: '#CCCCCC',
                    900: '#B3B3B3'
                },
                navy: {
                    DEFAULT: '#21568A',
                    100: '#C4D5E7',
                    200: '#A8BED8',
                    300: '#8DA7CA',
                    400: '#7191BC',
                    500: '#21568A', // Primary color
                    600: '#1A446D',
                    700: '#133250',
                    800: '#0C2033',
                    900: '#060F16'
                }
            }
        }
    },
    plugins: []
}
