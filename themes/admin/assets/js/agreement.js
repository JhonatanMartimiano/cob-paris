let agreementValue = document.querySelector('[name=value]')
let installments = document.querySelector('[name=installments]')
let amout = 0

// Search Client

function dateByString (date)
{
    return date.split('-').reverse().join('/')
}
let checkValue = null

let buttonSearchClient = document.querySelector('.btn-search-client'),
    inputCPF = document.querySelector('[name=searchClient]'),
    url = buttonSearchClient.getAttribute('data-url'),
    inputMessage = document.querySelector('.input-message')
buttonSearchClient.addEventListener('click', () => {
    axios.post(`${url}/admin/agreements/search-client/${inputCPF.value}`).then((response) => {
        if (response.data.client) {
            inputMessage.innerHTML = ''
            let inputIdClient = document.querySelector('[name=id_client]'),
                inputName = document.querySelector('[name=name]'),
                inputCPF = document.querySelector('[name=cpf_cnpj]')
                tableTbody = document.querySelector('table tbody')

            inputIdClient.value = response.data.client.id
            inputName.value = response.data.client.name
            inputCPF.value = response.data.client.cpf_cnpj
            
            for (let i = 0; i < response.data.agreements.length; i++) {
                let value = response.data.agreements[i].value
                value = value.toString().slice(0,value.toString.length-2)+','+value.toString().slice(-2)
                value = parseFloat(value) * 0.05 + (parseFloat(value)) + ((parseFloat(value) * 0.0033 * response.data.dueDays[i])) 
                tableTbody.innerHTML += `<tr align='center'><td>${response.data.agreements[i].id}</td> <td>${response.data.agreements[i].ticket_number}</td> <td>${dateByString(response.data.agreements[i].issue_date)}</td> <td>${dateByString(response.data.agreements[i].due_date)}</td> <td>${value.toFixed(2).replace('.', ',')}</td> <td><input data-id='${response.data.agreements[i].id}' type='checkbox' class='check-value' value='${value}'></td> </tr>`   
            }

            checkValue = document.querySelectorAll('.check-value')
            let inputIDTickets = document.querySelector('[name=id_tickets]')
            let idTickets = []

            for (let i = 0; i < checkValue.length; i++) {
                checkValue[i].addEventListener('change', () => {
                    if (checkValue[i].checked) {
                        let value = checkValue[i].value
                        amout += parseFloat(value.replace(',', '.'))
                        idTickets.push(checkValue[i].getAttribute('data-id'))
                        inputIDTickets.value = idTickets
                    } else {
                        let value = checkValue[i].value
                        amout -= parseFloat(value.replace(',', '.'))
                        let index = idTickets.indexOf(checkValue[i].getAttribute('data-id'))
                        idTickets.splice(index, 1)
                        inputIDTickets.value = idTickets
                    }
                    agreementValue.value = amout.toFixed(2).toString().replace('.', ',')
                    document.querySelector('.opt-reset').setAttribute('selected', 'selected')
                    console.log('Foi')
                })
            }
        }

        if (response.data.message) {
            let inputIdClient = document.querySelector('[name=id_client]'),
                inputName = document.querySelector('[name=name]'),
                inputCPF = document.querySelector('[name=cpf_cnpj]')

            inputIdClient.value = ''
            inputName.value = ''
            inputCPF.value = ''
            inputMessage.innerHTML = response.data.message
        }
    })
})

installments.addEventListener('change', () => {
    let value = amout
    if (1 == 1) {
        value = value / installments.value
        value = value.toFixed(2)
        agreementValue.value = value.toString().replace('.', ',')
    }
})