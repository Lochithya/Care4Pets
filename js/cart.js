// Cart functionality
const messageBar = document.getElementById('message-bar');
let pendingAction = null;
let previousState = null;

// Confirmation Modal Functions
function showConfirmation(title, message, icon, onConfirm, onCancel) {
    const overlay = document.getElementById('confirmOverlay');
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmIcon').textContent = icon;
    
    overlay.classList.add('active');
    
    const confirmBtn = document.getElementById('confirmOk');
    const cancelBtn = document.getElementById('confirmCancel');
    
    // Remove previous listeners by cloning
    const newConfirmBtn = confirmBtn.cloneNode(true);
    const newCancelBtn = cancelBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
    
    newConfirmBtn.addEventListener('click', function() {
        overlay.classList.remove('active');
        if (onConfirm) onConfirm();
    });
    
    newCancelBtn.addEventListener('click', function() {
        overlay.classList.remove('active');
        if (onCancel) onCancel();
    });
}

// Save cart item state
function saveCartItemState(cartItem) {
    return {
        quantity: cartItem.querySelector('.item-quantity-section .quantity').value,
        totalPrice: cartItem.querySelector('.item-total-section .total-price').textContent
    };
}

// Restore cart item state
function restoreCartItemState(cartItem, state) {
    cartItem.querySelector('.item-quantity-section .quantity').value = state.quantity;
    cartItem.querySelector('.item-total-section .total-price').textContent = state.totalPrice;
}

// Update total price display for a quantity input
function updateTotalPrice(quantityInput) {
    const cartItem = quantityInput.closest('.cart-item');
    const totalPrice = cartItem.querySelector('.item-total-section .total-price');
    const unitPrice = parseFloat(quantityInput.dataset.price);
    
    let qty = parseInt(quantityInput.value) || 1;
    if (qty < 1) {
        qty = 1;
    }
    totalPrice.textContent = "$" + (unitPrice * qty).toFixed(2);
}

document.addEventListener('DOMContentLoaded', function() {
    // Add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');                    
            addToCart(productId, 1);
        });
    });

    // Update quantity buttons
    const updateQuantityButtons = document.querySelectorAll('.update-quantity-btn');
    updateQuantityButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            const cartItem = this.closest(".cart-item");
            const quantityInput = cartItem.querySelector(".item-quantity-section .quantity");
            const quantity = parseInt(quantityInput.value);
            const originalQuantity = quantityInput.getAttribute('data-original-quantity');
            
            showConfirmation(
                '📝 Update Quantity',
                `Are you sure you want to update the quantity to ${quantity} for this item?`,
                '📝',
                () => {
                    // ONLY execute fetch after confirmation
                    updateCartQuantity(productId, quantity);
                },
                () => {
                    // Restore original value on cancel
                    quantityInput.value = originalQuantity;
                    updateTotalPrice(quantityInput);
                }
            );
        });
    });

    // Remove item buttons
    const removeItemButtons = document.querySelectorAll('.remove-item-btn');
    removeItemButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            const cartItem = this.closest(".cart-item");
            const productName = cartItem.querySelector('.item-details h3').textContent;
            
            showConfirmation(
                '🗑️ Remove Item',
                `Are you sure you want to remove "${productName}" from your cart?`,
                '🗑️',
                () => {
                    // ONLY execute fetch after confirmation
                    removeFromCart(productId);
                },
                () => {
                    // Nothing to restore on cancel
                }
            );
        });
    });

    // Delete cart button
    const deleteCartBtn = document.querySelector('.delete-cart');
    if (deleteCartBtn && deleteCartBtn.offsetParent !== null) { // Check if visible (not empty cart page)
        deleteCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showConfirmation(
                '⚠️ Clear Cart',
                'Are you sure you want to delete all items from your cart? This action cannot be undone.',
                '⚠️',
                () => {
                    // ONLY execute fetch after confirmation
                    clearCart();
                },
                () => {
                    // Nothing to restore, just close modal
                }
            );
        });
    }

    // for cartTotal 
    document.querySelectorAll('.select-item').forEach(checkbox => {
        checkbox.addEventListener("change", updateSelectedTotal);
    })

    // Checkout button
    const checkoutButton = document.querySelector('.checkout');
    if (checkoutButton) {
        checkoutButton.addEventListener("click", function(e) {
            e.preventDefault();
            const selectedIds = []; 
            
            document.querySelectorAll('.select-item:checked').forEach(
                checkbox => selectedIds.push(checkbox.value)
            );
            
            if(selectedIds.length === 0) {
                showConfirmation(
                    '⚠️ No Items Selected',
                    'You must select at least one product to proceed to checkout.',
                    '⚠️',
                    () => {
                        document.getElementById('confirmOverlay').classList.remove('active');
                    }
                );
            }
            else {
                showConfirmation(
                    '✓ Proceed to Checkout',
                    'Are you sure you want to proceed to checkout with the selected items?',
                    '✓',
                    () => {
                        const cartTotal = document.querySelector('.cart-total-amount').textContent;
                        
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'shipping.php';

                        selectedIds.forEach( id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'product_ids[]';
                            input.value = id;
                            form.appendChild(input);
                        })

                        const total = document.createElement('input');
                        total.type = 'hidden';
                        total.name = 'cart_total';
                        total.value = cartTotal;
                        form.appendChild(total);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                );
            }
        });
    }
});

// Add item to cart
function addToCart(productId, quantity) {
    fetch('../api/cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=add&product_id=' + productId + '&quantity=' + quantity
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message , 'success');
        } else {
            showMessage(data.message,'error') ;
        }
    })
    .catch(() => {
        showMessage('Error adding item to the cart !','error') ;   
    });
}

// Update cart item quantity - ONLY called after confirmation
function updateCartQuantity(productId, quantity) {
    fetch('../api/cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=update&product_id=' + productId + '&quantity=' + quantity
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message,'success') ;
        } else {
            showMessage(data.message,'error') ;
        }
    })
    .catch(() => {
        showMessage('Error updating the cart !','error') ;
    });
}

// Remove item from cart - ONLY called after confirmation
function removeFromCart(productId) {
    fetch('../api/cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=remove&product_id=' + productId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message,'success') ;
        } else {
            showMessage(data.message,'error') ;
        }
    })
    .catch(() => {
        showMessage('Error removing from the cart !','error') ;   
    });
}

// Clear cart - ONLY called after confirmation
function clearCart(){
    fetch('../api/cart_actions.php',{
        method: 'POST',
        headers : {
            'Content-type': 'application/x-www-form-urlencoded',
        },
        body: 'action=clear'
    })
    .then(response => response.json())
    .then(data=>{
        if(data.success){
            showMessage(data.message,'success');
        }
        else{
            showMessage(data.message,'error');
        }
    })
    .catch(() =>{
        showMessage('Error deleting the cart !','error');
    })
}

//updateSelectedTotal
function updateSelectedTotal(){
    const selectedIds = [];

    document.querySelectorAll('.select-item:checked').forEach(checkbox=>{
        selectedIds.push(checkbox.value);
    })

    let newSelectedIds = selectedIds.map( id => 'product_ids[]='+id).join('&') ;

    fetch('../api/cart_actions.php' , {
        method : 'POST',
        headers : {'Content-type' : 'application/x-www-form-urlencoded'},
        body : 'action=selected&'+newSelectedIds
    })
    .then(response => response.json())
    .then( data => {
        cartTotal = document.querySelector('.cart-total-amount') ;  
        cartTotal.textContent = parseFloat(data.message).toFixed(2) ;
    }) ;
}


function showMessage(message, type) {
    // Remove any existing toast
    const existing = document.getElementById('site-toast');
    if (existing) existing.remove();

    const isSuccess = type === 'success';
    const toast = document.createElement('div');
    toast.id = 'site-toast';
    toast.className = 'site-toast ' + (isSuccess ? 'toast-success' : 'toast-error');
    toast.innerHTML = `
        <div class="toast-inner">
            <div class="toast-icon">${isSuccess ? '&#10003;' : '&#10007;'}</div>
            <div class="toast-msg">${message}</div>
            <button class="toast-close" id="toast-close-btn">&times;</button>
        </div>
    `;
    document.body.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => toast.classList.add('toast-visible'));

    function dismissToast() {
        toast.classList.remove('toast-visible');
        setTimeout(() => {
            toast.remove();
            location.reload();
        }, 300);
    }

    // Close button triggers reload
    document.getElementById('toast-close-btn').addEventListener('click', dismissToast);

    // Auto-dismiss after 3.5s also reloads
    setTimeout(dismissToast, 3500);
}


