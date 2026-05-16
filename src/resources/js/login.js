// togglePassword function
document.addEventListener('DOMContentLoaded', function() {
    window.togglePass = function(inputId, offId, onId) {
        const input = document.getElementById(inputId)
        const off = document.getElementById(offId)
        const on = document.getElementById(onId)
        if (input.type === 'password') {
            input.type = 'text'
            off.classList.add('hidden')
            on.classList.remove('hidden')
        } else {
            input.type = 'password'
            off.classList.remove('hidden')
            on.classList.add('hidden')
        }
    }
})