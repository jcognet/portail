var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
var arg = {
    resultFunction: function(result) {
        var aChild = document.createElement('li');
        aChild[txt] = result.format + ': ' + result.code;
        document.querySelector('body').appendChild(aChild);
    }
};
var decoder = new WebCodeCamJS("canvas").buildSelectMenu('select', 'environment|back').init(arg).play();
/*  Without visible select menu
 var decoder = new WebCodeCamJS("canvas").buildSelectMenu(document.createElement('select'), 'environment|back').init(arg).play();
 */
document.querySelector('select').addEventListener('change', function(){
    decoder.stop().play();
});