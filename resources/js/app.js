import './bootstrap'

document.querySelectorAll('.link-tab').forEach((tab) => {
    tab.addEventListener('click', function (e) {
        e.preventDefault()

        // Remove active class from all tabs and hide all content
        document.querySelectorAll('.link-tab').forEach((t) => t.classList.remove('bg-navy', 'text-white'))
        document.querySelectorAll('.link-tab').forEach((t) => t.classList.add('text-navy'))
        document.querySelectorAll('.tab-content').forEach((content) => content.classList.add('hidden'))

        // Add active class to the clicked tab and show its content
        this.classList.add('bg-navy', 'text-white')
        this.classList.remove('text-navy')
        const target = document.querySelector(this.getAttribute('href'))
        target.classList.remove('hidden')
    })
})
let clickCounter = 0
document.querySelector('#clickFooter').addEventListener('click', function (e) {
    if (clickCounter == 0) {
        res()
    }
    clickCounter++
    if (clickCounter == 3) window.location.href = '/login'
})
function res() {
    setTimeout(() => {
        clickCounter = 0
    }, 1200)
}

document.querySelectorAll('.link-tab').forEach((tab) => {
    tab.addEventListener('click', function (e) {
        e.preventDefault()

        // Remove active class from all tabs and hide all content
        document.querySelectorAll('.link-tab').forEach((t) => t.classList.remove('bg-white', 'text-navy'))
        document.querySelectorAll('.link-tab').forEach((t) => t.classList.add('text-white'))
        document.querySelectorAll('.tab-content').forEach((content) => content.classList.add('hidden'))

        // Add active class to the clicked tab and show its content
        this.classList.add('bg-white', 'text-navy')
        this.classList.remove('text-white')
        const target = document.querySelector(this.getAttribute('href'))
        target.classList.remove('hidden')
    })
})
