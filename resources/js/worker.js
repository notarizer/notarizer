import CryptoJS from "crypto-js";

onmessage = function(e) {
    // https://stackoverflow.com/questions/29731329/cryptojs-sha256-large-file-progressive-checksum
    // TODO: Clean up

    let chunkSize = 10000000;

    let sha256 = CryptoJS.algo.SHA256.create();
    let sha256Update;
    let checksum = [];

    let uploadedFile = e.data;

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

        let chunk = fileReader.readAsArrayBuffer(uploadedFilePart);
        let chunkUint8 = new Uint8Array(chunk);
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
            postMessage(sha256Update._hash.toString(CryptoJS.enc.Hex));
        }
    }
};
