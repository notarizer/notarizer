
/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require('./bootstrap');

(function() {

    // Registers a new upload form with the given form element and name of document input
    window.registerUploadForm = function(form, docElement) {
        // Interrupt the form submission
        form.addEventListener('submit', (e) => {
            // Prevent the form from submitting regularly
            e.preventDefault();

            window.submitUpload(form, docElement);

            return false;
        });
    };

    window.submitUpload = function (form, docElement) {
        if (docElement.files.length === 0)
            return;

        setButtonStatus(docElement, form, true, false);
        
        // Create a new worker which will be in charge of parsing the uploaded file
        let worker = new Worker('/js/worker.js');

        let uploadedFile = docElement.files[0];

        // Once the worker is done parsing the file, upload the data
        worker.onmessage = function(e) {
            if (e.data.status === 'progress') {
                form.querySelector('.js-upload-status').style.background = 'linear-gradient(to right, #dd2b41 ' + e.data.progress + '%, #e8b7bd ' + (e.data.progress + 1) + '%)';
            } else if (e.data.status === 'done') {
                setButtonStatus(docElement, form, false, false);
                postDoc(form, uploadedFile.name, e.data.result, uploadedFile.size);
            }
        };

        // If something goes wrong, tell the user!
        worker.onerror = worker.onmessageerror = function(e) {
            console.error(e);
            setButtonStatus(docElement, form, false, true);
        };

        // Tell the worker to start parsing the uploaded file
        worker.postMessage(uploadedFile);
    };

    let preventPageChange = function (prevent) {
        if (prevent) {
            window.onbeforeunload = () => { return 'Are you sure you want to leave? A file upload is in progress.' };
        } else {
            window.onbeforeunload = null;
        }
    }

    let originalText = '';

    let setButtonStatus = function (docElement, form, disabled, failed) {
        if (disabled) {
            originalText = form.querySelector('.js-upload-status').innerHTML;

            form.querySelector('.js-errors').classList.add('hidden');
            form.querySelector('.js-upload-status').innerHTML = 'Your file is processing...';
            form.querySelector('.js-upload-status').classList.add('cursor-not-allowed');
        } else {
            if (failed) {
                form.querySelector('.js-errors').classList.remove('hidden');
                form.querySelector('.js-errors').innerHTML = 'Whoops! Something went wrong. Try again, and if the issue persists. Please <a class="text-white" href="/contact">Contact support</a>.'; // TODO: Remove absolute URL reference.
            }

            form.querySelector('.js-upload-status').classList.remove('cursor-not-allowed');
            form.querySelector('.js-upload-status').innerHTML = originalText;
            form.querySelector('.js-upload-status').style.background = '';
        }

        docElement.disabled = disabled;
        preventPageChange(disabled);
    }

    // Post the document to the server
    let postDoc = function (form, name, sha256, size) {

        form.querySelector('[name=name]').setAttribute('value', name);
        form.querySelector('[name=sha256]').setAttribute('value', sha256);
        form.querySelector('[name=size]').setAttribute('value', size);

        form.submit();
    };

    window.copyEl = function (element, button, originalText) {
        element.select();
        document.execCommand('copy');

        originalText = originalText || button.innerHTML;

        button.innerHTML = 'Copied!';

        setTimeout((original, button) => {
            button.innerHTML = originalText;
        }, 2000, originalText, button);
    };

    window.initPaymentForm = function (paymentForm, stripeToken, email, stripeKey) {
        let handler = StripeCheckout.configure({
            key: stripeKey,
            locale: 'auto',
            zipCode: true,
            name: 'Notarizer',
            image: '/img/Square Logo@2x.png',
            description: 'One-time payment',
            token: function(token) {
                stripeToken.value = token.id;
                email.value = token.email;
                paymentForm.submit();
            }
        });

        paymentForm.addEventListener('submit', function(event) {
            event.preventDefault();

            paymentForm.querySelector('.js-error-message').innerHTML = '';
            paymentForm.querySelector('.js-error-message').classList.add('hidden');

            let amount = paymentForm.querySelector('input[name=amount]').value;
            amount = amount.replace(/\$/g, '').replace(/,/g, '');

            amount = parseFloat(amount);

            if (isNaN(amount)) {
                paymentForm.querySelector('.js-error-message').innerHTML = 'The amount you entered is not a number!';
                paymentForm.querySelector('.js-error-message').classList.remove('hidden');
            } else if (amount < 1.00) {
                paymentForm.querySelector('.js-error-message').innerHTML = 'The minimum donation amount is $1.00';
                paymentForm.querySelector('.js-error-message').classList.remove('hidden');
            } else {
                amount = amount * 100; // Convert from dollars to cents
                handler.open({
                    amount: Math.round(amount)
                });
            }
        });
    };

}).call(this);
