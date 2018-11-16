
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

            // Create a new worker which will be in charge of parsing the uploaded file
            let worker = new Worker('/js/worker.js');

            let uploadedFile = docElement.files[0];

            // Once the worker is done parsing the file, upload the data
            worker.onmessage = function(e) {
                postDoc(form, uploadedFile.name, e.data, uploadedFile.size);
            };

            // Tell the worker to start parsing the uploaded file
            worker.postMessage(uploadedFile);

        });
    }

    // Post the document to the server
    let postDoc = function (form, name, sha256, size) {

        form.querySelector('[name=name]').setAttribute('value', name);
        form.querySelector('[name=sha256]').setAttribute('value', sha256);
        form.querySelector('[name=size]').setAttribute('value', size);

        form.submit();
    }

}).call(this);
