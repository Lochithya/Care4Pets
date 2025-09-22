// Cart functionality
const messageBar = document.getElementById('message-bar');

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
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            
            // get the parent cart item container instead of always the first one
            const cartItem = this.closest(".carts");

            const quantityInput = cartItem.querySelector(".quantity");
            const quantity = parseInt(quantityInput.value);
            updateCartQuantity(productId,quantity);
        });
    });

    // Remove item buttons
    const removeItemButtons = document.querySelectorAll('.remove-item-btn');
    removeItemButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            removeFromCart(productId);
        });
    });

    // for cartTotal 
    document.querySelectorAll('.select-item').forEach(checkbox => {
        checkbox.addEventListener("change",updateSelectedTotal) ;                       // for updating total for selected items
    })

    // Checkout button
    const checkoutButton = document.querySelector('.checkout');
    checkoutButton.addEventListener("click", function(){
        const selectedIds = []; 
        
        document.querySelectorAll('.select-item:checked').forEach(
            checkbox => selectedIds.push(checkbox.value)                         // pushing the ids of the selected products to the array
        )
        if(selectedIds.length === 0 ){
            alert("You must select at least a product to proceed");
        }
        else{
            if(confirm('Do you actually want to proceed?')){
                const cartTotal = document.querySelector('.cart-total-amount').textContent ;          //current cartTotal in summary section 
                
                const form = document.createElement('form');                 // creation of the form to pass in to the server
                form.method = 'POST' ;                                       // POST method since using sensitive data
                form.action = 'shipping.php' ; 

                selectedIds.forEach( id => {
                    const input = document.createElement('input') ;             // for each product id , creating input element
                    input.type = 'hidden'                                      // makes input field invisibe to the user because we are not actually showing the form to the user
                    input.name = 'product_ids[]';                 // to treat all inputs with this name as an array 
                    input.value = id ;                            
                    form.appendChild(input) ;                     // each input is appended as childs to the form
                })

                const total = document.createElement('input');                   // element for the total to be passed
                total.type = 'hidden' ;  
                total.name = 'cart_total' ; 
                total.value = cartTotal ;                               // assigning the last cart total to the element
                form.appendChild(total) ;
                
                document.body.appendChild(form);                    // adding the created form to the HTML document's body
                form.submit() ;                                     // submitting the form to the respective location using repsective method


            }
        }


    })
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
            showMessage(data.message , 'success');   // for successful messages
        } else {
            showMessage(data.message,'error') ;     // for error messages
        }
    })
    .catch(() => {
        showMessage('Error adding item to the cart !','error') ;   
    });
}

// Update cart item quantity
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
            showMessage(data.message,'success') ;   // for successful messages
        } else {
            showMessage(data.message,'error') ;     // for error messages
        }
    })
    .catch(() => {
        showMessage('Error updating the cart !','error') ;   
    });
}

// Remove item from cart
function removeFromCart(productId) {
    if (confirm('Are you sure you want to remove this item ?')) {
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
                showMessage(data.message,'success') ;   // for successful messages
            } else {
                showMessage(data.message,'error') ;     // for error messages
            }
        })
        .catch(() => {
            showMessage('Error removing from the cart !','error') ;   
        });
    }
}
function clearCart(){
    if(confirm('Are you sure you want to clear the cart.This process can not be undone ?')){
        fetch('../api/cart_actions.php',{
            method: 'POST',
            headers : {
                'Content-type': 'application/x-www-form-urlencoded',
            },
            body: 'action=clear'
        })
        .then(response => response.json())
        .then(data=>{                                            // this is the response sent from cart_actions.php
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
}

//updateSelectedTotal
function updateSelectedTotal(){
    const selectedIds = [];                      // for making an array for selected arrays

    document.querySelectorAll('.select-item:checked').forEach(checkbox=>{
        selectedIds.push(checkbox.value);                                            // if checked , pushing the product_id to array
    })

    let newSelectedIds = selectedIds.map( id => 'product_ids[]='+id).join('&') ;            // should have like product_ids[]=1&product_ids[]=2&...

    fetch('../api/cart_actions.php' , {
        method : 'POST',
        headers : {'Content-type' : 'application/x-www-form-urlencoded'},
        body : 'action=selected&'+newSelectedIds           //new URLSearchParams({'product_ids[]' : selectedIds}) will not work because only selects the first element  
    })
    .then(response => response.json())
    .then( data => {                                    // part of the repsonse object
        cartTotal = document.querySelector('.cart-total-amount') ;  
        cartTotal.textContent = parseFloat(data.message).toFixed(2) ;
    }) ;
}


function showMessage(message, type) {
    const messageBar = document.getElementById('message-bar');
    messageBar.innerHTML = `
        <span>${message}</span>
        <button class="close-btn">❌ </button>       
    `;                                                   // adding the button along with the mesagebar

    messageBar.className = 'message-bar ' + type; // reset + apply type , then two classes are included
    messageBar.style.display = 'block';

    // Attach close button event
    const closeBtn = messageBar.querySelector('.close-btn');
    closeBtn.addEventListener('click', () => {
    messageBar.style.display = 'none';

    // 🟢 delay එකක් දීලා reload
    setTimeout(()=>{
        location.reload();
    }, 500);  // 0.5 seconds delay, userට UI effect එක smooth
});
  

}    



