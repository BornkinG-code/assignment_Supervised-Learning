(() => {
  const modal = document.getElementById('orderModal');
  if (!modal) {
    return;
  }

  const modalOverlay = document.getElementById('orderModalOverlay');
  const modalClose = document.getElementById('orderModalClose');
  const modalMeta = document.getElementById('orderModalMeta');
  const modalItems = document.getElementById('orderModalItems');
  const viewButtons = document.querySelectorAll('.view-order-btn');

  function openModal(button) {
    const orderId = button.getAttribute('data-order-id') || 'Unknown Order';
    const customer = button.getAttribute('data-customer') || 'Customer';
    const rawItems = button.getAttribute('data-items') || '[]';

    let items;
    try {
      items = JSON.parse(rawItems);
    } catch (error) {
      items = [];
    }

    modalMeta.textContent = `${orderId} · ${customer}`;
    modalItems.innerHTML = '';

    if (!Array.isArray(items) || items.length === 0) {
      const emptyItem = document.createElement('li');
      emptyItem.textContent = 'No items found for this order.';
      modalItems.appendChild(emptyItem);
    } else {
      items.forEach((item) => {
        const listItem = document.createElement('li');
        const name = item.name || 'Unnamed item';
        const qty = Number.isFinite(item.qty) ? item.qty : item.qty || 1;
        listItem.textContent = `${name} × ${qty}`;
        modalItems.appendChild(listItem);
      });
    }

    modal.hidden = false;
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.hidden = true;
    document.body.style.overflow = '';
  }

  viewButtons.forEach((button) => {
    button.addEventListener('click', () => openModal(button));
  });

  if (modalOverlay) {
    modalOverlay.addEventListener('click', closeModal);
  }

  if (modalClose) {
    modalClose.addEventListener('click', closeModal);
  }

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !modal.hidden) {
      closeModal();
    }
  });
})();
