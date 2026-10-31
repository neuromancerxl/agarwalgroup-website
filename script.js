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
    }, 6500);
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

// Publication search and year filter
const paperSearch = document.getElementById("paper-search");
const yearFilter = document.getElementById("year-filter");
const publicationRows = document.querySelectorAll("#publication-list .paper-row");

function filterPublications() {
  const searchText = paperSearch.value.toLowerCase().trim();
  const selectedYear = yearFilter.value;

  publicationRows.forEach(function(row) {
    const textMatches = row.textContent.toLowerCase().includes(searchText);
    const yearMatches = selectedYear === "all" || row.dataset.year === selectedYear;
    row.hidden = !(textMatches && yearMatches);
  });
}

paperSearch.addEventListener("input", filterPublications);
yearFilter.addEventListener("change", filterPublications);

// Simple navigation between the demonstration pages
const pageNames = ["home", "research", "publications", "people", "news", "contact"];

function openPage() {
  let pageName = window.location.hash.replace("#", "") || "home";
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
    publications: "Selected publications",
    people: "People",
    news: "News",
    contact: "Contact"
  };

  document.title = titles[pageName] + " | University of Pennsylvania";
  window.scrollTo(0, 0);
  startSlideshow();
}

window.addEventListener("hashchange", openPage);
openPage();
