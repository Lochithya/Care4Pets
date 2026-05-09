document.addEventListener('DOMContentLoaded',function(){

    let radios = document.querySelectorAll('input[name="payment_type"]');      // either card or cash radio button is selected
    let cardFields = document.getElementById('cardFields') ;

    function toggleCards(){
        const result =  document.querySelector('input[name="payment_type"]:checked').value ;
        cardFields.style.display = (result === 'card') ? 'block' : 'none' ;

        const cardInputs = cardFields.querySelectorAll('input, select');                // getting all the element fields in the form
        cardInputs.forEach( input =>{
            input.required = (result === 'card') ;              // either true or false set to the required attribute
            if(result === 'cash'){
                input.value =''; 
            }
        }) ;

    }

    radios.forEach( radio =>
        radio.addEventListener('change',toggleCards) ) ;

    

// for validating and formatting card number 
    let form = document.getElementById('paymentForm');
    let cardNumber = document.getElementById('card_number');
    let cardType = document.getElementById('card_type');
    let expiry = document.getElementById('card_expiry') ;        // max - length is set to be 4
    let ccv = document.getElementById('card_cvv') ;


    // first make sure a card_type is selected , until that cardNumber entering should be disabled

    cardNumber.disabled = true ;

    cardType.addEventListener('change',()=>{
        if(cardType.value) {                               //if something is selected as the type of card
            cardNumber.disabled = false ; 
            cardNumber.value = '';
            cardNumber.placeholder = getPlaceholder(cardType.value);
        }    
        else{
            cardNumber.disabled = true; 
            cardNumber.value = '' ; 
            cardNumber.placeholder = 'Select a card first' ; 
        }
    })

    function getPlaceholder(type){
        switch(type){
            case 'visa' : 
            case 'mastercard' : return '**** **** **** ****' ; 

            case 'american-express' : return '**** **** **** ***';

            case 'maestro' : return '**** **** **** **** ***';

            default : return 'Enter card number';
        }
    }

    cardNumber.addEventListener('input',formatNumber);               // once inputtable , check for the event

    function formatNumber(){
        value = cardNumber.value.replace(/\D/g,'') ;           // replacing non-digits with blanks
        let maxlength = 19 ;         // default maxlength in the input box

        switch(cardType.value){
            case 'visa' : 
            case 'mastercard': maxlength = 16 ; break ;

            case 'american-express' : maxlength = 15 ; break ; 

            case 'maestro' : maxlength = 19 ; break ;
        }

        value = value.substring(0,maxlength);         // once the card Type is selected , maxlength is set accordingly
        
        cardNumber.value = value.replace(/(.{4})/g , '$1 ').trim();             // formatting of the card Number

    }

// for validating and formatting expiration date

    expiry.addEventListener('input',()=>{
        let value = expiry.value.replace(/\D/g , '') ;         // reomving of non-digits

        if(value.length == 4 ){
            value = value.substring(0,4) ;
            expiry.value = value.substring(0,2)+'/'+value.substring(2);
        }
        else{
            value = value.substring(0,4);
            expiry.value = value ;          // normal value being entered
        }
    })

// for formatting the ccv code(removal of non-digits)
    
    ccv.addEventListener('input',()=>{
        ccv.value = ccv.value.replace(/\D/g , '');        // removal of non-digits
    })


    // Toast message helper
    function showToast(msg, type = 'error') {
        if (window.showMessage) {
            window.showMessage(msg, type);
        } else {
            const bar = document.getElementById('message-bar');
            if (bar) {
                bar.textContent = msg;
                bar.className = 'message-bar ' + type;
                bar.style.display = 'block';
                setTimeout(() => { bar.style.display = 'none'; }, 3500);
            } else {
                alert(msg);
            }
        }
    }

    form.addEventListener('submit', function(e){
        e.preventDefault();
        const payment_type = document.querySelector('input[name="payment_type"]:checked').value ;
        
        if(payment_type === 'card'){
            let finalCardNumber = document.getElementById('card_number').value.replace(/\D/g , '');
            
            if(!cardType.value){
                showToast('Please select a card type');
                cardType.focus();
                return ;
            }
            
            const expectedLength = {
                'visa': 16,
                'mastercard': 16,
                'american-express': 15,
                'maestro': 19
            };

            if(finalCardNumber.length !== expectedLength[cardType.value]){
                showToast(`${cardType.value.charAt(0).toUpperCase() + cardType.value.slice(1)} card must have ${expectedLength[cardType.value]} digits`);
                cardNumber.focus(); 
                return ;
            }

            const finalExpiry = expiry.value ;                     
            if(!/^\d{2}\/\d{2}$/.test(finalExpiry)){
                showToast("Invalid expiry format. Use MM/YY");
                expiry.focus();
                return ; 
            }

            const [mm,yy] = finalExpiry.split('/').map( e => parseInt(e,10)) ;
            if(mm < 1 || mm > 12){
                showToast('Invalid month. Should be between 01-12');
                expiry.focus();
                return ;
            }

            const now = new Date();
            const currentMonth = now.getMonth()+1 ;
            const currentYear = now.getFullYear()%100 ;

            if(yy < currentYear || (yy == currentYear && mm < currentMonth)){
                showToast('This card has expired. Please use a valid card.');
                expiry.focus();
                return ; 
            }

            if(ccv.value.length !== 3 && ccv.value.length !== 4 ){
                showToast('Enter a valid CVV code (3 or 4 digits)');
                ccv.focus();
                return ;
            }
        }

        // Professional Confirmation Modal
        if (window.showGlobalConfirmation) {
            window.showGlobalConfirmation(
                '🛍️ Complete Your Order',
                'Are you sure you want to finalize your purchase? This will process your payment and place the order.',
                '🛍️',
                () => {
                    // Show processing toast
                    showToast('Processing your order...', 'success');
                    
                    fetch('../includes/payment_process.php',{
                        method : "post", 
                        body : new FormData(form) 
                    })
                    .then(response => response.json())
                    .then(data=> {
                        if (data.success){
                            window.location.href = 'order_successful.php?order_id=' +data.order_id ;
                        }
                        else{
                            showToast('Error saving order : '+(data.message || 'Unknown error'), 'error');
                        }
                    })
                    .catch(() =>{
                        showToast('Something went wrong while saving your order', 'error');
                    });
                }
            );
        } else {
            // Fallback
            if(confirm('Do you want to complete the order?')){
                form.submit();
            }
        }
    });

});

