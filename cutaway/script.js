const styles = {
    fio: { align: "left", size: "24px" },
    position: { align: "left", size: "15px" }
  };
  
  document.querySelectorAll(".align-btns button").forEach(button => {
    button.addEventListener("click", e => {
      const parent = e.target.closest(".align-btns");
      const target = parent.dataset.target;
      const alignValue = e.target.dataset.align;
      styles[target].align = alignValue;
      applyCard();
    });
  });
  
  document.querySelectorAll(".font-btns button").forEach(button => {
    button.addEventListener("click", e => {
      const parent = e.target.closest(".font-btns");
      const target = parent.dataset.target;
      const sizeValue = e.target.dataset.size;
      styles[target].size = sizeValue;
      applyCard();
    });
  });
  
  function validateInputs() {
    let isValid = true;
    clearErrors();
    const emailField = document.getElementById("email");
    const phoneField = document.getElementById("phone");
    const emailValue = emailField.value.trim();
    const phoneValue = phoneField.value.trim();
    if (emailValue.length > 0) {
      const emailRegex = /^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,}$/;
      if (!emailRegex.test(emailValue)) {
        showError(emailField, "Введите корректный адрес электронной почты");
        isValid = false;
      }
    }
    if (phoneValue.length > 0) {
      const phoneRegex = /^[+\d\s()-]{5,}$/;
      if (!phoneRegex.test(phoneValue)) {
        showError(phoneField, "Введите корректный номер телефона");
        isValid = false;
      }
    }
    return isValid;
  }
  function showError(input, message) {
    input.classList.add("error");
    const error = document.createElement("div");
    error.className = "error-message";
    error.textContent = message;
    input.insertAdjacentElement("afterend", error);
  }
  function clearErrors() {
    document.querySelectorAll(".error").forEach(el => el.classList.remove("error"));
    document.querySelectorAll(".error-message").forEach(el => el.remove());
  }
  ["email", "phone"].forEach(id => {
    const input = document.getElementById(id);
    input.addEventListener("input", () => {
      input.classList.remove("error");
      const next = input.nextElementSibling;
      if (next && next.classList.contains("error-message")) {
        next.remove();
      }
    });
  });
  
  document.getElementById("apply").addEventListener("click", () => {
    if (!validateInputs()) return;
    applyCard();
  });
  
  ["fioColor", "posColor", "bgColor", "showEmail", "showAddress"].forEach(id => {
    document.getElementById(id).addEventListener("change", applyCard);
  });
  
  function applyCard() {
    const org = document.getElementById("org").value.trim();
    const fio = document.getElementById("fio").value.trim();
    const position = document.getElementById("position").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();
    const address = document.getElementById("address").value.trim();
    const fioColor = document.getElementById("fioColor").value;
    const posColor = document.getElementById("posColor").value;
    const bgColor = document.getElementById("bgColor").value;
    const showEmail = document.getElementById("showEmail").checked;
    const showAddress = document.getElementById("showAddress").checked;
    const card = document.getElementById("card");
    const cOrg = document.getElementById("c-org");
    const cFio = document.getElementById("c-fio");
    const cPos = document.getElementById("c-position");
    const cPhone = document.getElementById("c-phone");
    const cEmail = document.getElementById("c-email");
    const cAddress = document.getElementById("c-address");
    cOrg.textContent = org;
    cFio.textContent = fio;
    cPos.textContent = position;
    cPhone.textContent = phone;
    cEmail.textContent = email;
    cAddress.textContent = address;
    card.style.backgroundColor = bgColor;
    cFio.style.color = fioColor;
    cFio.style.fontSize = styles.fio.size;
    cFio.style.textAlign = styles.fio.align;
    cFio.style.width = "100%";
    cPos.style.color = posColor;
    cPos.style.fontSize = styles.position.size;
    cPos.style.textAlign = styles.position.align;
    cPos.style.width = "100%";
    cEmail.style.display = showEmail && email.length > 0 ? "block" : "none";
    cAddress.style.display = showAddress && address.length > 0 ? "block" : "none";
  }
  
  window.addEventListener("DOMContentLoaded", applyCard);
  