document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');
  if (toggle && nav) toggle.addEventListener('click', () => {
    const opened = nav.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(opened));
  });

  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', event => {
      if (!confirm(form.dataset.confirm || 'Confirmer cette action ?')) event.preventDefault();
    });
  });

  // Accessible hero carousel: image and video slides, keyboard/touch-friendly controls.
  document.querySelectorAll('[data-carousel]').forEach(carousel => {
    const slides = [...carousel.querySelectorAll('[data-slide]')];
    const dots = [...carousel.querySelectorAll('[data-go]')];
    const counter = carousel.querySelector('[data-current]');
    if (!slides.length) return;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let current = 0;
    let timer = null;
    const interval = 5600;

    function showSlide(index, resetTimer = true) {
      current = (index + slides.length) % slides.length;
      slides.forEach((slide, i) => {
        const active = i === current;
        slide.classList.toggle('is-active', active);
        slide.setAttribute('aria-hidden', String(!active));
        const video = slide.querySelector('video');
        if (video) {
          if (active) {
            video.play().catch(() => {});
          } else {
            video.pause();
            try { video.currentTime = 0; } catch (_) {}
          }
        }
      });
      dots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === current);
        dot.setAttribute('aria-selected', String(i === current));
        dot.setAttribute('tabindex', i === current ? '0' : '-1');
      });
      if (counter) counter.textContent = String(current + 1).padStart(2, '0');
      if (resetTimer) startTimer();
    }
    function startTimer() {
      if (timer) window.clearInterval(timer);
      if (!reducedMotion && !document.hidden) timer = window.setInterval(() => showSlide(current + 1, false), interval);
    }
    carousel.querySelector('[data-prev]')?.addEventListener('click', () => showSlide(current - 1));
    carousel.querySelector('[data-next]')?.addEventListener('click', () => showSlide(current + 1));
    // Pointer parallax and soft gold spotlight for desktop; disabled for touch/reduced motion.
    if (!reducedMotion && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      let frame = null;
      carousel.addEventListener('pointermove', event => {
        if (frame) cancelAnimationFrame(frame);
        frame = requestAnimationFrame(() => {
          const rect = carousel.getBoundingClientRect();
          const px = (event.clientX - rect.left) / rect.width;
          const py = (event.clientY - rect.top) / rect.height;
          carousel.style.setProperty('--hero-x', `${(0.5 - px) * 14}px`);
          carousel.style.setProperty('--hero-y', `${(0.5 - py) * 10}px`);
          carousel.style.setProperty('--hero-glow-x', `${Math.round(px * 100)}%`);
          carousel.style.setProperty('--hero-glow-y', `${Math.round(py * 100)}%`);
          carousel.classList.add('is-pointer-active');
        });
      });
      carousel.addEventListener('pointerleave', () => {
        if (frame) cancelAnimationFrame(frame);
        carousel.style.setProperty('--hero-x', '0px');
        carousel.style.setProperty('--hero-y', '0px');
        carousel.style.setProperty('--hero-glow-x', '72%');
        carousel.style.setProperty('--hero-glow-y', '22%');
        carousel.classList.remove('is-pointer-active');
      });
    }
    carousel.addEventListener('keydown', event => {
      if (event.key === 'ArrowRight') { event.preventDefault(); showSlide(current + 1); }
      if (event.key === 'ArrowLeft') { event.preventDefault(); showSlide(current - 1); }
    });
    dots.forEach(dot => dot.addEventListener('click', () => showSlide(Number(dot.dataset.go))));
    carousel.addEventListener('mouseenter', () => { if (timer) window.clearInterval(timer); });
    carousel.addEventListener('mouseleave', startTimer);
    carousel.addEventListener('focusin', () => { if (timer) window.clearInterval(timer); });
    carousel.addEventListener('focusout', event => { if (!carousel.contains(event.relatedTarget)) startTimer(); });
    document.addEventListener('visibilitychange', () => document.hidden ? window.clearInterval(timer) : startTimer());
    let touchStartX = null;
    carousel.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].clientX; }, {passive:true});
    carousel.addEventListener('touchend', e => {
      if (touchStartX === null) return;
      const delta = e.changedTouches[0].clientX - touchStartX;
      if (Math.abs(delta) > 55) showSlide(current + (delta < 0 ? 1 : -1));
      touchStartX = null;
    }, {passive:true});
    showSlide(0, false);
    startTimer();
  });

  // Responsive admin drawer.
  const adminToggle = document.querySelector('.admin-mobile-toggle');
  const sidebar = document.querySelector('#adminSidebar');
  if (adminToggle && sidebar) {
    adminToggle.addEventListener('click', () => {
      const opened = sidebar.classList.toggle('is-open');
      adminToggle.setAttribute('aria-expanded', String(opened));
      adminToggle.setAttribute('aria-label', opened ? 'Fermer le menu' : 'Ouvrir le menu');
    });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape') {
        sidebar.classList.remove('is-open');
        adminToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }
});

// Global motion enhancements: scroll reveals, staggered grids, header state and subtle pointer glow.
document.addEventListener('DOMContentLoaded', () => {
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const header = document.querySelector('.site-header');
  const updateHeader = () => header?.classList.toggle('is-scrolled', window.scrollY > 18);
  updateHeader();
  window.addEventListener('scroll', updateHeader, { passive: true });

  if (reduceMotion) return;

  const revealSelectors = [
    '.section-heading', '.category-card', '.product-card', '.editorial-copy',
    '.editorial-art', '.newsletter-band > *', '.trust-strip > *', '.page-hero > *',
    '.shop-sidebar', '.results-top', '.product-detail > *', '.site-ad-banner',
    '.cart-layout > *', '.checkout-layout > *', '.account-panel', '.order-card'
  ];
  const revealNodes = new Set();
  revealSelectors.forEach(selector => document.querySelectorAll(selector).forEach(node => revealNodes.add(node)));
  document.querySelectorAll('.category-grid,.product-grid,.footer-grid').forEach(grid => grid.classList.add('motion-stagger'));
  revealNodes.forEach((node, index) => {
    if (node.closest('.hero-copy') || node.closest('.hero-visual')) return;
    node.classList.add('motion-reveal');
    if (node.matches('.editorial-art,.shop-sidebar,.product-detail > :first-child')) node.dataset.motion = 'left';
    if (node.matches('.editorial-copy,.product-detail > :last-child')) node.dataset.motion = 'right';
    if (node.matches('.category-card,.product-card')) node.style.setProperty('--reveal-delay', `${(index % 4) * 65}ms`);
  });
  const revealObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -35px 0px' });
  document.querySelectorAll('.motion-reveal,.motion-stagger').forEach(node => revealObserver.observe(node));

  if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    document.querySelectorAll('.category-art,.product-image,.site-ad-banner,.newsletter-band').forEach(node => {
      node.classList.add('motion-glow');
      node.addEventListener('pointermove', event => {
        const rect = node.getBoundingClientRect();
        node.style.setProperty('--glow-x', `${event.clientX - rect.left}px`);
        node.style.setProperty('--glow-y', `${event.clientY - rect.top}px`);
      }, { passive: true });
    });
  }
});
