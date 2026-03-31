const state={tableId:null,cart:{},menu:{}};
const q = new URLSearchParams(location.search);
state.tableId = q.get('table_id');

async function fetchMenu(){
  const res=await fetch(`../api/get_menu.php?table_id=${state.tableId}`);
  const data=await res.json();
  if(!data.success){alert(data.message);return}
  state.menu=data.menu;
  document.getElementById('tableName').textContent=data.table.table_name;
  document.getElementById('gstPercent').value=data.gst_percent || 5;
  renderMenu();
  renderCart();
}

function renderMenu(){
  const wrap=document.getElementById('menuWrap');wrap.innerHTML='';
  Object.entries(state.menu).forEach(([cat,items])=>{
    const section=document.createElement('section');
    section.innerHTML=`<h3>${cat}</h3><div class='grid'>${items.map(i=>`
      <div class='card'>
        <img src='../${i.image_path || 'assets/images/placeholder.svg'}' alt='${i.item_name}'>
        <div class='card-body'><h4>${i.item_name}</h4><p>${i.description||''}</p>
        <p>₹${i.price}</p><button class='btn' ${+i.is_available? '' : 'disabled'} onclick='addToCart(${i.id})'>${+i.is_available? 'Add':'Sold out'}</button></div>
      </div>`).join('')}</div>`;
    wrap.appendChild(section);
  })
}
function addToCart(id){state.cart[id]=(state.cart[id]||0)+1;renderCart()}
function minusFromCart(id){if(!state.cart[id]) return; state.cart[id]--; if(state.cart[id]<=0) delete state.cart[id]; renderCart()}

function renderCart(){
  const el=document.getElementById('cartItems');
  let subtotal=0; const lines=[];
  Object.values(state.menu).flat().forEach(i=>{if(state.cart[i.id]){const qty=state.cart[i.id];const line=qty*parseFloat(i.price);subtotal+=line;lines.push(`<div class='row'><span>${i.item_name} x${qty}</span><span>₹${line.toFixed(2)}</span><button class='btn alt' onclick='minusFromCart(${i.id})'>-</button></div>`)}})
  const gst=Number(document.getElementById('gstPercent').value||5);
  const gstAmt=subtotal*gst/100; const total=subtotal+gstAmt;
  el.innerHTML=lines.join('') || '<p>Cart empty</p>';
  document.getElementById('subtotal').textContent=subtotal.toFixed(2);
  document.getElementById('gstAmt').textContent=gstAmt.toFixed(2);
  document.getElementById('total').textContent=total.toFixed(2);
}

async function placeOrder(){
  const name=document.getElementById('name').value.trim();
  const mobile=document.getElementById('mobile').value.trim();
  if(!name||!/^[6-9]\d{9}$/.test(mobile)){alert('Enter valid name and mobile');return}
  const items=Object.entries(state.cart).map(([id,qty])=>({id:+id,qty}));
  if(!items.length){alert('Cart empty');return}
  const res=await fetch('../api/place_order.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name,mobile,table_id:+state.tableId,items})});
  const data=await res.json();
  if(!data.success){alert(data.message);return}
  localStorage.setItem('last_order_code',data.order_code);
  location.href=`order_status.php?order_code=${data.order_code}`;
}

fetchMenu();
