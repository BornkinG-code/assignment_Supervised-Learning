const state = { tableId: null, cart: {}, menu: {}, search: '' };
const q = new URLSearchParams(location.search);
state.tableId = q.get('table_id');

async function fetchMenu() {
  const res = await fetch(`../api/get_menu.php?table_id=${state.tableId}`);
  const data = await res.json();
  if (!data.success) {
    alert(data.message);
    return;
  }

  state.menu = data.menu;
  document.getElementById('tableName').textContent = data.table.table_name;
  document.getElementById('gstPercent').value = data.gst_percent || 5;

  const searchInput = document.getElementById('menuSearch');
  searchInput.addEventListener('input', (e) => {
    state.search = e.target.value.trim().toLowerCase();
    renderMenu();
  });

  renderMenu();
  renderCart();
}

function renderMenu() {
  const wrap = document.getElementById('menuWrap');
  wrap.innerHTML = '';

  Object.entries(state.menu).forEach(([cat, items]) => {
    const filtered = items.filter((i) => {
      if (!state.search) return true;
      const text = `${i.item_name} ${i.description || ''}`.toLowerCase();
      return text.includes(state.search);
    });

    if (!filtered.length) return;

    const section = document.createElement('section');
    section.className = 'menu-section';
    section.innerHTML = `<h3>${cat}</h3><div class='grid'>${filtered.map((i) => {
      const qty = state.cart[i.id] || 0;
      const controls = qty
        ? `<div class='qty'>
            <button onclick='minusFromCart(${i.id})'>−</button>
            <span>${qty}</span>
            <button onclick='addToCart(${i.id})'>+</button>
          </div>`
        : `<button class='btn btn-add' ${+i.is_available ? '' : 'disabled'} onclick='addToCart(${i.id})'>${+i.is_available ? 'Add' : 'Sold out'}</button>`;

      return `<article class='card'>
        <div class='card-media'>
          <img src='../${i.image_path || 'assets/images/placeholder.svg'}' alt='${i.item_name}'>
          <span class='badge'>Popular</span>
        </div>
        <div class='card-body'>
          <h4>${i.item_name}</h4>
          <p class='desc'>${i.description || 'Freshly prepared with chef special flavors.'}</p>
          <div class='price-row'>
            <span class='price'>₹${Number(i.price).toFixed(2)}</span>
            ${controls}
          </div>
        </div>
      </article>`;
    }).join('')}</div>`;

    wrap.appendChild(section);
  });

  if (!wrap.innerHTML.trim()) {
    wrap.innerHTML = "<p class='empty'>No items found for this search.</p>";
  }
}

function addToCart(id) {
  state.cart[id] = (state.cart[id] || 0) + 1;
  renderMenu();
  renderCart();
}

function minusFromCart(id) {
  if (!state.cart[id]) return;
  state.cart[id]--;
  if (state.cart[id] <= 0) delete state.cart[id];
  renderMenu();
  renderCart();
}

function scrollToCart() {
  document.getElementById('cartSection')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function renderCart() {
  const el = document.getElementById('cartItems');
  let subtotal = 0;
  let totalQty = 0;
  const lines = [];

  Object.values(state.menu).flat().forEach((i) => {
    if (!state.cart[i.id]) return;
    const qty = state.cart[i.id];
    const line = qty * parseFloat(i.price);
    subtotal += line;
    totalQty += qty;
    lines.push(`<div class='cart-line'>
      <div>
        <strong>${i.item_name}</strong><br>
        <small>₹${Number(i.price).toFixed(2)} each</small>
      </div>
      <div class='cart-actions'>
        <div class='qty'>
          <button onclick='minusFromCart(${i.id})'>−</button>
          <span>${qty}</span>
          <button onclick='addToCart(${i.id})'>+</button>
        </div>
        <span>₹${line.toFixed(2)}</span>
      </div>
    </div>`);
  });

  const gst = Number(document.getElementById('gstPercent').value || 5);
  const gstAmt = (subtotal * gst) / 100;
  const total = subtotal + gstAmt;

  el.innerHTML = lines.join('') || "<p class='empty'>Cart is empty</p>";
  document.getElementById('subtotal').textContent = subtotal.toFixed(2);
  document.getElementById('gstAmt').textContent = gstAmt.toFixed(2);
  document.getElementById('total').textContent = total.toFixed(2);

  document.getElementById('floatingCartCount').textContent = totalQty;
  document.getElementById('floatingCartTotal').textContent = total.toFixed(2);
  const floatingBtn = document.getElementById('floatingCartBtn');
  floatingBtn.style.display = totalQty ? 'flex' : 'none';
}

async function placeOrder() {
  const name = document.getElementById('name').value.trim();
  const mobile = document.getElementById('mobile').value.trim();
  if (!name || !/^[6-9]\d{9}$/.test(mobile)) {
    alert('Enter valid name and mobile');
    return;
  }

  const items = Object.entries(state.cart).map(([id, qty]) => ({ id: +id, qty }));
  if (!items.length) {
    alert('Cart empty');
    return;
  }

  const res = await fetch('../api/place_order.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name, mobile, table_id: +state.tableId, items }),
  });
  const data = await res.json();

  if (!data.success) {
    alert(data.message);
    return;
  }

  localStorage.setItem('last_order_code', data.order_code);
  location.href = `order_status.php?order_code=${data.order_code}`;
}

fetchMenu();
