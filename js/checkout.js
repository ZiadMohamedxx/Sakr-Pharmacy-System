console.log('Checkout script initialized');
console.log('Debug Info - COD Form:', !!document.getElementById('cod-form'), 
            '| Credit Form:', !!document.getElementById('credit-card-form'),
            '| Payment Methods:', document.querySelectorAll('.payment-method').length);

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded - initializing checkout');
    
    // 1. Payment Method Handling ==============================================
    function initializePaymentMethods() {
        console.log('Initializing payment methods...');
        
        const paymentMethods = document.querySelectorAll('.payment-method');
        const codForm = document.getElementById('cod-form');
        const creditForm = document.getElementById('credit-card-form');

        if (!paymentMethods.length || !codForm || !creditForm) {
            console.error('Critical elements missing for payment processing');
            return;
        }

        // Set COD as default
        function setActiveMethod(method) {
            console.log('Setting active method:', method);
            
            // Update UI state
            paymentMethods.forEach(btn => {
                btn.classList.toggle('active', btn.dataset.method === method);
            });
            
            // Toggle forms
            codForm.style.display = method === 'cod' ? 'block' : 'none';
            creditForm.style.display = method === 'credit' ? 'block' : 'none';
            
            // Force reflow for transitions
            void codForm.offsetWidth;
            void creditForm.offsetWidth;
        }

        // Event listeners
        paymentMethods.forEach(method => {
            method.addEventListener('click', function() {
                setActiveMethod(this.dataset.method);
            });
        });

        // Initialize
        setActiveMethod('cod');
    }

    // 2. Input Formatting ====================================================
    function initializeInputFormatters() {
        console.log('Setting up input formatters...');
        
        // Credit card number formatting
        const cardNumberInput = document.getElementById('card_number');
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', function() {
                let value = this.value.replace(/\s+/g, '');
                if (value.length > 0) {
                    value = value.match(/.{1,4}/g)?.join(' ') || '';
                }
                this.value = value;
            });
        }

        // Expiry date formatting
        const expiryInput = document.getElementById('expiry');
        if (expiryInput) {
            expiryInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                this.value = value;
            });
        }
    }

    // 3. Form Validation =====================================================
    function initializeFormValidation() {
        console.log('Setting up form validation...');
        
        const checkoutForm = document.getElementById('checkout-form');
        if (!checkoutForm) return;

        checkoutForm.addEventListener('submit', function(e) {
            console.log('Form submission intercepted');
            let isValid = true;
            const errors = [];

            // Validate required fields
            this.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('error');
                    errors.push(`${field.name || field.id} is required`);
                    isValid = false;
                } else {
                    field.classList.remove('error');
                }
            });

            // Credit card specific validation
            const activeMethod = document.querySelector('.payment-method.active');
            if (activeMethod?.dataset.method === 'credit') {
                console.log('Validating credit card...');
                
                const cardNumber = document.getElementById('card_number')?.value.replace(/\s+/g, '');
                const expiry = document.getElementById('expiry')?.value;
                const cvv = document.getElementById('cvv')?.value;

                if (!/^\d{16}$/.test(cardNumber)) {
                    document.getElementById('card_number').classList.add('error');
                    errors.push('Card number must be 16 digits');
                    isValid = false;
                }

                if (!/^\d{2}\/\d{2}$/.test(expiry)) {
                    document.getElementById('expiry').classList.add('error');
                    errors.push('Expiry must be in MM/YY format');
                    isValid = false;
                }

                if (!/^\d{3,4}$/.test(cvv)) {
                    document.getElementById('cvv').classList.add('error');
                    errors.push('CVV must be 3-4 digits');
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                console.error('Validation errors:', errors);
                alert(`Please fix these issues:\n${errors.join('\n')}`);
            } else {
                console.log('Form validation passed');
            }
        });
    }

    

    // Initialize all components ==============================================
    try {
        initializePaymentMethods();
        initializeInputFormatters();
        initializeFormValidation();
        convertCurrencyToEGP();
        console.log('Checkout initialization complete');
    } catch (error) {
        console.error('Initialization error:', error);
        alert('Cart added');
    }
});

// Fallback for DOMContentLoaded
if (document.readyState !== 'loading') {
    document.dispatchEvent(new Event('DOMContentLoaded'));
}