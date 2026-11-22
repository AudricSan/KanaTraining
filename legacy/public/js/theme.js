// Thème clair / sombre : bascule un attribut data-theme sur <html>, lu par
// les variables CSS de color.css (--bg, --text, --surface, ...). Le thème
// sauvegardé est déjà appliqué de façon synchrone dans <head> (voir header.php)
// pour éviter un flash ; ce script gère seulement le changement au clic et la
// synchronisation de l'icône/bouton.
function applyTheme(theme) {
  const isDark = theme === "dark";
  document.documentElement.setAttribute("data-theme", isDark ? "dark" : "light");

  const icon = document.getElementById("themeToggleIcon");
  if (icon) icon.textContent = isDark ? "light_mode" : "dark_mode";

  const btn = document.getElementById("themeToggleBtn");
  if (btn) btn.setAttribute("aria-pressed", String(isDark));
}

// Change le thème. Appelé sans argument (clic sur le bouton) : bascule le
// thème actuel. Appelé avec "light"/"dark" (chargement de page, valeur
// sauvegardée) : applique ce thème précisément.
function selectTheme(nextTheme) {
  if (nextTheme !== "light" && nextTheme !== "dark") {
    const current = document.documentElement.getAttribute("data-theme") === "dark" ? "dark" : "light";
    nextTheme = current === "dark" ? "light" : "dark";
  }

  applyTheme(nextTheme);
  save("theme", nextTheme);
}
