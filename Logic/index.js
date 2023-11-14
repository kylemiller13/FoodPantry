// core

// APIS

const GET_TEMPLATE_API_ENDPOINT = "http://localhost/backend/fetch-templates.php";

// DOM ELEMENTS
const TEMPLATE_LIST = document.querySelector(".card-container");
const CREATE_TEMPLATE_BUTTON = document.querySelector(".section-header__button");

// BIND EVENTS

const refetchTemplates = async () => {
  TEMPLATE_LIST.innerHTML = "";
  const response = await fetch(GET_TEMPLATE_API_ENDPOINT, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
    mode: "cors",
  });

  if (response.ok || response.status === 200) {
    const data = await response.json();

    // GENERATE CARD BASED ON DATA
    data.forEach(template => {
      const templateCard = document.createElement("div");
      templateCard.classList.add("card");
      templateCard.innerHTML = `
            <h2 class="card__title">${template.name}</h2>
            <p class="card__description"></p>

            <div class="card-footer">
              <span class="card-footer__badge badge event">${template.notification_type}</span>
              <div class="card-footer__actions">
                <button class="card-footer__actions-button edit card-${template.id}-edit">Edit</button>
                <button class="card-footer__actions-button delete card-${template.id}-delete">Delete</button>
              </div>
            </div>
            `;

      TEMPLATE_LIST.appendChild(templateCard);

      // BIND ACTIONS

      const editButton = document.querySelector(`.card-${template.id}-edit`);
      const deleteButton = document.querySelector(`.card-${template.id}-delete`);

      editButton.addEventListener("click", () => {
        generateModal({
          title: "Edit template",
          callback: async formData => {
            const response = await fetch("http://localhost/backend/update-template.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify(formData),
              mode: "cors",
            });

            return response;
          },
          defaultValues: {
            id: template.id,
            name: template.name,
            notification_type: template.notification_type,
            message: template.message,
          },
        });
      });

      deleteButton.addEventListener("click", async () => {
        const response = await fetch("http://localhost/backend/delete-template.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ template_id: template.id }),
          mode: "cors",
        });

        if (response.ok || response.status === 200) {
          await refetchTemplates();
        }
      });
    });
  }
};

document.addEventListener("DOMContentLoaded", async () => {
  await refetchTemplates();
});

CREATE_TEMPLATE_BUTTON.addEventListener("click", () => {
  generateModal({
    title: "Create template",
    callback: async formData => {
      const response = await fetch("http://localhost/backend/create-template.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
        mode: "cors",
      });

      return response;
    },
    defaultValues: null,
  });
});

// generate modal

const generateModal = ({ title, callback, defaultValues }) => {
  const modal = document.createElement("div");
  modal.innerHTML = `
  <div id="modal">
  <div class="overlay"></div>
  <div class="modal-container">
    <div class="modal-header">
      <h2>${title}</h2>
      <button class="modal-close">&times;</button>
    </div>
   
      <form class="modal-form">
        <div class="form-group">
          <label for="template-name">Template name</label>
          <input type="text" id="template-name" name="template-name" required />
        </div>
        <div class="form-group">
          <label for="template-type">Template type</label>
          <select id="template-type" name="template-type" required>
            <option value="event">Event</option>
            <option value="reminder">Reminder</option>
          </select>
        </div>
        <div class="form-group">
          <label for="template-message">Template message</label>
          <textarea rows="12" id="template-message" name="template-message" required></textarea>
        </div>

        <div class="form-footer">
          <button type="submit" class="form-submit">Submit</button>
        </div>
      </form>
   
  </div>
</div>
  `;

  document.body.appendChild(modal);

  if (defaultValues) {
    // SET DEFAULT VALUES
    const templateName = document.querySelector("#template-name");
    const templateType = document.querySelector("#template-type");
    const templateMessage = document.querySelector("#template-message");

    templateName.value = defaultValues.name;
    templateMessage.value = defaultValues.message;
  }

  const modalClose = document.querySelector(".modal-close");

  modalClose.addEventListener("click", () => {
    modal.remove();
  });

  // FORM SUBMIT

  const form = document.querySelector(".modal-form");
  const submitButton = document.querySelector(".form-submit");

  form.addEventListener("submit", async e => {
    e.preventDefault();
    submitButton.disabled = true;

    const templateName = document.querySelector("#template-name");
    const templateType = document.querySelector("#template-type");
    const templateMessage = document.querySelector("#template-message");

    console.log(templateType.options[templateType.options.selectedIndex].value);

    const response = await callback({
      template_id: defaultValues ? defaultValues.id : null,
      template_name: templateName.value,
      template_type: templateType.options[templateType.options.selectedIndex].value,
      template_content: templateMessage.value,
    });

    if (response.ok || response.status === 200) {
      submitButton.disabled = false;
      modal.remove();
      await refetchTemplates();
    }
  });
};
