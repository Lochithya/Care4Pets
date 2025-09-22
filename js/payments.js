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


// now the complete validation upon submitting the 'complete order' button

    form.addEventListener('submit',function(e){
        e.preventDefault();
        const payment_type = document.querySelector('input[name="payment_type"]:checked').value ;
        let finalCardNumber = document.getElementById('card_number').value ;        // has spaces
        finalCardNumber = finalCardNumber.replace(/\D/g , '');
        finalCardNumber = finalCardNumber.length ;                        // after removing the white spaces and get the length

        if(payment_type === 'card'){                     // actual validation starts from
            
            if(!cardType.value){
                alert('Please select a card type');
                cardType.focus();
                return ;
            }
            
            switch(cardType.value){
                case 'visa' :  case 'mastercard' : 
                    if(finalCardNumber !== 16){
                        alert(cardType.value+' card must have 16 digits'); 
                        cardNumber.focus(); 
                        return ;
                    } 
                    break ;

                case 'american-express' : 
                    if(finalCardNumber !==15){
                        alert(cardType.value+' card must have 15 digits');
                        cardNumber.focus();
                        return ;
                    }
                    break ;

                case 'maestro' :
                    if(finalCardNumber !== 19){
                        alert(cardType.value+' card must have 19 digits');
                        cardNumber.focus();
                        return;
                    } 
                    break ;
                    
                default : alert('Please select a card first') ; cardNumber.focus(); break ;
            }
            

            // for expiry validation
            const finalExpiry = expiry.value ;                     
            
            if(!/^\d{2}\/\d{2}$/.test(finalExpiry)){
                alert("Invalid format. Use MM/YY");
                expiry.focus();
                return ; 
            }

            const [mm,yy] = finalExpiry.split('/').map( e => parseInt(e,10)) ;        // dividing into an array
            
            if(mm<1 || mm>12){
                alert('Invalid month. Should be between 01-12');
                expiry.focus();
                return ;
            }

            const now = new Date();
            const currentMonth = now.getMonth()+1 ;         // between 1-12
            const currentYear = now.getFullYear()%100 ;      // for last 2 digits

            if(yy < currentYear || (yy == currentYear && mm<currentMonth)){
                alert('card expired. Enter a valid card');
                expiry.focus();
                return ; 
            }

            // ------------------------------------

            // for ccv code

            if(ccv.value.length !==3 && ccv.value.length !==4 ){
                alert('Enter a valid ccv code for the card');
                ccv.focus();
                return ;
            }
            //--------------

        }
        if(confirm('Do you want to complete the order ?')){

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
                    alert('Error saving order : '+(data.message || 'Unknown error'));
                }
            })
            .catch(() =>{
                alert('Something went wrong while saving your order');
            });
        }

    })

});

