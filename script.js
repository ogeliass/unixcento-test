// menu mobile + accessibilità
const menuButton = document.getElementById("menuButton");
const nav = document.getElementById("nav");

function closeMenu() {
  if (!menuButton || !nav) return;
  nav.classList.remove("open");
  menuButton.setAttribute("aria-expanded", "false");
}

function openMenu() {
  if (!menuButton || !nav) return;
  nav.classList.add("open");
  menuButton.setAttribute("aria-expanded", "true");
}

if (menuButton && nav) {
  // stato iniziale
  menuButton.setAttribute("aria-expanded", nav.classList.contains("open") ? "true" : "false");

  menuButton.addEventListener("click", () => {
    const isOpen = nav.classList.toggle("open");
    menuButton.setAttribute("aria-expanded", isOpen ? "true" : "false");
  });

  // chiudi menu quando clicchi un link (mobile)
  nav.addEventListener("click", (e) => {
    const target = e.target;
    if (target && target.closest && target.closest("a")) closeMenu();
  });

  // chiudi cliccando fuori
  document.addEventListener("click", (e) => {
    if (!nav.classList.contains("open")) return;
    if (e.target === menuButton || menuButton.contains(e.target)) return;
    if (nav.contains(e.target)) return;
    closeMenu();
  });

  // chiudi con ESC
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeMenu();
  });
}

// dropdown "News" (desktop: hover via CSS, mobile: tap)
const dropdownToggle = document.querySelector(".nav-dropdown-toggle");
const dropdownMenu = document.querySelector(".nav-dropdown-menu");

if (dropdownToggle && dropdownMenu) {
  dropdownToggle.addEventListener("click", (e) => {
    // su mobile vogliamo il toggle, su desktop il click va alla pagina news.html
    const isMobile = window.matchMedia("(max-width: 700px)").matches;
    if (!isMobile) return;

    e.preventDefault();
    const expanded = dropdownToggle.getAttribute("aria-expanded") === "true";
    dropdownToggle.setAttribute("aria-expanded", expanded ? "false" : "true");
    dropdownMenu.classList.toggle("open");
  });
}

// evidenzia pagina corrente
(function highlightCurrentNav() {
  const links = Array.from(document.querySelectorAll(".nav a.nav-link"));
  const file = (location.pathname.split("/").pop() || "index.html").toLowerCase();

  links.forEach((a) => {
    const href = (a.getAttribute("href") || "").toLowerCase();
    if (!href) return;

    if (href === file) a.classList.add("active");
    if (file.startsWith("news") && href === "news.html") a.classList.add("active");
  });
})();

// anno corrente nel footer
const yearSpan = document.getElementById("yearSpan");
if (yearSpan) yearSpan.textContent = new Date().getFullYear();

// submit demo per il form tesseramento
const joinForm = document.getElementById("joinForm");
const joinStatus = document.getElementById("joinStatus");

if (joinForm && joinStatus) {
  joinForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(joinForm);

    const name = (formData.get("name") || "").toString().trim();
    const email = (formData.get("email") || "").toString().trim();

    joinStatus.textContent = "";
    joinStatus.className = "form-status";

    if (!name || !email) {
      joinStatus.textContent = "Compila almeno nome e email.";
      joinStatus.classList.add("error");
      return;
    }

    joinStatus.textContent =
      "Richiesta inviata (demo). Collega questo form a un servizio reale per ricevere le richieste.";
    joinStatus.classList.add("success");
    joinForm.reset();
  });
}
