/* ========================================
   BrewMaster – recipes.js
   COM 2303 | ASB/2023/144
   ======================================== */

const recipes = [
  {
    id: 'espresso',
    title: 'Classic Espresso',
    category: 'hot',
    time: '5 min', rating: '4.9',
    img: 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=600&q=80',
    desc: 'A bold, concentrated shot pulled from finely ground dark roast beans.'
  },
  {
    id: 'flatWhite',
    title: 'Flat White',
    category: 'hot',
    time: '7 min', rating: '4.7',
    img: 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=600&q=80',
    desc: 'Velvety double ristretto with silky microfoam — the barista\'s favourite.'
  },
  {
    id: 'coldbrewTonic',
    title: 'Cold Brew Tonic',
    category: 'cold',
    time: '12 hr', rating: '4.7',
    img: 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&q=80',
    desc: 'Smooth cold brew concentrate over sparkling tonic water with a citrus twist.'
  },
  {
    id: 'coldFoamColdBrew',
    title: 'Cold Foam Cold Brew',
    category: 'cold',
    time: '10 min', rating: '4.8',
    img: 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=600&q=80',
    desc: 'Silky vanilla cold foam floated over smooth cold brew concentrate on ice.'
  },
  {
    id: 'honeyLavender',
    title: 'Honey Lavender Latte',
    category: 'sweet',
    time: '10 min', rating: '4.8',
    img: 'https://images.unsplash.com/photo-1529892485617-25f63cd7b1e9?w=600&q=80',
    desc: 'Creamy oat milk latte infused with floral lavender syrup and raw honey.'
  },
  {
    id: 'caramelMacchiato',
    title: 'Caramel Macchiato',
    category: 'sweet',
    time: '8 min', rating: '4.5',
    img: 'https://images.unsplash.com/photo-1485808191679-5f86510bd652?w=600&q=80',
    desc: 'Vanilla steamed milk marked with espresso and finished with caramel drizzle.'
  },
  {
    id: 'matchaEspresso',
    title: 'Matcha Espresso Fusion',
    category: 'sweet',
    time: '8 min', rating: '4.6',
    img: 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=600&q=80',
    desc: 'A stunning layered drink combining earthy matcha and bold espresso over ice.'
  },
  {
    id: 'vietnameseCoffee',
    title: 'Vietnamese Iced Coffee',
    category: 'strong',
    time: '15 min', rating: '4.9',
    img: 'https://images.unsplash.com/photo-1509785307050-d4066910ec1e?w=600&q=80',
    desc: 'Rich Robusta dripped through a Phin filter over sweet condensed milk and ice.'
  },
  {
    id: 'lungo',
    title: 'Lungo Forte',
    category: 'strong',
    time: '6 min', rating: '4.8',
    img: 'https://images.unsplash.com/photo-1497515114629-f71d768fd07c?w=600&q=80',
    desc: 'A long, intense espresso pull delivering deep bitterness and a complex aroma.'
  }
];

let activeFilter = 'all';

function renderCards(list) {
  const grid = document.getElementById('recipesGrid');
  const noResults = document.getElementById('noResults');
  grid.innerHTML = '';

  if (list.length === 0) {
    noResults.style.display = 'block';
    return;
  }
  noResults.style.display = 'none';

  list.forEach((r, i) => {
    const col = document.createElement('div');
    col.className = 'col-md-6 col-lg-4';
    col.innerHTML = `
      <div class="recipe-card fade-in" style="transition-delay:${i * 0.07}s">
        <div class="card-img-wrap">
          <img src="${r.img}" alt="${r.title}" class="card-recipe-img" loading="lazy"/>
          <span class="badge-cat ${r.category}">${r.category.charAt(0).toUpperCase() + r.category.slice(1)}</span>
        </div>
        <div class="card-body-custom">
          <h3>${r.title}</h3>
          <p>${r.desc}</p>
          <div class="card-meta">
            <span>⏱ ${r.time}</span>
            <span>⭐ ${r.rating}</span>
          </div>
          <button class="btn-view" onclick="openModal('${r.id}')">View Recipe</button>
        </div>
      </div>
    `;
    grid.appendChild(col);
  });

  requestAnimationFrame(() => {
    document.querySelectorAll('#recipesGrid .fade-in').forEach((el, i) => {
      setTimeout(() => el.classList.add('visible'), i * 70);
    });
  });
}

function getFiltered() {
  const query = document.getElementById('searchInput').value.toLowerCase().trim();
  return recipes.filter(r => {
    const matchFilter = activeFilter === 'all' || r.category === activeFilter;
    const matchSearch = !query ||
      r.title.toLowerCase().includes(query) ||
      r.category.includes(query) ||
      r.desc.toLowerCase().includes(query);
    return matchFilter && matchSearch;
  });
}

// Filter buttons
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', function () {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    activeFilter = this.getAttribute('data-filter');
    renderCards(getFiltered());
  });
});

// Search
document.getElementById('searchInput').addEventListener('input', () => {
  renderCards(getFiltered());
});

// Initial render
renderCards(recipes);