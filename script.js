const toggles = document.querySelectorAll('.nav-toggle');
const navMenus = document.querySelectorAll('.nav-links');

toggles.forEach((toggle, index) => {
  toggle.addEventListener('click', () => {
    navMenus[index].classList.toggle('open');
  });
});

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });

document.querySelectorAll('.fade-in').forEach((el) => observer.observe(el));
