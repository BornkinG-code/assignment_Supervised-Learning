const state = { tableId: null, cart: {}, menu: {} };
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
  renderMenu();
  renderCart();
}

function renderMenu() {
  const wrap = document.getElementById('menuWrap');
  wrap.innerHTML = '';

  Object.entries(state.menu).forEach(([cat, items]) => {
    const section = document.createElement('section');
    section.className = 'category-block';
    section.innerHTML = `
      <h3 class='section-title'>${cat}</h3>
      <div class='grid'>
        ${items.map((i) => {
          const qty = state.cart[i.id] || 0;
          return `
            <div class='card'>
              <img src='../${i.image_path || 'assets/images/placeholder.svg'}' alt='${i.item_name}'>
              <div class='card-body stack'>
                <div>
                  <div class='row' style='justify-content:space-between;align-items:flex-start'>
                    <h4 style='margin:0'>${i.item_name}</h4>
                    <span class='badge ${+i.is_available ? 'neutral' : 'rejected'}'>${+i.is_available ? 'Available' : 'Sold out'}</span>
                  </div>
                  <p class='food-meta'>${i.description || 'Freshly prepared from our kitchen.'}</p>
                  <div class='price'>₹${i.price}</div>
                </div>
                <div class='row'>
                  <button class='btn' ${+i.is_available ? '' : 'disabled'} onclick='addToCart(${i.id})'>${+i.is_available ? 'Add to cart' : 'Unavailable'}</button>
                  ${qty > 0 ? `
                    <span class='qty-control'>
                      <button class='btn alt qty-btn' onclick='minusFromCart(${i.id})'>-</button>
                      <strong>${qty}</strong>
                      <button class='btn alt qty-btn' onclick='addToCart(${i.id})'>+</button>
                    </span>` : ''}
                </div>
              </div>
            </div>`;
        }).join('')}
      </div>`;
    wrap.appendChild(section);
  });
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

function renderCart() {
  const el = document.getElementById('cartItems');
  let subtotal = 0;
  const lines = [];

  Object.values(state.menu).flat().forEach((i) => {
    if (state.cart[i.id]) {
      const qty = state.cart[i.id];
      const line = qty * parseFloat(i.price);
      subtotal += line;
      lines.push(`
        <div class='cart-row'>
          <div><strong>${i.item_name}</strong><div class='muted'>x${qty}</div></div>
          <strong>₹${line.toFixed(2)}</strong>
          <button class='btn alt qty-btn' onclick='minusFromCart(${i.id})'>-</button>
        </div>`);
    }
  });

  const gst = Number(document.getElementById('gstPercent').value || 5);
  const gstAmt = subtotal * gst / 100;
  const total = subtotal + gstAmt;

  el.innerHTML = lines.join('') || '<div class="empty-state">Your cart is empty.</div>';
  document.getElementById('subtotal').textContent = subtotal.toFixed(2);
  document.getElementById('gstAmt').textContent = gstAmt.toFixed(2);
  document.getElementById('total').textContent = total.toFixed(2);
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
    body: JSON.stringify({ name, mobile, table_id: +state.tableId, items })
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
