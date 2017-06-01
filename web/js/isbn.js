var Quagga = window.Quagga;
var App = {
    _scanner: null,
    init: function () {
        this.attachListeners();
    },
    decode: function (src) {
        Quagga
            .decoder({readers: ['code_128_reader', 'ean_reader', 'ean_8_reader', 'code_39_reader', 'code_39_vin_reader', 'codabar_reader', 'upc_reader', 'upc_e_reader', 'i2of5_reader']})
            .locator({patchSize: 'medium'})
            .fromSource(src)
            .toPromise()
            .then(function (result) {
               $('#input_isbn').value = result.codeResult.code;
            })
            .catch(function () {
                console.log("erreur")
                $('#input_isbn').val("Code bar non reconnu");
            })
            .then(function () {
                this.attachListeners();
            }.bind(this));
    },
    attachListeners: function () {
        var self = this,
            button = $('#button_isbn'),
            fileInput = $('#file_isbn');

        button.on("click", function (e) {
            $('#button_isbn').attr('disabled', true);
            e.preventDefault();
            $('#file_isbn').click();
        });

        fileInput.on("change", function (e) {
            e.preventDefault();
            if (e.target.files && e.target.files.length) {
                self.decode(e.target.files[0]);
            }
            $('#button_isbn').attr('disabled', false);
        });
    }
};
App.init();
