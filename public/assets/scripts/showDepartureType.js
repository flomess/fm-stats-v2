let typeTransfert

document.addEventListener('DOMContentLoaded',()=>{

    let typeTransfertSelect = document.querySelector('select.typeTransfert')

    typeTransfert =  typeTransfertSelect.value

    showTypeTransfertOptions(typeTransfert)
    typeTransfertSelect.onchange = (e)=>{
        showTypeTransfertOptions(e.target.value)
    }
})

function showTypeTransfertOptions(typeTransfert){
    switch (typeTransfert) {
        case 'transfert':
            document.querySelector('.clubTransfert').style.display = "grid"
            document.querySelector('.clubTransfert').setAttribute('required','')
            document.querySelector('.transfMontant').style.display = "grid"
            document.querySelector('.transfMontant input').setAttribute('required','')
            break;
        case 'f.c.':
            document.querySelector('.clubTransfert').style.display = "grid"
            document.querySelector('.clubTransfert').removeAttribute('required')
            document.querySelector('.transfMontant').style.display = "none"
            document.querySelector('.transfMontant input').removeAttribute('required')
            break;
        case 'r.p.':
            document.querySelector('.clubTransfert').style.display = "grid"
            document.querySelector('.clubTransfert').setAttribute('required', '')
            document.querySelector('.transfMontant').style.display = "none"
            document.querySelector('.transfMontant input').removeAttribute('required')
            break;
        case 'p.o.a.':
            document.querySelector('.clubTransfert').style.display = "grid"
            document.querySelector('.clubTransfert').setAttribute('required','')
            document.querySelector('.transfMontant').style.display = "grid"
            document.querySelector('.transfMontant input').setAttribute('required','')
            break;
        case 'retraite':
            document.querySelector('.clubTransfert').style.display = "none"
            document.querySelector('.clubTransfert input').removeAttribute('required')
            document.querySelector('.transfMontant').style.display = "none"
            document.querySelector('.transfMontant input').removeAttribute('required')
            break;
        case 'libre':
            document.querySelector('.clubTransfert').style.display = "none"
            document.querySelector('.clubTransfert input').removeAttribute('required')
            document.querySelector('.transfMontant').style.display = "none"
            document.querySelector('.transfMontant input').removeAttribute('required')
            break;
    }
}