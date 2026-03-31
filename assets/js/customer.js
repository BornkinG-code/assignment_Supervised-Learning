const state = { tableId: null, cart: {}, menu: {}, filteredMenu: {}, restaurantName: 'DigitalTable Restaurant' };
const q = new URLSearchParams(location.search);
state.tableId = q.get('table_id');

const els = {
  landing: document.getElementById('landingScreen'),
  menu: document.getElementById('menuScreen'),
  browseBtn: document.getElementById('browseBtn'),
  browseBtnSticky: document.getElementById('browseBtnSticky'),
  menuWrap: document.getElementById('menuWrap'),
  categoryTabs: document.getElementById('categoryTabs'),
  cartSheet: document.getElementById('cartSheet'),
  sheetBackdrop: document.getElementById('sheetBackdrop'),
  cartOpenBtn: document.getElementById('cartOpenBtn'),
  cartCloseBtn: document.getElementById('cartCloseBtn'),
  stickyCartBar: document.getElementById('stickyCartBar'),
  searchToggle: document.getElementById('searchToggle'),
  menuSearch: document.getElementById('menuSearch'),
  cartItems: document.getElementById('cartItems'),
  cartCountBadge: document.getElementById('cartCountBadge'),
  stickyCount: document.getElementById('stickyCount'),
  stickyTotal: document.getElementById('stickyTotal'),
  subtotal: document.getElementById('subtotal'),
  gstAmt: document.getElementById('gstAmt'),
  total: document.getElementById('total'),
  formError: document.getElementById('formError')
};

async function fetchMenu() {
  const res = await fetch(`../api/get_menu.php?table_id=${state.tableId}`);
  const data = await res.json();
  if (!data.success) { alert(data.message); return; }

  state.menu = data.menu || {};
  state.filteredMenu = state.menu;
  state.restaurantName = data.table?.restaurant_name || 'DigitalTable Restaurant';

  document.getElementById('tableName').textContent = data.table.table_name;
  document.getElementById('tableNameTop').textContent = data.table.table_name;
  document.getElementById('restaurantName').textContent = state.restaurantName;
  document.getElementById('restaurantNameTop').textContent = state.restaurantName;
  document.getElementById('gstPercent').value = data.gst_percent || 5;

  renderCategoryTabs();
  renderMenu();
  renderCart();
}

function showMenuExperience() {
  els.landing.classList.add('hidden');
  els.menu.classList.remove('hidden');
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function renderCategoryTabs() {
  const categories = Object.keys(state.filteredMenu);
  els.categoryTabs.innerHTML = categories.map((cat, index) =>
    `<button class='cat-tab ${index === 0 ? 'active' : ''}' data-target='cat-${slug(cat)}'>${cat}</button>`
  ).join('');

  els.categoryTabs.querySelectorAll('.cat-tab').forEach(btn => {
    btn.addEventListener('click', () => {
      els.categoryTabs.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById(btn.dataset.target)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
}

function renderMenu() {
  const entries = Object.entries(state.filteredMenu);
  if (!entries.length) {
    els.menuWrap.innerHTML = `<div class='panel'><h3>No items found</h3><p class='muted'>Try another keyword.</p></div>`;
    return;
  }

  els.menuWrap.innerHTML = entries.map(([cat, items]) => `
    <section id='cat-${slug(cat)}' class='menu-category'>
      <div class='section-title'><h3>${cat}</h3><span class='pill'>${items.length} items</span></div>
      <div class='item-grid'>
        ${items.map((i, idx) => {
          const qty = state.cart[i.id] || 0;
          const popular = idx === 0 ? "<span class='pill emerald'>Most Ordered</span>" : '';
          const foodType = Number(i.is_veg || 1) === 1 ? 'veg' : 'nonveg';
          return `
            <article class='food-card fade-up' style='animation-delay:${idx * 40}ms'>
              <img loading='lazy' src='../${i.image_path || 'assets/images/placeholder.svg'}' alt='${i.item_name}'>
              <div class='food-body'>
                <div class='row spread'>
                  <h4>${i.item_name}</h4>
                  ${popular}
                </div>
                <p>${i.description || 'Chef-crafted favorite with premium ingredients.'}</p>
                <div class='row spread'>
                  <span class='price'>₹${i.price}</span>
                  <span class='food-dot ${foodType}'></span>
                </div>
                ${Number(i.is_available) ? cartControl(i.id, qty) : `<button class='btn alt full' disabled>Out of stock</button>`}
              </div>
            </article>`;
        }).join('')}
      </div>
    </section>
  `).join('');
}

function cartControl(id, qty) {
  if (!qty) return `<button class='btn cta full' onclick='addToCart(${id})'>Add</button>`;
  return `<div class='stepper'><button onclick='minusFromCart(${id})'>−</button><b>${qty}</b><button onclick='addToCart(${id})'>+</button></div>`;
}

function slug(text) { return text.toLowerCase().replace(/[^a-z0-9]+/g, '-'); }

function addToCart(id) {
  state.cart[id] = (state.cart[id] || 0) + 1;
  renderMenu();
  renderCart();
  animateCartBadge();
}

function minusFromCart(id) {
  if (!state.cart[id]) return;
  state.cart[id]--;
  if (state.cart[id] <= 0) delete state.cart[id];
  renderMenu();
  renderCart();
}

function renderCart() {
  let subtotal = 0;
  const lines = [];

  Object.values(state.menu).flat().forEach(i => {
    if (state.cart[i.id]) {
      const qty = state.cart[i.id];
      const line = qty * parseFloat(i.price);
      subtotal += line;
      lines.push(`
        <div class='cart-line'>
          <div><b>${i.item_name}</b><p class='muted'>₹${i.price} each</p></div>
          <div class='row'>
            <div class='stepper compact'><button onclick='minusFromCart(${i.id})'>−</button><b>${qty}</b><button onclick='addToCart(${i.id})'>+</button></div>
            <strong>₹${line.toFixed(2)}</strong>
          </div>
        </div>
      `);
    }
  });

  const gst = Number(document.getElementById('gstPercent').value || 5);
  const gstAmt = subtotal * gst / 100;
  const total = subtotal + gstAmt;
  const count = Object.values(state.cart).reduce((a, b) => a + b, 0);

  els.cartItems.innerHTML = lines.join('') || `<div class='empty-state'><h3>Cart is empty</h3><p>Add delicious items to continue.</p></div>`;
  els.subtotal.textContent = subtotal.toFixed(2);
  els.gstAmt.textContent = gstAmt.toFixed(2);
  els.total.textContent = total.toFixed(2);
  els.cartCountBadge.textContent = count;
  els.stickyCount.textContent = count;
  els.stickyTotal.textContent = total.toFixed(2);

  els.stickyCartBar.classList.toggle('hidden', count === 0);
}

function animateCartBadge() {
  els.cartCountBadge.animate([
    { transform: 'scale(1)' },
    { transform: 'scale(1.2)' },
    { transform: 'scale(1)' }
  ], { duration: 260, easing: 'ease-out' });
}

function toggleCart(open) {
  els.cartSheet.classList.toggle('open', open);
  els.sheetBackdrop.classList.toggle('open', open);
  if (open) setTimeout(() => document.getElementById('name')?.focus(), 120);
}

function applySearchFilter(term) {
  const q = term.trim().toLowerCase();
  if (!q) {
    state.filteredMenu = state.menu;
  } else {
    state.filteredMenu = Object.fromEntries(
      Object.entries(state.menu)
        .map(([cat, items]) => [cat, items.filter(i =>
          i.item_name.toLowerCase().includes(q) ||
          (i.description || '').toLowerCase().includes(q)
        )])
        .filter(([, items]) => items.length)
    );
  }
  renderCategoryTabs();
  renderMenu();
}

async function placeOrder() {
  const name = document.getElementById('name').value.trim();
  const mobile = document.getElementById('mobile').value.trim();
  const valid = name.length >= 2 && /^[6-9]\d{9}$/.test(mobile);

  els.formError.classList.toggle('hidden', valid);
  if (!valid) return;

  const items = Object.entries(state.cart).map(([id, qty]) => ({ id: +id, qty }));
  if (!items.length) { alert('Cart empty'); return; }

  const res = await fetch('../api/place_order.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name, mobile, table_id: +state.tableId, items })
  });
  const data = await res.json();

  if (!data.success) { alert(data.message); return; }

  localStorage.setItem('last_order_code', data.order_code);
  location.href = `order_status.php?order_code=${data.order_code}&just_placed=1`;
}

els.browseBtn?.addEventListener('click', showMenuExperience);
els.browseBtnSticky?.addEventListener('click', showMenuExperience);
els.cartOpenBtn?.addEventListener('click', () => toggleCart(true));
els.cartCloseBtn?.addEventListener('click', () => toggleCart(false));
els.sheetBackdrop?.addEventListener('click', () => toggleCart(false));
els.stickyCartBar?.addEventListener('click', () => toggleCart(true));
els.searchToggle?.addEventListener('click', () => {
  els.menuSearch.classList.toggle('hidden');
  if (!els.menuSearch.classList.contains('hidden')) els.menuSearch.focus();
});
els.menuSearch?.addEventListener('input', (e) => applySearchFilter(e.target.value));

document.addEventListener('keydown', (e) => { if (e.key === 'Escape') toggleCart(false); });

fetchMenu();
window.placeOrder = placeOrder;
window.addToCart = addToCart;
window.minusFromCart = minusFromCart;
