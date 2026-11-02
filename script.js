// Slideshow
const slides = document.querySelectorAll(".slide");
const topicWords = document.querySelectorAll("[data-hero-topic]");
const slideCount = document.getElementById("slide-count");
const slideshow = document.querySelector(".slideshow");
let currentSlide = 0;
let slideTimer;
let mouseIsOverSlider = false;

function showSlide(number) {
  currentSlide = (number + slides.length) % slides.length;

  slides.forEach(function(slide, index) {
    slide.hidden = index !== currentSlide;
  });

  const currentTopic = slides[currentSlide].dataset.topic;
  topicWords.forEach(function(word) {
    word.classList.toggle("active-topic", word.dataset.heroTopic === currentTopic);
  });

  slideCount.textContent = String(currentSlide + 1).padStart(2, "0") + " / " + String(slides.length).padStart(2, "0");
}

function startSlideshow() {
  clearInterval(slideTimer);
  const homeIsOpen = !document.getElementById("home-page").hidden;
  const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  if (homeIsOpen && !mouseIsOverSlider && !reducedMotion) {
    slideTimer = setInterval(function() {
      showSlide(currentSlide + 1);
    }, 5000);
  }
}

document.getElementById("previous-slide").addEventListener("click", function() {
  showSlide(currentSlide - 1);
  startSlideshow();
});

document.getElementById("next-slide").addEventListener("click", function() {
  showSlide(currentSlide + 1);
  startSlideshow();
});

slideshow.addEventListener("mouseenter", function() {
  mouseIsOverSlider = true;
  startSlideshow();
});

slideshow.addEventListener("mouseleave", function() {
  mouseIsOverSlider = false;
  startSlideshow();
});

showSlide(0);

// Publication search, topic, and year filters
const paperSearch = document.getElementById("paper-search");
const yearFilter = document.getElementById("year-filter");
const topicButtons = document.querySelectorAll(".publication-topics button");
const publicationRows = document.querySelectorAll("#publication-list .paper-row");
const noPublications = document.getElementById("no-publications");
let selectedTopic = "all";

function filterPublications() {
  const searchText = paperSearch.value.toLowerCase().trim();
  const selectedYear = yearFilter.value;
  let matchingPapers = 0;

  publicationRows.forEach(function(row) {
    const textMatches = row.textContent.toLowerCase().includes(searchText);
    const yearMatches = selectedYear === "all" || row.dataset.year === selectedYear;
    const topics = (row.dataset.topics || "").split(/\s+/);
    const topicMatches = selectedTopic === "all" || topics.includes(selectedTopic);
    row.hidden = !(textMatches && yearMatches && topicMatches);
    if (!row.hidden) matchingPapers++;
  });

  noPublications.hidden = matchingPapers > 0;
}

paperSearch.addEventListener("input", filterPublications);
yearFilter.addEventListener("change", filterPublications);

topicButtons.forEach(function(button) {
  button.addEventListener("click", function() {
    selectedTopic = button.dataset.topic;
    topicButtons.forEach(function(topicButton) {
      const isSelected = topicButton === button;
      topicButton.classList.toggle("active", isSelected);
      topicButton.setAttribute("aria-pressed", String(isSelected));
    });
    filterPublications();
  });
});

filterPublications();

// Simple navigation between the demonstration pages
const pageNames = ["home", "research", "publications", "people", "news", "contact"];
const sectionMenus = document.querySelectorAll(".research-nav, .people-nav");
const pageSections = document.querySelectorAll(".research-area, .people-section");

function updateSectionNavigation() {
  const readingLine = Math.min(200, window.innerHeight / 3);

  sectionMenus.forEach(function(menu) {
    const page = menu.closest("[data-page]");
    if (page.hidden) return;

    const links = menu.querySelectorAll("a");
    const sections = Array.from(links, function(link) {
      return document.getElementById(link.getAttribute("href").slice(1));
    });
    let currentSection = sections[0];

    sections.forEach(function(section) {
      if (section.getBoundingClientRect().top <= readingLine) {
        currentSection = section;
      }
    });

    // The last heading may not reach the reading line before the page ends.
    if (window.scrollY > 0 && window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 2) {
      currentSection = sections[sections.length - 1];
    }

    links.forEach(function(link) {
      const isCurrent = link.getAttribute("href") === "#" + currentSection.id;
      link.classList.toggle("active", isCurrent);
      if (isCurrent) {
        link.setAttribute("aria-current", "location");
      } else {
        link.removeAttribute("aria-current");
      }
    });
  });
}

function openPage() {
  let pageName = window.location.hash.replace("#", "") || "home";
  const pageSection = Array.from(pageSections).find(function(section) {
    return section.id === pageName;
  });
  if (pageSection) pageName = pageSection.closest("[data-page]").dataset.page;
  if (!pageNames.includes(pageName)) pageName = "home";

  document.querySelectorAll("[data-page]").forEach(function(page) {
    page.hidden = page.dataset.page !== pageName;
  });

  document.querySelectorAll(".main-nav a").forEach(function(link) {
    link.classList.toggle("active", link.getAttribute("href") === "#" + pageName);
  });

  const titles = {
    home: "Agarwal Group",
    research: "Research",
    publications: "Publications",
    people: "People",
    news: "News",
    contact: "Contact"
  };

  document.title = titles[pageName] + " | University of Pennsylvania";
  if (pageSection) {
    pageSection.scrollIntoView({ block: "start" });
  } else {
    window.scrollTo(0, 0);
  }
  updateSectionNavigation();
  startSlideshow();
}

window.addEventListener("hashchange", openPage);
window.addEventListener("scroll", updateSectionNavigation, { passive: true });
window.addEventListener("resize", updateSectionNavigation);
openPage();
