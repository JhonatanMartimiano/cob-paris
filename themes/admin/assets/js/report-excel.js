window.addEventListener("load", () => {
  let btnDue = document.querySelector(".btn-due"),
    btnToWin = document.querySelector(".btn-towin"),
    btnNegotiation = document.querySelector(".btn-negotiation"),
    btnCourts = document.querySelector(".btn-courts"),
    btnProtested = document.querySelector(".btn-protested"),
    btnCanceled = document.querySelector(".btn-canceled"),
    btnOpen = document.querySelector(".btn-open"),
    btnProtestedAgreed = document.querySelector(".btn-protested-agreed"),
    url = document.querySelector(".app.sidebar-mini").getAttribute("data-url");

  btnDue.addEventListener("click", () => {
    axios
      .post(`${url}/admin/charges/report`, {
        status: "due",
      })
      .then((response) => {
        btnDue.setAttribute("href", response.data);
        btnDue.setAttribute("download", response.data.split("reports/")[1]);
      });
  });

  btnDue.click();

  btnToWin.addEventListener("click", () => {
    axios
      .post(`${url}/admin/charges/report`, {
        status: "towin",
      })
      .then((response) => {
        btnToWin.setAttribute("href", response.data);
        btnToWin.setAttribute("download", response.data.split("reports/")[1]);
      });
  });

  btnToWin.click();

  btnNegotiation.addEventListener("click", () => {
    axios
      .post(`${url}/admin/charges/report`, {
        status: "negotiation",
      })
      .then((response) => {
        btnNegotiation.setAttribute("href", response.data);
        btnNegotiation.setAttribute(
          "download",
          response.data.split("reports/")[1]
        );
      });
  });

  btnNegotiation.click();

  btnCourts.addEventListener("click", () => {
    axios
      .post(`${url}/admin/charges/report`, {
        status: "courts",
      })
      .then((response) => {
        btnCourts.setAttribute("href", response.data);
        btnCourts.setAttribute("download", response.data.split("reports/")[1]);
      });
  });

  btnCourts.click();

  btnProtested.addEventListener("click", () => {
    axios
      .post(`${url}/admin/charges/report`, {
        status: "protested",
      })
      .then((response) => {
        btnProtested.setAttribute("href", response.data);
        btnProtested.setAttribute(
          "download",
          response.data.split("reports/")[1]
        );
      });
  });

  btnProtested.click();

  btnCanceled.addEventListener("click", () => {
    axios
      .post(`${url}/admin/charges/report`, {
        status: "canceled",
      })
      .then((response) => {
        btnCanceled.setAttribute("href", response.data);
        btnCanceled.setAttribute(
          "download",
          response.data.split("reports/")[1]
        );
      });
  });

  btnCanceled.click();

  btnOpen.addEventListener("click", () => {
    axios
      .post(`${url}/admin/charges/report`, {
        status: "open",
      })
      .then((response) => {
        btnOpen.setAttribute("href", response.data);
        btnOpen.setAttribute("download", response.data.split("reports/")[1]);
      });
  });

  btnOpen.click();

  btnProtestedAgreed.addEventListener("click", () => {
    axios
      .post(`${url}/admin/charges/report`, {
        status: "protestedAgreed",
      })
      .then((response) => {
        btnProtestedAgreed.setAttribute("href", response.data);
        btnProtestedAgreed.setAttribute(
          "download",
          response.data.split("reports/")[1]
        );
      });
  });

  btnProtestedAgreed.click();
});
