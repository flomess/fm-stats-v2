let isLoaned

document.addEventListener('DOMContentLoaded', () => {

    let isLoanedSelect = document.querySelector('input.isLoaned')

    isLoaned = true

    isLoanedSelect.onchange = (e) => {
        if (isLoaned === true) {
            isLoaned = false
            document.querySelector('.loan-inputs').classList.remove('hidden')
            document.querySelector('.loan-inputs').setAttribute('required', '')
            document.querySelector('.half-season').classList.remove('hidden')
            document.querySelector('.half-season').setAttribute('required', '')
            document.querySelector('.stats-fields').classList.add('hidden')
            document.querySelector('.stats-fields').removeAttribute('required')
        } else {
            isLoaned = true
            document.querySelector('.stats-fields').classList.remove('hidden')
            document.querySelector('.stats-fields').setAttribute('required', '')
            document.querySelector('.loan-inputs').classList.add('hidden')
            document.querySelector('.loan-inputs').removeAttribute('required')
            document.querySelector('.half-season').classList.add('hidden')
            document.querySelector('.half-season').removeAttribute('required')
        }
    }
})