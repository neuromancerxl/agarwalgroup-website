(() => {
  "use strict";

  const papersPerPage = 10;
  const groupAuthor = "Ritesh Agarwal";
  const publicationList = document.getElementById("publication-list");
  const topicFilters = document.querySelector(".publication-topics");
  const publicationTools = document.querySelector(".publication-tools");
  const paperSearch = document.getElementById("paper-search");
  const yearFilter = document.getElementById("year-filter");
  const emptyMessage = document.getElementById("no-publications");
  const pagination = document.getElementById("publication-pagination");
  const resultStatus = document.getElementById("publication-status");

  if (!publicationList) return;

  const configuration = window.agarwalPublications || {};
  let papers = [];
  let selectedTopic = configuration.topic || "all";
  let currentPage = configuration.page || 1;

  function abbreviateAuthor(name) {
    const parts = name.trim().split(/\s+/);
    let surname = parts.pop();
    const surnameParticles = ["van", "von", "de", "der", "den", "del", "da", "di", "dos", "la", "le"];

    // Keep surname particles intact: Lambert K. van Vugt becomes L. K. van Vugt.
    while (parts.length > 1 && surnameParticles.includes(parts[parts.length - 1].toLowerCase())) {
      surname = parts.pop() + " " + surname;
    }

    const initials = parts.map(function(part) {
      return part.split("-").map(function(piece) {
        return piece.charAt(0) + ".";
      }).join("-");
    }).join(" ");

    return initials ? initials + " " + surname : surname;
  }

  function formatAuthors(paper) {
    if (!paper.authors.length) return paper.authorNote || "";

    const otherAuthors = paper.authors.filter(function(name) {
      return name !== groupAuthor;
    });
    let names = otherAuthors.slice(0, 3).map(abbreviateAuthor).join(", ");
    if (names && (otherAuthors.length > 3 || paper.authorsIncomplete)) {
      names += " et al.";
    }

    const authorGroups = [];
    if (names) authorGroups.push(names);
    if (paper.authors.includes(groupAuthor)) authorGroups.push(abbreviateAuthor(groupAuthor));
    return authorGroups.join(" · ");
  }

  function paperUrl(paper) {
    return paper.url || (paper.doi ? "https://doi.org/" + paper.doi : "");
  }

  function validateCatalog(catalog) {
    if (catalog.schemaVersion !== 1 || !Array.isArray(catalog.topics) || !Array.isArray(catalog.papers)) {
      throw new Error("Unsupported publication catalog format.");
    }

    const topicIds = new Set();
    catalog.topics.forEach(function(topic) {
      if (!topic.id || topic.id === "all" || !topic.label || topicIds.has(topic.id)) {
        throw new Error("Invalid or duplicate publication topic: " + topic.id);
      }
      topicIds.add(topic.id);
    });

    const paperIds = new Set();
    catalog.papers.forEach(function(paper) {
      const validAuthors = Array.isArray(paper.authors) && paper.authors.every(function(name) {
        return typeof name === "string" && name.trim();
      });
      const validTopics = Array.isArray(paper.topics) && paper.topics.every(function(topic) {
        return topicIds.has(topic);
      });
      if (!paper.id || paperIds.has(paper.id) || !paper.title || !paper.journal ||
          !Number.isInteger(paper.year) || !validAuthors || !validTopics ||
          !["published", "preprint"].includes(paper.status) || !/^https?:\/\//i.test(paperUrl(paper))) {
        throw new Error("Invalid or duplicate publication: " + paper.id);
      }
      paperIds.add(paper.id);
    });
  }

  function createPaperLink(paper) {
    const link = document.createElement("a");
    link.href = paperUrl(paper);
    link.target = "_blank";
    link.rel = "noopener";
    link.textContent = paper.title;
    return link;
  }

  function createPaperRow(paper) {
    const row = document.createElement("article");
    row.className = "paper-row";
    row.dataset.paperId = paper.id;

    const year = document.createElement("span");
    year.className = "paper-year";
    year.textContent = paper.year;

    const details = document.createElement("div");
    const title = document.createElement("h3");
    title.append(createPaperLink(paper));
    const authors = document.createElement("p");
    authors.textContent = formatAuthors(paper);
    details.append(title, authors);

    const journal = document.createElement("div");
    journal.className = "paper-journal";
    journal.textContent = paper.journal;
    if (paper.status === "preprint") {
      const note = document.createElement("span");
      note.textContent = "Preprint";
      journal.append(note);
    }

    row.append(year, details, journal);
    return row;
  }

  function createTopicFilters(topics) {
    topicFilters.replaceChildren();
    [{ id: "all", label: "All" }, ...topics].forEach(function(topic) {
      const button = document.createElement("button");
      button.type = "button";
      button.dataset.topic = topic.id;
      button.textContent = topic.label;
      button.classList.toggle("active", topic.id === selectedTopic);
      button.setAttribute("aria-pressed", String(topic.id === selectedTopic));
      button.addEventListener("click", function() {
        selectedTopic = topic.id;
        topicFilters.querySelectorAll("button").forEach(function(topicButton) {
          const active = topicButton.dataset.topic === selectedTopic;
          topicButton.classList.toggle("active", active);
          topicButton.setAttribute("aria-pressed", String(active));
        });
        resetPublicationPage();
      });
      topicFilters.append(button);
    });
  }

  function populateYears() {
    const previousYear = yearFilter.value;
    yearFilter.replaceChildren();
    const allYears = document.createElement("option");
    allYears.value = "all";
    allYears.textContent = "All years";
    yearFilter.append(allYears);

    const years = [...new Set(papers.map(function(paper) { return paper.year; }))];
    years.sort(function(a, b) { return b - a; });
    years.forEach(function(year) {
      const option = document.createElement("option");
      option.value = year;
      option.textContent = year;
      yearFilter.append(option);
    });
    yearFilter.value = years.some(function(year) { return String(year) === previousYear; }) ? previousYear : "all";
  }

  function filteredPapers() {
    const search = paperSearch.value.trim().toLowerCase();
    return papers.filter(function(paper) {
      const searchableText = [paper.title, paper.journal, paper.year, paper.status, ...paper.authors,
        formatAuthors(paper), paper.authorNote || ""].join(" ").toLowerCase();
      const matchesText = searchableText.includes(search);
      const matchesYear = yearFilter.value === "all" || String(paper.year) === yearFilter.value;
      const matchesTopic = selectedTopic === "all" || paper.topics.includes(selectedTopic);
      return matchesText && matchesYear && matchesTopic;
    });
  }

  function pageNumbers(totalPages) {
    if (totalPages <= 7) return Array.from({ length: totalPages }, function(_, i) { return i + 1; });

    const visible = new Set([1, totalPages, currentPage]);
    for (let page = currentPage - 1; page <= currentPage + 1; page++) {
      if (page > 1 && page < totalPages) visible.add(page);
    }
    if (currentPage <= 3) { visible.add(2); visible.add(3); }
    if (currentPage >= totalPages - 2) { visible.add(totalPages - 2); visible.add(totalPages - 1); }

    const numbers = [...visible].sort(function(a, b) { return a - b; });
    const result = [];
    numbers.forEach(function(page, index) {
      const gap = page - numbers[index - 1];
      if (gap === 2) result.push(page - 1);
      else if (gap > 2) result.push("…");
      result.push(page);
    });
    return result;
  }

  function createPageButton(label, page, accessibleLabel) {
    const button = document.createElement("button");
    button.type = "button";
    button.textContent = label;
    button.dataset.page = page;
    button.setAttribute("aria-label", accessibleLabel || "Page " + page);
    button.setAttribute("aria-controls", "publication-list");
    if (page === currentPage) button.setAttribute("aria-current", "page");
    button.addEventListener("click", function() {
      if (page === currentPage) return;
      currentPage = page;
      renderPublications();
      publicationList.focus({ preventScroll: true });
      publicationList.scrollIntoView({ block: "start" });
    });
    return button;
  }

  function renderPagination(totalPages) {
    pagination.replaceChildren();
    pagination.hidden = totalPages <= 1;
    if (pagination.hidden) return;

    if (currentPage > 1) pagination.append(createPageButton("← Previous", currentPage - 1, "Previous page"));
    pageNumbers(totalPages).forEach(function(page) {
      if (page === "…") {
        const gap = document.createElement("span");
        gap.textContent = "…";
        gap.setAttribute("aria-hidden", "true");
        pagination.append(gap);
      } else {
        pagination.append(createPageButton(page, page));
      }
    });
    if (currentPage < totalPages) pagination.append(createPageButton("Next →", currentPage + 1, "Next page"));
  }

  function renderPublications() {
    const matches = filteredPapers();
    const totalPages = Math.ceil(matches.length / papersPerPage);
    currentPage = Math.max(1, Math.min(currentPage, totalPages));
    const start = (currentPage - 1) * papersPerPage;
    const visiblePapers = matches.slice(start, start + papersPerPage);
    publicationList.replaceChildren(...visiblePapers.map(createPaperRow));
    updateAddress();
    emptyMessage.hidden = matches.length > 0;
    renderPagination(totalPages);
    resultStatus.textContent = matches.length
      ? "Page " + currentPage + " of " + totalPages + ". Showing papers " + (start + 1) + "–" +
        (start + visiblePapers.length) + " of " + matches.length + "."
      : "";
  }

  function resetPublicationPage() {
    currentPage = 1;
    renderPublications();
  }

  function updateAddress() {
    const address = new URL(window.location.href);
    const values = { ag_search: paperSearch.value.trim(), ag_topic: selectedTopic, ag_year: yearFilter.value, ag_page: String(currentPage) };
    Object.entries(values).forEach(function ([key, value]) {
      if (!value || value === "all" || (key === "ag_page" && value === "1")) address.searchParams.delete(key);
      else address.searchParams.set(key, value);
    });
    window.history.replaceState(null, "", address);
  }

  async function loadPublications() {
    publicationList.setAttribute("aria-busy", "true");
    try {
      const response = await fetch(configuration.catalogUrl, { cache: "no-store" });
      if (!response.ok) throw new Error("Publication catalog request failed: " + response.status);
      const catalog = await response.json();
      validateCatalog(catalog);
      papers = catalog.papers.slice().sort(function(a, b) { return b.year - a.year; });
      createTopicFilters(catalog.topics);
      populateYears();
      paperSearch.addEventListener("input", resetPublicationPage);
      yearFilter.addEventListener("change", resetPublicationPage);
      publicationTools.addEventListener("submit", function (event) {
        event.preventDefault();
        resetPublicationPage();
      });
      publicationTools.querySelector(".publication-submit").hidden = true;
      topicFilters.hidden = false;
      publicationTools.hidden = false;
      renderPublications();
      window.dispatchEvent(new Event("publicationsloaded"));
    } catch (error) {
      console.error("Could not load the publication catalog:", error);
      // Keep the server-rendered list, links, and GET form as a working fallback.
    } finally {
      publicationList.setAttribute("aria-busy", "false");
    }
  }

  loadPublications();
})();
