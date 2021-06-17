import Echo from 'laravel-echo'
window._ = require('lodash')
window.io = require('socket.io-client')

try {
    window.Popper = require('popper.js').default
    window.$ = window.jQuery = require('jquery')

    require('bootstrap')
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.withCredentials = true

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
})
