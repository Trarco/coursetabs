define(["jquery"], function ($) {
  function activateTab() {
    const urlParams = new URLSearchParams(window.location.search);
    const selectedTab = urlParams.get("tab");

    console.log("Selected Tab:", selectedTab); // Debug
    if (selectedTab) {
      // Mappa speciale per il primo link
      const tabMapping = {
        courseTabContent: "#tab1",
        tab2: "#tab2",
        tab3: "#tab3",
        tab4: "#tab4",
        tab5: "#tab5",
      };

      // Trova il target corretto
      const dataTarget = tabMapping[selectedTab] || `#${selectedTab}`;
      console.log("Mapped Data Target:", dataTarget); // Debug

      // Trova il link della tab corrispondente
      const tabLink = $(`#courseTab .nav-link[data-target='${dataTarget}']`);

      console.log("Tab Link Found:", tabLink); // Debug

      if (tabLink.length) {
        // Simula un clic e triggera l'evento per il cambio tab
        tabLink.tab("show"); // Per Bootstrap o librerie compatibili

        console.log(`Activated tab: ${selectedTab}`);
      } else {
        console.error(`Tab link with data-target='${dataTarget}' not found.`);
      }
    } else {
      console.warn("No tab parameter found in the URL.");
    }
  }

  function waitForTabsAndActivate(interval = 1000, maxAttempts = 50) {
    let attempts = 0;

    const checkTabs = setInterval(() => {
      const tabLinks = document.querySelectorAll("#courseTab .nav-link");
      console.log(`Polling for tabs... Attempt: ${attempts + 1}`);

      if (tabLinks.length > 0) {
        clearInterval(checkTabs);
        console.log("Tabs found. Activating tab...");
        activateTab();
      }

      attempts++;
      if (attempts >= maxAttempts) {
        clearInterval(checkTabs);
        console.error("Failed to find tabs after multiple attempts.");
      }
    }, interval);
  }

  return {
    init: function () {
      console.log("Window fully loaded. Starting polling for tabs...");
      waitForTabsAndActivate();
    },
  };
});
