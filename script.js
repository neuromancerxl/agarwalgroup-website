(() => {
  "use strict";

  // The editor uses an ordinary Gallery. Playback is a frontend enhancement only.
  document.querySelectorAll(".is-style-agarwal-slideshow").forEach(function (gallery) {
    const slides = Array.from(gallery.children).filter(function (child) {
      return child.classList.contains("wp-block-image");
    });
    if (slides.length < 2) return;

    const container = gallery.closest(".slideshow") || gallery.parentElement;
    const hero = gallery.closest(".home-intro");
    const motionPreference = window.matchMedia("(prefers-reduced-motion: reduce)");
    let current = 0;
    let paused = motionPreference.matches;
    let hovered = false;
    let timer;
    const controls = document.createElement("div");
    controls.className = "slide-controls";
    const count = document.createElement("span");
    const buttons = document.createElement("div");

    function button(label, accessibleLabel, action) {
      const element = document.createElement("button");
      element.type = "button";
      element.textContent = label;
      element.setAttribute("aria-label", accessibleLabel);
      element.addEventListener("click", action);
      buttons.append(element);
      return element;
    }

    const playback = button("Pause", "Pause automatic slideshow", function () {
      paused = !paused;
      schedule();
    });
    button("‹ Prev", "Previous research image", function () { show(current - 1); schedule(); });
    button("Next ›", "Next research image", function () { show(current + 1); schedule(); });
    controls.append(count, buttons);
    gallery.after(controls);

    function show(index) {
      current = (index + slides.length) % slides.length;
      slides.forEach(function (slide, number) { slide.hidden = number !== current; });
      count.textContent = String(current + 1).padStart(2, "0") + " / " + String(slides.length).padStart(2, "0");
      if (hero) {
        ["quantum", "photonics", "phase"].forEach(function (topic) {
          const word = hero.querySelector(".hero-" + topic);
          if (word) word.classList.toggle("active-topic", slides[current].classList.contains(topic + "-slide"));
        });
      }
    }

    function schedule() {
      clearInterval(timer);
      playback.textContent = paused ? "Play" : "Pause";
      playback.setAttribute("aria-label", paused ? "Play automatic slideshow" : "Pause automatic slideshow");
      playback.setAttribute("aria-pressed", String(paused));
      if (!paused && !hovered && !container.contains(document.activeElement) && !document.hidden) {
        timer = setInterval(function () { show(current + 1); }, 5000);
      }
    }

    container.addEventListener("mouseenter", function () { hovered = true; schedule(); });
    container.addEventListener("mouseleave", function () { hovered = false; schedule(); });
    container.addEventListener("focusin", schedule);
    container.addEventListener("focusout", function () { setTimeout(schedule, 0); });
    document.addEventListener("visibilitychange", schedule);
    motionPreference.addEventListener("change", function () { paused = motionPreference.matches; schedule(); });
    show(0);
    schedule();
  });

  const menus = Array.from(document.querySelectorAll(".research-nav, .people-nav"));
  let scrollPending = false;
  function updateSectionNavigation() {
    scrollPending = false;
    const readingLine = Math.min(200, window.innerHeight / 3);
    menus.forEach(function (menu) {
      const links = Array.from(menu.querySelectorAll('a[href^="#"]'));
      const sections = links.map(function (link) {
        return document.getElementById(link.hash.slice(1));
      }).filter(Boolean);
      if (!sections.length) return;
      let current = sections[0];
      sections.forEach(function (section) {
        if (section.getBoundingClientRect().top <= readingLine) current = section;
      });
      if (window.scrollY > 0 && window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 2) current = sections[sections.length - 1];
      links.forEach(function (link) {
        const active = link.hash === "#" + current.id;
        link.classList.toggle("active", active);
        if (active) link.setAttribute("aria-current", "location");
        else link.removeAttribute("aria-current");
      });
    });
  }
  if (menus.length) {
    window.addEventListener("scroll", function () {
      if (!scrollPending) { scrollPending = true; requestAnimationFrame(updateSectionNavigation); }
    }, { passive: true });
    window.addEventListener("resize", updateSectionNavigation);
    updateSectionNavigation();
  }

  // Page identity matters when the installation uses plain WordPress permalinks.
  function pageIdentity(url) {
    return url.pathname.replace(/\/$/, "") + "?" + (url.searchParams.get("page_id") || url.searchParams.get("p") || "");
  }
  const currentUrl = new URL(window.location.href);
  document.querySelectorAll(".main-nav a").forEach(function (link) {
    const destination = new URL(link.href, currentUrl);
    const active = destination.origin === currentUrl.origin && pageIdentity(destination) === pageIdentity(currentUrl);
    link.classList.toggle("active", active);
    if (active) link.setAttribute("aria-current", "page");
  });
})();
