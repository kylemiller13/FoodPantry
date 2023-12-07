// core

// APIS
import { Editor } from "https://esm.sh/@tiptap/core";
import StarterKit from "https://esm.sh/@tiptap/starter-kit";
import Youtube from "https://esm.sh/@tiptap/extension-youtube";
import Image from "https://esm.sh/@tiptap/extension-image";
import Link from "https://esm.sh/@tiptap/extension-link";

const GET_TEMPLATE_API_ENDPOINT = "http://localhost/Data/fetch-templates.php";

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
      <div class="card__header">

      <h2 class="card__title">${template.name}</h2>
      <button class="card__preview preview-${template.id}">Preview</button>
      </div>
           
            <p class="card__description"></p>

            <div class="card-footer">
              <span class="card-footer__badge badge event">${template.notification_type}</span>
              <div class="card-footer__actions">
                <button class="card-footer__actions-button edit card-${template.id}-edit">Edit</button>
                <button class="card-footer__actions-button delete card-${template.id}-delete">Delete</button>
              </div>
            </div>
            `;

      // Include subject and body properties in the dataset
      templateCard.dataset.subject = template.subject; // Replace with your actual subject property
      templateCard.dataset.body = template.body; // Replace with your actual body property

      TEMPLATE_LIST.appendChild(templateCard);

      // BIND ACTIONS

      const editButton = document.querySelector(`.card-${template.id}-edit`);
      const deleteButton = document.querySelector(`.card-${template.id}-delete`);
      const previewButton = document.querySelector(`.preview-${template.id}`);

      editButton.addEventListener("click", () => {
        generateModal({
          title: "Edit template",
          callback: async formData => {
            const response = await fetch("../Data/update-template.php", {
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
            subject: template.subject,
          },
        });
      });

      deleteButton.addEventListener("click", async () => {
        const response = await fetch("../Data/delete-template.php", {
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

      previewButton.addEventListener("click", () => {
        const modal = document.createElement("div");
        modal.innerHTML = `
        <div id="modal">
        <div class="overlay"></div>
        <div class="modal-container">
          <div class="modal-header">
            <h2>Preview</h2>
            <button class="modal-close">&times;</button>
          </div>
         
            <div class="modal-preview">
            <div class="modal-preview__header">
            <h2 class="modal-preview__title">${template.name}</h2>
            <p class="modal-preview__subtitle">${template.subject}</p>
            </div>
            <div class="modal-preview__body">
            ${template.message}
            </div>
            </div>
         
        </div>`;

        document.body.appendChild(modal);

        const modalClose = document.querySelector(".modal-close");

        modalClose.addEventListener("click", () => {
          modal.remove();
        });
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
      const response = await fetch("../Data/create-template.php", {
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
          <label for="template-subject">Template subject</label>
          <input type="text" id="template-subject" name="template-subject" required />
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
          <div class="tooltip"></div>
        
          <div class="template-message" style="border: 1px solid black; margin-top: 8px; padding: 8px;"></div>
       
          
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
    const templateSubject = document.querySelector("#template-subject");
    templateName.value = defaultValues.name;
    templateSubject.value = defaultValues.subject ? defaultValues.subject : "";
  }

  const modalClose = document.querySelector(".modal-close");

  modalClose.addEventListener("click", () => {
    modal.remove();
  });

  // FORM SUBMIT

  const form = document.querySelector(".modal-form");
  const submitButton = document.querySelector(".form-submit");

  const editor = new Editor({
    element: document.querySelector(".template-message"),
    extensions: [
      StarterKit,
      Youtube.configure({ controls: false }),
      Image,
      Link.configure({
        validate: href => /^https?:\/\//.test(href),
      }),
    ],
    content: defaultValues ? defaultValues.message : "",
  });

  const tooltip = document.querySelector(".tooltip");

  tooltip.innerHTML = `
  <button class="h1-button">
        H1
      </button>
      <button class="h2-button">
      H2
    </button>
    <button class="h3-button">
      H3
    </button>
    <button class="paragraph-button">
      P
    </button>
    <button class="bold-button">
      Bold
    </button>
    <button class="italic-button">
    Italic
  </button>
  <button class="strike-button">
    Strike
  </button>
  <button class="bulletlist-button">
    Bullet list
  </button>
  <button class="orderedlist-button">
  Ordered list
  </button>
  <button class="youtube-button">
  Youtube
  </button>
  <button class="image-button">
  Image
  </button>
  <button class="link-button">
  Link
  </button>
      `;

  const h1Button = document.querySelector(".h1-button");
  const h2Button = document.querySelector(".h2-button");
  const h3Button = document.querySelector(".h3-button");
  const paragraphButton = document.querySelector(".paragraph-button");
  const boldButton = document.querySelector(".bold-button");
  const italicButton = document.querySelector(".italic-button");
  const strikeButton = document.querySelector(".strike-button");
  const bulletListButton = document.querySelector(".bulletlist-button");
  const orderedListButton = document.querySelector(".orderedlist-button");
  const youtubeButton = document.querySelector(".youtube-button");
  const imageButton = document.querySelector(".image-button");
  const linkButton = document.querySelector(".link-button");
  const removeLinkButton = document.querySelector(".remove-link-button");

  h1Button.addEventListener("click", event => {
    event.preventDefault();
    editor.chain().focus().toggleHeading({ level: 1 }).run();
    h1Button.className = editor.isActive("heading", { level: 1 }) ? "h1-button active" : "h1-button";
  });

  h2Button.addEventListener("click", event => {
    event.preventDefault();
    editor.chain().focus().toggleHeading({ level: 2 }).run();
    h2Button.className = editor.isActive("heading", { level: 2 }) ? "h2-button active" : "h2-button";
  });

  h3Button.addEventListener("click", event => {
    event.preventDefault();
    editor.chain().focus().toggleHeading({ level: 3 }).run();
    h3Button.className = editor.isActive("heading", { level: 3 }) ? "h3-button active" : "h3-button";
  });

  paragraphButton.addEventListener("click", event => {
    event.preventDefault();
    editor.chain().focus().setParagraph().run();
    paragraphButton.className = editor.isActive("paragraph") ? "paragraph-button active" : "paragraph-button";
  });

  boldButton.addEventListener("click", event => {
    event.preventDefault();
    editor.chain().focus().toggleBold().run();
    boldButton.className = editor.isActive("bold") ? "bold-button active" : "bold-button";
  });

  italicButton.addEventListener("click", event => {
    event.preventDefault();
    editor.chain().focus().toggleItalic().run();
    italicButton.className = editor.isActive("italic") ? "italic-button active" : "italic-button";
  });

  strikeButton.addEventListener("click", event => {
    event.preventDefault();
    editor.chain().focus().toggleStrike().run();
    strikeButton.className = editor.isActive("strike") ? "strike-button active" : "strike-button";
  });

  bulletListButton.addEventListener("click", event => {
    event.preventDefault();
    editor.chain().focus().toggleBulletList().run();
    bulletListButton.className = editor.isActive("bulletList") ? "bulletlist-button active" : "bulletlist-button";
  });

  orderedListButton.addEventListener("click", event => {
    event.preventDefault();
    editor.chain().focus().toggleOrderedList().run();
    orderedListButton.className = editor.isActive("orderedList") ? "orderedlist-button active" : "orderedlist-button";
  });

  youtubeButton.addEventListener("click", event => {
    event.preventDefault();

    const youtubeUrl = prompt("Enter youtube url");

    if (youtubeUrl) {
      editor.commands.setYoutubeVideo({
        src: youtubeUrl,
        width: 560,
        height: 315,
      });
    }
  });

  imageButton.addEventListener("click", event => {
    event.preventDefault();

    const imageUrl = prompt("Enter image url");

    if (imageUrl) {
      editor.commands.setImage({
        src: imageUrl,
      });
    }
  });

  linkButton.addEventListener("click", event => {
    event.preventDefault();

    if (editor.getAttributes("link").href) {
      editor.commands.unsetLink();
      return;
    }

    const linkUrl = prompt("Enter link url");

    editor.commands.toggleLink({ href: linkUrl, target: "_blank" });
  });

  form.addEventListener("submit", async e => {
    e.preventDefault();
    submitButton.disabled = true;

    const templateName = document.querySelector("#template-name");
    const templateType = document.querySelector("#template-type");
    const templateSubject = document.querySelector("#template-subject");

    const response = await callback({
      template_id: defaultValues ? defaultValues.id : null,
      template_name: templateName.value,
      template_type: templateType.options[templateType.options.selectedIndex].value,
      template_content: editor.getHTML(),
      template_subject: templateSubject.value,
    });

    if (response.ok || response.status === 200) {
      submitButton.disabled = false;
      modal.remove();
      await refetchTemplates();
    }
  });
};
