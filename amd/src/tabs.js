define(["jquery"], function ($) {

  function showOnlyTab(tabId) {
    // Nasconde tutti i contenuti
    $(".tab-pane").removeClass("show active");

    // Mostra solo quello selezionato
    $(`#${tabId}`).addClass("show active");

    // Rimuove la classe "active" da tutti i link
    $("#courseTab .nav-link").removeClass("active");

    // Mappatura inversa per assegnare l'active al link corretto
    let href;
    switch (tabId) {
      case "tab1":
        href = "#courseTabContent";
        break;
      case "tab2":
      case "tab3":
      case "tab4":
      case "tab5":
        href = `#${tabId}`;
        break;
      default:
        return;
    }

    $(`#courseTab .nav-link[href="${href}"]`).addClass("active");
  }

  function getTabIdFromHash(hash) {
    switch (hash) {
      case "#courseTabContent":
        return "tab1"; // tab associato al contenuto principale
      case "#tab2":
      case "#tab3":
      case "#tab4":
      case "#tab5":
        return hash.substring(1); // 'tab2', 'tab3'...
      default:
        return null;
    }
  }

  function handleHashChange() {
    const hash = window.location.hash;
    const tabId = getTabIdFromHash(hash);
    if (tabId && $(`#${tabId}`).length) {
      showOnlyTab(tabId);
    }
  }

  return {
    init: function () {
      // Attiva il tab corretto al primo caricamento
      handleHashChange();

      // Intercetta click sui link dei tab
      $(document).on("click", '#courseTab .nav-link[href^="#"]', function (e) {
        const hash = $(this).attr("href");
        const tabId = getTabIdFromHash(hash);
        if (tabId && $(`#${tabId}`).length) {
          e.preventDefault();
          history.replaceState(null, null, hash);
          showOnlyTab(tabId);
        }
      });

      // Reagisce anche al back/forward del browser
      window.addEventListener("hashchange", handleHashChange);
    },
  };
});
