
/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require('./bootstrap');

// import sha256 from 'crypto-js/sha256';
import CryptoJS from 'crypto-js';

// Wait for form submit to #document form
// Interrupt the submit and read the contents of the file
// Fetch the name, sha256, and file size from the file.
// Upload that information to a URI

let uploadFormEl = document.getElementById('upload');
let uploadFileEl = document.getElementById('doc');

uploadFormEl.addEventListener('submit', (e) => {
    e.preventDefault();

    let chunkSize = 1000000;

    let sha256 = CryptoJS.algo.SHA256.create();
    let sha256Update;
    let checksum = [];

    let uploadedFile = uploadFileEl.files[0];

    let fileName = uploadedFile.name;
    let fileSize = uploadedFile.size;

    let arrayLength = Math.ceil(fileSize / chunkSize);
    let fullData = new Array(arrayLength);
    let numComplete = 0;

    for (let i = 0; (i * chunkSize) < fileSize; i += 1) {
        let chunkStart = i * chunkSize;
        let chunkEnd = (chunkStart + chunkSize > fileSize)
            ? fileSize
            : chunkStart + chunkSize;

        // console.log("Index " + i + " chunkStart is " + chunkStart + ", chunkEnd: " + chunkEnd);

        let uploadedFilePart = uploadedFile.slice(chunkStart, chunkEnd);

        // console.log('Chunk data: ' + uploadedFilePart.size);

        let fileReader = new FileReaderSync();

        fileReader.onload = (function(filePart, fileName, fileSize, index) {
            // Do something with the file...

            return function (e) {
                // e.target.result : The file contents
                // file            : the uploaded file object

                // console.log(file.name + ': ' + e.target.result);
                // uploadFile(filePart.name, sha256(e.target.result), filePart.size);
                // console.log(fileName + ' : ' + fileSize + ' : ' + e.target.result);
                let chunkUint8 = new Uint8Array(e.target.result);
                let wordArr = CryptoJS.lib.WordArray.create(chunkUint8);
                // fullData[index] = e.target.result;
                sha256Update = sha256.update(wordArr);
                checksum.push(sha256Update);

                numComplete++;

                // Check if the array is filled
                if (numComplete === arrayLength) {
                    // let data = sha256(fullData.join(''));
                    // console.log(data);
                    // uploadFile(fileName, data, fileSize);
                    sha256Update.finalize();
                    console.log(sha256Update._hash.toString(CryptoJS.enc.Hex));
                }
            };
        })(uploadedFilePart, fileName, fileSize, i);

        fileReader.readAsArrayBuffer(uploadedFilePart);
    }



});

function uploadFile(name, sha256, size) {
    // console.log(name + ': ' + sha256 + ': ' + size);


    let nameField = document.createElement("input");
    nameField.setAttribute("type", "hidden");
    nameField.setAttribute("name", 'name');
    nameField.setAttribute("value", name);
    uploadFormEl.appendChild(nameField);

    let sha256Field = document.createElement("input");
    sha256Field.setAttribute("type", "hidden");
    sha256Field.setAttribute("name", 'sha256');
    sha256Field.setAttribute("value", sha256);
    uploadFormEl.appendChild(sha256Field);

    let sizeField = document.createElement("input");
    sizeField.setAttribute("type", "hidden");
    sizeField.setAttribute("name", 'size');
    sizeField.setAttribute("value", size);
    uploadFormEl.appendChild(sizeField);

    uploadFileEl.remove();

    uploadFormEl.submit();
}