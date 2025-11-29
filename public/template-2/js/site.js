document.addEventListener('DOMContentLoaded', () => {
  // --- Helper Functions for Animations ---

  const slideUp = (target, duration = 300) => {
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = duration + 'ms';
    target.style.boxSizing = 'border-box';
    target.style.height = target.offsetHeight + 'px';
    target.offsetHeight; // trigger reflow
    target.style.overflow = 'hidden';
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    window.setTimeout(() => {
      target.style.display = 'none';
      target.style.removeProperty('height');
      target.style.removeProperty('padding-top');
      target.style.removeProperty('padding-bottom');
      target.style.removeProperty('margin-top');
      target.style.removeProperty('margin-bottom');
      target.style.removeProperty('overflow');
      target.style.removeProperty('transition-duration');
      target.style.removeProperty('transition-property');
    }, duration);
  };

  const slideDown = (target, duration = 300) => {
    target.style.removeProperty('display');
    let display = window.getComputedStyle(target).display;
    if (display === 'none') display = 'block';
    target.style.display = display;

    // Get natural height
    let height = target.offsetHeight; // if display block, we get height? No if height is not set.
    // Standard way to slide down from 0
    // If it was display none, we just set it block.
    // Now we need to animate from 0 to height.
    // Problem: target.offsetHeight is the full height now.

    // Correct approach:
    // 1. Set display block (invisible) to get height
    target.style.display = display;
    let fullHeight = target.offsetHeight;
    if (fullHeight === 0) {
        // Use auto height workaround if needed, or assume it has content.
        // If content is huge, we might want to temporarily set position absolute visibility hidden
    }

    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    target.offsetHeight; // trigger reflow
    target.style.overflow = 'hidden';

    target.style.boxSizing = 'border-box';
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = duration + 'ms';

    target.style.height = fullHeight + 'px';
    target.style.removeProperty('padding-top');
    target.style.removeProperty('padding-bottom');
    target.style.removeProperty('margin-top');
    target.style.removeProperty('margin-bottom');

    window.setTimeout(() => {
      target.style.removeProperty('height');
      target.style.removeProperty('overflow');
      target.style.removeProperty('transition-duration');
      target.style.removeProperty('transition-property');
    }, duration);
  };

  const slideToggle = (target, duration = 300) => {
    if (window.getComputedStyle(target).display === 'none') {
      return slideDown(target, duration);
    } else {
      return slideUp(target, duration);
    }
  };

  const fadeIn = (element, duration = 300) => {
      element.style.opacity = 0;
      element.style.display = 'flex'; // Modal specific, usually 'block'

      let start = null;
      const step = (timestamp) => {
          if (!start) start = timestamp;
          const progress = timestamp - start;
          element.style.opacity = Math.min(progress / duration, 1);
          if (progress < duration) {
              window.requestAnimationFrame(step);
          } else {
              element.style.opacity = 1;
          }
      };
      window.requestAnimationFrame(step);
  };

  const fadeOut = (element, duration = 300) => {
      element.style.opacity = 1;
      let start = null;
      const step = (timestamp) => {
          if (!start) start = timestamp;
          const progress = timestamp - start;
          element.style.opacity = Math.max(1 - (progress / duration), 0);
          if (progress < duration) {
              window.requestAnimationFrame(step);
          } else {
              element.style.opacity = 0;
              element.style.display = 'none';
          }
      };
      window.requestAnimationFrame(step);
  };


  // --- Scroll Header ---
  window.addEventListener("scroll", () => {
    const topMenu = document.querySelector(".top-menu");
    if (window.scrollY >= 100) {
      topMenu.style.boxShadow = "0 10px 10px rgba(0,0,0,0.05)";
    } else {
      topMenu.style.boxShadow = "";
    }
  });

  // --- Accessibility Helpers ---
  const addKeyboardSupport = (element, callback) => {
      element.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              callback(e);
          }
      });
  };

  // --- Mobile Menu ---
  const openMenuBtn = document.querySelector(".open-menu");
  const menu = document.querySelector(".menu");
  const body = document.body;

  if (openMenuBtn && menu) {
    const toggleMenu = (e) => {
        e.stopPropagation();
        openMenuBtn.classList.toggle("active");
        menu.classList.toggle("active");
        body.classList.toggle("no-scroll");
        body.classList.toggle("after-body");

        const isExpanded = openMenuBtn.classList.contains("active");
        openMenuBtn.setAttribute("aria-expanded", isExpanded);
    };

    openMenuBtn.addEventListener("click", toggleMenu);
    addKeyboardSupport(openMenuBtn, toggleMenu);

    document.addEventListener("click", (e) => {
        if (menu.classList.contains("active") &&
            !menu.contains(e.target) &&
            !openMenuBtn.contains(e.target)) {
            closeMenu();
        }
    });

    window.addEventListener('resize', () => {
        if (menu.classList.contains("active")) closeMenu();
    });

    function closeMenu() {
        menu.classList.remove("active");
        openMenuBtn.classList.remove("active");
        body.classList.remove("no-scroll");
        body.classList.remove("after-body");
        openMenuBtn.setAttribute("aria-expanded", "false");
    }
  }

  // --- Tabs ---
  // Assuming tab content is hidden via CSS or previous JS state
  // In jQuery version:
  // $container.find(`#${activeTab}`).addClass("active").slideDown().siblings(".tab-content").removeClass("active").slideUp();

  const initTabs = () => {
      document.querySelectorAll(".tab-container").forEach(container => {
          // Find active tab or default to first
          let activeBtn = container.querySelector("[data-tab].active");
          if (!activeBtn) {
              activeBtn = container.querySelector("[data-tab]");
              if (activeBtn) activeBtn.classList.add('active');
          }

          if (activeBtn) {
              const targetId = activeBtn.getAttribute("data-tab");
              const target = container.querySelector(`#${targetId}`);
              // Hide other contents
              container.querySelectorAll(".tab-content").forEach(c => {
                  if (c.id !== targetId) {
                      c.style.display = 'none';
                      c.classList.remove('active');
                  }
              });
              // Show active
              if (target) {
                  target.style.display = 'block';
                  target.classList.add('active');
              }
          }

          // Add Click Listeners
          const btns = container.querySelectorAll("[data-tab]");
          btns.forEach(btn => {
              const activateTab = () => {
                  const targetId = btn.getAttribute("data-tab");
                  const targetContent = container.querySelector(`#${targetId}`);

                  // Deactivate siblings
                  btns.forEach(b => {
                      b.classList.remove("active");
                      b.setAttribute("aria-selected", "false");
                  });
                  btn.classList.add("active");
                  btn.setAttribute("aria-selected", "true");

                  // Handle Content Transition
                  container.querySelectorAll(".tab-content").forEach(c => {
                      if (c.id === targetId) {
                          if (window.getComputedStyle(c).display === 'none') {
                              c.classList.add('active');
                              slideDown(c);
                          }
                      } else {
                          if (window.getComputedStyle(c).display !== 'none') {
                              c.classList.remove('active');
                              slideUp(c);
                          }
                      }
                  });
              };

              btn.addEventListener('click', activateTab);
              addKeyboardSupport(btn, activateTab);
          });
      });
  };
  initTabs();


  // --- Accordion / Toggle Slide ---
  document.querySelectorAll('.toggle-slide').forEach(toggle => {
      toggle.addEventListener('click', (e) => {
          const slideTarget = toggle.nextElementSibling;
          if(slideTarget && slideTarget.classList.contains('slide-down')) {
              // Close others in the same box
              const box = toggle.closest('.toggle-slide-box');
              if (box) {
                  box.querySelectorAll('.slide-down').forEach(other => {
                      if (other !== slideTarget && window.getComputedStyle(other).display !== 'none') {
                          slideUp(other);
                      }
                  });
              }
              slideToggle(slideTarget);
          }
      });
  });

  // --- Modals ---
  const closeModal = (modalBox) => {
      fadeOut(modalBox);
      const modalContent = modalBox.querySelector('.modal');
      if (modalContent) modalContent.style.transform = 'scale(0.8)';
      document.body.classList.remove('no-scroll');
  };

  document.querySelectorAll('[data-modal]').forEach(trigger => {
      trigger.addEventListener('click', () => {
          const modalId = trigger.getAttribute('data-modal');
          const modalBox = document.getElementById(modalId);
          if (modalBox) {
              fadeIn(modalBox);
              const modalContent = modalBox.querySelector('.modal');
              if (modalContent) modalContent.style.transform = 'scale(1)';
              document.body.classList.add('no-scroll');
          }
      });
  });

  document.querySelectorAll('.modal-close').forEach(btn => {
      btn.addEventListener('click', () => {
          const modalBox = btn.closest('.modal-box');
          closeModal(modalBox);
      });
  });

  document.querySelectorAll('.modal-box').forEach(box => {
      box.addEventListener('click', (e) => {
          if (!e.target.closest('.modal')) {
              closeModal(box);
          }
      });
  });

  // --- Product Selection (Moved from inline script) ---
  // $('.product-item').on('click', ...)
  document.querySelectorAll('.product-item').forEach(item => {
      item.addEventListener('click', () => {
          const radio = item.querySelector('input[type="radio"]');
          if (radio) {
              // Find all items in the same container or group
              // The original code: $('.product-item').removeClass('checked');
              // This implies global reset or reset within container.
              // Assuming global for safety based on original jQuery code,
              // but better scoped to the tab content or product box if possible.
              // Let's stick to global .product-item reset to mimic original exactly.
              document.querySelectorAll('.product-item').forEach(pi => pi.classList.remove('checked'));

              if (!radio.checked) {
                  radio.checked = true;
                  item.classList.add('checked');
              } else {
                  // If already checked, jQuery logic was:
                  // if (!radio.prop('checked')) { radio.prop('checked', true); addClass... }
                  // else it was already checked, but we removed class above. So add it back.
                  // Wait, radio buttons usually stay checked.
                  // If radio is part of a group, clicking another unchecks this one.
                  // If we click the same one, it stays checked.
                  radio.checked = true;
                  item.classList.add('checked');
              }
          }
      });
  });


  // --- Swiper Initialization ---
  var sliderOption = {
    slidesPerView: 4,
    spaceBetween: 20,
    resizeObserver: true,
    watchSlidesVisibility: true,
    watchSlidesProgress: true,
    watchState: true,
    updateOnWindowResize: true,
    breakpointsBase: 'container',
    grabCursor: true,
    speed: 1500,
    breakpoints: {
      0: {
        slidesPerView: 1.1,
        spaceBetween: 20,
      },
      365: {
        slidesPerView: 1.4,
        spaceBetween: 20,
      },
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
    },
  }

  if (typeof Swiper !== 'undefined') {
      var featureSlider = new Swiper(".feature-slider", {
        navigation: {
          nextEl: '.feature-next',
          prevEl: '.feature-prev',
        },
        ...sliderOption
      });

      var commentsSlider = new Swiper(".comments-slider", {
        navigation: {
          nextEl: '.comment-next',
          prevEl: '.comment-prev',
        },
        ...sliderOption
      });

      var blogSlider = new Swiper(".blog-slider", {
        navigation: {
          nextEl: '.blog-next',
          prevEl: '.blog-prev',
        },
        speed: 500,
        loop: true,
        autoplay: {
          delay: 2500,
          disableOnInteraction: false,
        },
        ...sliderOption
      });
  }

  // --- Show More / Hide Box (Vanilla JS logic) ---
  document.querySelectorAll(".hide-box").forEach(function(comentList) {
      var dataHeight = parseInt(comentList.getAttribute("data-height"), 10);
      comentList.style.maxHeight = dataHeight + "px";
  });

  document.querySelectorAll(".show-more").forEach(function(element) {
      element.addEventListener("click", function() {
          var comentList = this.closest(".show-btn-box").previousElementSibling;
          var dataHeight = parseInt(comentList.getAttribute("data-height"), 10);

          var isOpen = comentList.classList.toggle("open");
          var maxHeight = isOpen ? comentList.scrollHeight : dataHeight;
          var startHeight = isOpen ? dataHeight : comentList.scrollHeight;
          var startTime = null;
          var duration = 1000;

          function animateHeight(timestamp) {
              if (!startTime) startTime = timestamp;
              var elapsedTime = timestamp - startTime;
              var progress = Math.min(elapsedTime / duration, 1);
              // Simple easing could be added here
              var currentHeight = startHeight + (maxHeight - startHeight) * progress;
              comentList.style.maxHeight = currentHeight + "px";

              if (progress < 1) {
                  requestAnimationFrame(animateHeight);
              }
          }

          requestAnimationFrame(animateHeight);

          var toggleText = this.querySelector('.toggle-text');
          var icon = this.querySelector('.toggle-icon');

          if (isOpen) {
              toggleText.textContent = 'مشاهده کمتر';
              icon.classList.add('horizon-y');
          } else {
              toggleText.textContent = 'مشاهده بیشتر';
              icon.classList.remove('horizon-y');
          }
      });
  });

  // --- Lottie Animation ---
  if (typeof lottie !== 'undefined') {
      document.querySelectorAll('.star-amin').forEach(function (element) {
          lottie.loadAnimation({
              container: element,
              renderer: 'svg',
              loop: true,
              autoplay: true,
              path: 'js/star.json'
          });
      });
  }

});
