// js/main.js – BrewMaster | ASB/2023/144
// Features: Form Validation, Dynamic Filtering, Smooth Scrolling,
//           Event Handling (modals, hover), Custom Animations

document.addEventListener('DOMContentLoaded', function () {

    /* ── 1. SMOOTH SCROLLING ─────────────────────────────────────── */
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    /* ── 2. DYNAMIC RECIPE FILTERING ────────────────────────────── */
    const filterBtns = document.querySelectorAll('.filter-btn');
    const recipeItems = document.querySelectorAll('.recipe-item');

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Update active state
            filterBtns.forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');

            const cat = this.dataset.filter;

            recipeItems.forEach(function (item) {
                if (cat === 'All' || item.dataset.category === cat) {
                    item.style.display = 'block';
                    item.style.animation = 'fadeUp .4s ease both';
                } else {
                    item.style.display = 'none';
                }
            });

            // Show empty state if nothing visible
            const grid = document.getElementById('recipeGrid');
            if (grid) {
                const visible = grid.querySelectorAll('.recipe-item[style*="block"]').length;
                const emptyEl = document.getElementById('emptyState');
                if (emptyEl) emptyEl.style.display = visible === 0 ? 'block' : 'none';
            }
        });
    });

    /* ── 3. RECIPE MODAL (Event Handling + Hover) ────────────────── */
    document.querySelectorAll('.recipe-card[data-bs-toggle="modal"]').forEach(function (card) {
        // Hover effect – already handled by CSS transition, but add JS class for extra flair
        card.addEventListener('mouseenter', function () { this.classList.add('card-hovered'); });
        card.addEventListener('mouseleave', function () { this.classList.remove('card-hovered'); });
    });

    // Populate modal with recipe data
    const recipeModal = document.getElementById('recipeModal');
    if (recipeModal) {
        recipeModal.addEventListener('show.bs.modal', function (event) {
            const card   = event.relatedTarget;
            const title  = card.dataset.title  || '';
            const cat    = card.dataset.cat    || '';
            const time   = card.dataset.time   || '';
            const ingr   = card.dataset.ingr   || '';
            const steps  = card.dataset.steps  || '';
            const emoji  = card.dataset.emoji  || '☕';

            document.getElementById('modalTitle').textContent    = title;
            document.getElementById('modalEmoji').textContent    = emoji;
            document.getElementById('modalCategory').textContent = cat;
            document.getElementById('modalTime').textContent     = time ? '⏱ ' + time : '';
            document.getElementById('modalIngredients').innerHTML = ingr.split('\n').map(function(l){ return '<li>' + l + '</li>'; }).join('');
            document.getElementById('modalSteps').innerHTML       = steps.split('\n').filter(Boolean).map(function(l){ return '<li>' + l + '</li>'; }).join('');
        });
    }

    /* ── 4. FORM VALIDATION ──────────────────────────────────────── */

    // Register form
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const username = v('username');
            const email    = v('email');
            const password = v('password');
            const confirm  = v('confirm_password');

            clearError(registerForm);
            if (!username || !email || !password || !confirm) { e.preventDefault(); showError(registerForm, 'All fields are required.'); return; }
            if (!validEmail(email))    { e.preventDefault(); showError(registerForm, 'Please enter a valid email address.'); return; }
            if (password.length < 6)  { e.preventDefault(); showError(registerForm, 'Password must be at least 6 characters.'); return; }
            if (password !== confirm) { e.preventDefault(); showError(registerForm, 'Passwords do not match.'); return; }
        });
    }

    // Login form
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            clearError(loginForm);
            if (!v('email') || !v('password')) { e.preventDefault(); showError(loginForm, 'Please fill in all fields.'); return; }
            if (!validEmail(v('email')))         { e.preventDefault(); showError(loginForm, 'Please enter a valid email.'); return; }
        });
    }

    // Contact form
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            clearError(contactForm);
            if (!v('name') || !v('email') || !v('message')) { e.preventDefault(); showError(contactForm, 'All fields are required.'); return; }
            if (!validEmail(v('email'))) { e.preventDefault(); showError(contactForm, 'Please enter a valid email address.'); return; }
        });
    }

    // Submit recipe form
    const submitForm = document.getElementById('submitForm');
    if (submitForm) {
        submitForm.addEventListener('submit', function (e) {
            clearError(submitForm);
            const title = submitForm.querySelector('[name="title"]');
            const ingr  = submitForm.querySelector('[name="ingredients"]');
            const steps = submitForm.querySelector('[name="instructions"]');
            if (!title.value.trim() || !ingr.value.trim() || !steps.value.trim()) {
                e.preventDefault(); showError(submitForm, 'Title, ingredients, and instructions are required.');
            }
        });
    }

    /* ── 5. CUSTOM FADE-IN ANIMATION on scroll ───────────────────── */
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-on-scroll').forEach(function (el) {
        observer.observe(el);
    });

    /* ── Helpers ─────────────────────────────────────────────────── */
    function v(id) {
        const el = document.getElementById(id);
        return el ? el.value.trim() : '';
    }
    function validEmail(e) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e); }
    function showError(form, msg) {
        const div = document.createElement('div');
        div.className = 'alert-bm-error js-err';
        div.textContent = msg;
        form.prepend(div);
        div.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    function clearError(form) {
        const old = form.querySelector('.js-err');
        if (old) old.remove();
    }

});
