let typeTransfert

document.addEventListener('DOMContentLoaded', () => {

    let typeTransfertSelect = document.querySelector('select.typeTransfert')

    typeTransfert = typeTransfertSelect.value

    showTypeTransfertOptions(typeTransfert)
    typeTransfertSelect.onchange = (e) => {
        showTypeTransfertOptions(e.target.value)
    }
})

function showTypeTransfertOptions(typeTransfert) {
    arrivalTeamInputs = document.querySelectorAll('.arrival-team-input')
    switch (typeTransfert) {
        case 'transfert':
            document.querySelector('.arrival-team').style.display = "grid"
            for (k in arrivalTeamInputs) {
                arrivalTeamInputs[k].required = true
            }
            document.querySelector('.arrival-amount').style.display = "grid"
            document.querySelector('.arrival-amount-input').required = true
            break;
        case 'p.':
            document.querySelector('.arrival-team').style.display = "grid"
            for (k in arrivalTeamInputs) {
                arrivalTeamInputs[k].required = true
            }
            document.querySelector('.arrival-amount').style.display = "none"
            document.querySelector('.arrival-amount input').required = false
            break;
        case 'f.c.':
            document.querySelector('.arrival-team').style.display = "grid"
            for (k in arrivalTeamInputs) {
                arrivalTeamInputs[k].required = true
            }
            document.querySelector('.arrival-amount').style.display = "none"
            document.querySelector('.arrival-amount input').required = false
            break;
        case 'forme':
            document.querySelector('.arrival-team').style.display = "none"
            for (k in arrivalTeamInputs) {
                arrivalTeamInputs[k].required = false
            }
            document.querySelector('.arrival-amount').style.display = "none"
            document.querySelector('.arrival-amount input').required = false
            break;
        case 'p.o.a.':
            document.querySelector('.arrival-team').style.display = "grid"
            for (k in arrivalTeamInputs) {
                arrivalTeamInputs[k].required = true
            }
            document.querySelector('.arrival-amount').style.display = "grid"
            document.querySelector('.arrival-amount input').required = false
            break;
        case 'origine':
            document.querySelector('.arrival-team').style.display = "none"
            for (k in arrivalTeamInputs) {
                arrivalTeamInputs[k].required = false
            }
            document.querySelector('.arrival-amount').style.display = "none"
            document.querySelector('.arrival-amount-input').required = false
            document.querySelector('.arrival-date').value = '2020'
            break;
    }
}