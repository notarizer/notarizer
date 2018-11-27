import sha256, { Hash, HMAC } from "fast-sha256";

onmessage = function(e) {

    let chunkSize = 256 * 1000;

    let file = e.data;
    let fileSize = (file.size - 1);
    const hasher = new sha256.Hash();

    for (let i = 0; i < fileSize; i += chunkSize) {
        let reader = new FileReaderSync();
        let blob = reader.readAsArrayBuffer(file.slice(i, chunkSize + i));

        hasher.update(new Uint8Array(blob));

        postMessage({
            status: 'progress',
            progress: (i / fileSize * 100)
        });
    }

    let result = array2hex(hasher.digest());

    postMessage({
        status: 'done',
        result: result
    });
};

let array2hex = function (array) {
    let hexArr = [];

    for (let item of array) {
        const hex = item.toString(16);

        const paddedHex = ('00' + hex).slice(-2);

        hexArr.push(paddedHex);
    }

    return hexArr.join('');
}
