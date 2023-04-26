if (!document.querySelector("form").getAttribute("data-id")) {
  let buttonSearchClient = document.querySelector(".btn-search-client"),
    inputCPF = document.querySelector("[name=searchClient]"),
    url = buttonSearchClient.getAttribute("data-url"),
    inputMessage = document.querySelector(".input-message"),
    selectAgreements = document.querySelector("[name=id_agreement]");

  function dateByString(date) {
    return date.split("-").reverse().join("/");
  }

  buttonSearchClient.addEventListener("click", () => {
    axios
      .post(`${url}/admin/tickets/search-client/${inputCPF.value}`)
      .then((response) => {
        if (response.data.client) {
          inputMessage.innerHTML = "";
          document.querySelector("[name=id_client]").value =
            response.data.client.id;
          document.querySelector("[name=name]").value =
            response.data.client.name;
          document.querySelector("[name=cpf_cnpj]").value =
            response.data.client.cpf_cnpj;
          for (let i = 0; i < response.data.agreements.length; i++) {
            selectAgreements.innerHTML +=
              selectAgreements.innerHTML = `<option value="${
                response.data.agreements[i].id
              }">${response.data.agreements[i].id} - ${dateByString(
                response.data.agreements[i].created
              )}</option>`;
          }
        }

        if (response.data.message) {
          document.querySelector("[name=id_client]").value = "";
          document.querySelector("[name=name]").value = "";
          document.querySelector("[name=cpf_cnpj]").value = "";
          inputMessage.innerHTML = response.data.message;
        }
      });
  });
} else {
  let btnTicketCreate = document.querySelector(".btn-ticket-create"),
    icon = document.querySelector(".btn-ticket-create span i");
  (ticketNumber = document.querySelector("[name=other_ticket_number]")),
    (dueDate = document.querySelector("[name=other_due_date]")),
    (ticketID = btnTicketCreate.getAttribute("data-id")),
    (url = document
      .querySelector(".app.sidebar-mini")
      .getAttribute("data-url"));

  btnTicketCreate.addEventListener("click", () => {
    axios
      .post(`${url}/admin/tickets/others-tickets`, {
        ticket_number: ticketNumber.value,
        due_date: dueDate.value,
        ticket_id: ticketID,
      })
      .then((response) => {
        if (response.data.status) {
          btnTicketCreate.classList.remove("btn-light");
          btnTicketCreate.classList.add(`btn-${response.data.status}`);
          icon.classList.remove("fa-upload");
          response.data.status == "success"
            ? icon.classList.add("fa-check")
            : icon.classList.add("fa-warning");

          setTimeout(() => {
            btnTicketCreate.classList.remove(`btn-${response.data.status}`);
            btnTicketCreate.classList.add("btn-light");
            response.data.status == "success"
              ? icon.classList.remove("fa-check")
              : icon.classList.remove("fa-warning");
            icon.classList.add("fa-upload");
          }, 3000);
        }
      })
      .catch((error) => {
        console.error(error);
      });
  });
}
