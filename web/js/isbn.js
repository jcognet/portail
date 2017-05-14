
var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
var arg = {
    resultFunction: function(result) {
      /*
       result.format: code format,
       result.code: decoded string,
       result.imgData: decoded image data
       */
        var aChild = document.createElement('li');
        aChild[txt] = result.format + ': ' + result.code;
        document.querySelector('body').appendChild(aChild);
    }
};

var args = {
    autoBrightnessValue: 100,
    resultFunction: function(res) {
        new Ajax().POST('com.php', {
            params: {
                order: 'save_data',
                img: res.imgData,
                txt: res.format + ": " + res.code,
                app: 'WebCodeCamJS'
            }
        }, function() {}, true);
        [].forEach.call(scannerLaser, function(el) {
            fadeOut(el, 0.5);
            setTimeout(function() {
                fadeIn(el, 0.5);
            }, 300);
        });
        scannedImg.src = res.imgData;
        scannedQR[txt] = res.format + ": " + res.code;
    },
    getDevicesError: function(error) {
        var p, message = "Error detected with the following parameters:\n";
        for (p in error) {
            message += p + ": " + error[p] + "\n";
        }
        alert(message);
    },
    getUserMediaError: function(error) {
        var p, message = "Error detected with the following parameters:\n";
        for (p in error) {
            message += p + ": " + error[p] + "\n";
        }
        alert(message);
    },
    cameraError: function(error) {
        var p, message = "Error detected with the following parameters:\n";
        if (error.name == "NotSupportedError" || (error.name == "PermissionDeniedError" && error.message == "Only secure origins are allowed (see: https://goo.gl/Y0ZkNV).")) {
            var ans = confirm("Your browser does not support getUserMedia via HTTP!\n(see: https://goo.gl/Y0ZkNV).\n You want to see github demo page in a new window?");
            if (ans) {
                window.open("https://andrastoth.github.io/webcodecamjs/");
            }
        } else {
            for (p in error) {
                message += p + ": " + error[p] + "\n";
            }
            alert(message);
        }
    },
    cameraSuccess: function() {
        grabImg.classList.remove("disabled");
    }
};

/* -------------------------------------- Available parameters --------------------------------------*/
var options = {
    DecodeQRCodeRate: 5,                    // null to disable OR int > 0 !
    DecodeBarCodeRate: 5,                   // null to disable OR int > 0 !
    successTimeout: 500,                    // delay time when decoding is succeed
    codeRepetition: true,                   // accept code repetition true or false
    tryVertical: true,                      // try decoding vertically positioned barcode true or false
    frameRate: 15,                          // 1 - 25
    width: 320,                             // canvas width
    height: 240,                            // canvas height
    constraints: {                          // default constraints
        video: {
            mandatory: {
                maxWidth: 1280,
                maxHeight: 720
            },
            optional: [{
                sourceId: true
            }]
        },
        audio: false
    },
    flipVertical: false,                    // boolean
    flipHorizontal: false,                  // boolean
    zoom: -1,                               // if zoom = -1, auto zoom for optimal resolution else int
    beep: 'audio/beep.mp3',                 // string, audio file location
    decoderWorker: DECODER_WORKER_ASSET,   // string, DecoderWorker file location
    brightness: 0,                          // int
    autoBrightnessValue: false,             // functional when value autoBrightnessValue is int
    grayScale: false,                       // boolean
    contrast: 0,                            // int
    threshold: 0,                           // int
    sharpness: [],      // to On declare matrix, example for sharpness ->  [0, -1, 0, -1, 5, -1, 0, -1, 0]
    resultFunction: function(result) {
      /*
       result.format: code format,
       result.code: decoded string,
       result.imgData: decoded image data
       */
        alert(result.code);
    },
    cameraSuccess: function(stream) {           //callback funtion to camera success
        console.log('cameraSuccess');
    },
    canPlayFunction: function() {               //callback funtion to can play
        console.log('canPlayFunction');
    },
    getDevicesError: function(error) {          //callback funtion to get Devices error
        console.log(error);
    },
    getUserMediaError: function(error) {        //callback funtion to get usermedia error
        console.log(error);
    },
    cameraError: function(error) {              //callback funtion to camera error
        console.log(error);
    }
};

/*------------------------ Example initializations jquery & Javascript version ------------------------*/
var canvas = document.querySelector('#webcodecam-canvas');
new WebCodeCamJS(canvas).init();
var decoder = new WebCodeCamJS('#webcodecam-canvas');
decoder.buildSelectMenu('#camera-select'); //sel : default camera optional
/* Chrome & ': build select menu
 *  Firefox: the default camera initializes, return decoder object
 */
//simple initialization
decoder.init();
/* Select environment camera if available */
decoder.buildSelectMenu('#camera-select', 'environment|back').init(args);
/* Select user camera if available */
decoder.buildSelectMenu('#camera-select', 'user|front').init(args);
/* Select camera by name */
decoder.buildSelectMenu('#camera-select', 'facecam').init(args);
/* Select first camera */
decoder.buildSelectMenu('#camera-select', 0).init(args);
/* Select environment camera if available, without visible select menu*/
decoder.buildSelectMenu(document.createElement('select'), 'environment|back').init().play();

/* --------------------------------------- Available Functions: ----------------------------------------*/
/* camera stop & delete stream */
decoder.stop();
/* camera play, restore process */
decoder.play();
/* get current image from camera */
decoder.getLastImageSrc();
/* decode local image */
/* if url is defined download image before staring open process */
if (typeof url != 'undefined')
    decoder.decodeLocalImage(url);
/* get optimal zoom */
decoder.getOptimalZoom();
/* Configurable options */
decoder.options['parameter'];
/* Example:
 ** decoder.options.brightness = 20;         - set brightness to 20
 ** decoder.options.DecodeQRCodeRate = null; - disable qrcode decoder
 */