(function () {
  'use strict';

  const STORAGE_KEY = 'ctc_service_cart_v1';

  function startCart() {
    const products = Array.isArray(window.CTC_PRODUCTS) ? window.CTC_PRODUCTS : [];
    const productMap = new Map(products.map((p) => [String(p.id), p]));

    const cartCountEls = document.querySelectorAll('[data-cart-count]');
    const cartItemsEl = document.querySelector('[data-cart-items]');
    const cartTotalEl = document.querySelector('[data-cart-total]');
    const checkoutCartInput = document.querySelector('#checkout-cart-json');
    const checkoutForm = document.querySelector('#ctc-checkout-form');
    const checkoutBtn = document.querySelector('#ctc-checkout-button');
    const checkoutStatus = document.querySelector('#ctc-checkout-status');
    const clearBtn = document.querySelector('[data-clear-cart]');

    function setStatus(message) {
      if (checkoutStatus) checkoutStatus.textContent = message || '';
    }

    function money(cents) {
      return '$' + (Number(cents || 0) / 100).toLocaleString(undefined, {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
      });
    }

    function loadCart() {
      try {
        const parsed = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        return Array.isArray(parsed) ? parsed : [];
      } catch (e) {
        return [];
      }
    }

    function cleanCart(cart) {
      return cart
        .map((item) => ({ id: String(item.id || ''), qty: Number(item.qty || 0) }))
        .filter((item) => productMap.has(item.id) && item.qty > 0);
    }

    function saveCart(cart) {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(cleanCart(cart)));
      render();
    }

    function getQty(cart) {
      return cleanCart(cart || loadCart()).reduce((sum, item) => sum + Number(item.qty || 0), 0);
    }

    function total(cart) {
      return cleanCart(cart).reduce((sum, item) => {
        const p = productMap.get(item.id);
        return sum + (p ? Number(p.price || 0) * item.qty : 0);
      }, 0);
    }

    function addToCart(id) {
      id = String(id || '');
      const p = productMap.get(id);
      if (!p) return;

      const cart = cleanCart(loadCart());
      const existing = cart.find((item) => item.id === id);
      if (existing) existing.qty += 1;
      else cart.push({ id, qty: 1 });

      saveCart(cart);
      setStatus(p.name + ' added to cart.');
      document.querySelector('#ctc-cart')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function setQty(id, qty) {
      id = String(id || '');
      const cart = cleanCart(loadCart())
        .map((item) => (item.id === id ? { ...item, qty: Math.max(0, Number(qty || 0)) } : item))
        .filter((item) => item.qty > 0);
      saveCart(cart);
    }

    function clearCart() {
      localStorage.removeItem(STORAGE_KEY);
      setStatus('Cart cleared.');
      render();
    }

    function render() {
      const cart = cleanCart(loadCart());
      cartCountEls.forEach((el) => (el.textContent = getQty(cart)));
      if (cartTotalEl) cartTotalEl.textContent = money(total(cart));
      if (checkoutCartInput) checkoutCartInput.value = JSON.stringify(cart);

      if (!cartItemsEl) return;

      if (cart.length === 0) {
        cartItemsEl.innerHTML = '<div class="ctc-empty">Your cart is empty. Add a service to get started.</div>';
        return;
      }

      cartItemsEl.innerHTML = cart
        .map((item) => {
          const p = productMap.get(item.id);
          return `<div class="ctc-cart-item">
            <div>
              <div class="ctc-cart-title">${escapeHtml(p.name)}</div>
              <div class="ctc-cart-meta">${money(p.price)} × ${item.qty}</div>
              <div class="ctc-cart-actions">
                <button type="button" data-cart-dec="${escapeAttr(item.id)}" aria-label="Decrease ${escapeAttr(p.name)}">−</button>
                <span>${item.qty}</span>
                <button type="button" data-cart-inc="${escapeAttr(item.id)}" aria-label="Increase ${escapeAttr(p.name)}">+</button>
                <button type="button" class="ctc-remove-btn" data-cart-remove="${escapeAttr(item.id)}">Remove</button>
              </div>
            </div>
            <strong>${money(Number(p.price || 0) * item.qty)}</strong>
          </div>`;
        })
        .join('');
    }

    function escapeHtml(value) {
      return String(value).replace(/[&<>'"]/g, (ch) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[ch]));
    }

    function escapeAttr(value) {
      return escapeHtml(value).replace(/`/g, '&#096;');
    }

    function submitCheckout(event) {
      if (event) {
        event.preventDefault();
        event.stopPropagation();
        if (typeof event.stopImmediatePropagation === 'function') event.stopImmediatePropagation();
      }

      const cart = cleanCart(loadCart());
      if (cart.length === 0) {
        alert('Add at least one service before checkout.');
        setStatus('Add at least one service before checkout.');
        return false;
      }

      if (checkoutBtn) {
        checkoutBtn.disabled = true;
        checkoutBtn.textContent = 'Opening secure checkout...';
      }
      setStatus('Opening secure checkout...');

      const checkoutUrl = checkoutForm ? checkoutForm.action : '/checkout.php';
      const body = new URLSearchParams();
      body.set('cart', JSON.stringify(cart));
      body.set('ajax', '1');

      fetch(checkoutUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
          'Accept': 'application/json'
        },
        body: body.toString(),
        credentials: 'same-origin'
      })
        .then(async (response) => {
          const text = await response.text();
          let data = {};
          try { data = JSON.parse(text); }
          catch (err) { throw new Error(text.replace(/<[^>]*>/g, ' ').trim() || 'Checkout returned an invalid server response.'); }
          if (!response.ok || !data.ok) { throw new Error(data.error || 'Checkout failed.'); }
          if (!data.url) { throw new Error('Stripe did not return a checkout URL.'); }
          window.location.href = data.url;
        })
        .catch((error) => {
          if (checkoutBtn) {
            checkoutBtn.disabled = false;
            checkoutBtn.textContent = 'Checkout Securely';
          }
          setStatus('Checkout error: ' + error.message);
          console.error('CTC checkout error:', error);
          alert('Checkout error: ' + error.message);
        });

      return false;
    }

    window.ctcSubmitCheckout = submitCheckout;

    document.addEventListener('click', function (e) {
      const checkoutClick = e.target.closest('#ctc-checkout-button');
      if (checkoutClick) return submitCheckout(e);

      const addBtn = e.target.closest('[data-add-cart]');
      if (addBtn) return addToCart(addBtn.dataset.addCart);

      const inc = e.target.closest('[data-cart-inc]');
      if (inc) {
        const item = cleanCart(loadCart()).find((i) => i.id === inc.dataset.cartInc);
        return setQty(inc.dataset.cartInc, (item?.qty || 0) + 1);
      }

      const dec = e.target.closest('[data-cart-dec]');
      if (dec) {
        const item = cleanCart(loadCart()).find((i) => i.id === dec.dataset.cartDec);
        return setQty(dec.dataset.cartDec, (item?.qty || 0) - 1);
      }

      const rem = e.target.closest('[data-cart-remove]');
      if (rem) return setQty(rem.dataset.cartRemove, 0);
    }, true);

    clearBtn?.addEventListener('click', clearCart);
    checkoutForm?.addEventListener('submit', submitCheckout, true);

    render();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startCart);
  } else {
    startCart();
  }
})();
