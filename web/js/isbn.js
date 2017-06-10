var Quagga = window.Quagga;
var App = {
    _scanner: null,
    init: function () {
        this.attachListeners();
    },
    decode: function (src, dest) {
        Quagga
            .decoder({readers: ['code_128_reader', 'ean_reader', 'ean_8_reader', 'code_39_reader', 'code_39_vin_reader', 'codabar_reader', 'upc_reader', 'upc_e_reader', 'i2of5_reader']})
            .locator({patchSize: 'medium'})
            .fromSource(src)
            .toPromise()
            .then(function (result) {
                dest.val(result.codeResult.code);
            })
            .catch(function () {
                dest.val("Code bar non reconnu");
            })
    },
    attachListeners: function () {
        var self = this,
            buttons = $('.isbn_button'),
            fileInputs = $('.isbn_file')
        ;

        buttons.on("click", function (e) {
            e.preventDefault();
            console.log($(this).parent().find('.isbn_file'));
            $(this).parent().find('.isbn_file').click();
        });

        fileInputs.on("change", function (e) {
            e.preventDefault();
            if (e.target.files && e.target.files.length) {
                console.log($(this).parent().find('.isbn_input'))
                self.decode(e.target.files[0], $(this).parent().find('.isbn_input'));
            }
        });
    }
};
$(document).ready(function(){
    App.init();
});

