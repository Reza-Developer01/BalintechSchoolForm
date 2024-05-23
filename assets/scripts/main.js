const getInputs = document.querySelectorAll(".form__input-text-box");
const getBtnSubmit = document.querySelector(".form__submit-btn");
const getParentInput = document.querySelectorAll(".form__input");

getBtnSubmit.addEventListener("click", (e) => {
  // e.preventDefault();

  getInputs.forEach((input) => {
    if (!input.value) {
      input.parentElement.classList.add("input__error");
    } else {
      input.parentElement.classList.remove("input__error");
    }
  });
});
