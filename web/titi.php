<!doctype html>
<html>
  <head>
  
  </head>
  <body>
    <div class="wrapper">
      <section>
        <h1>Examples</h1>


<link rel="stylesheet" type="text/css" href="https://serratus.github.io/quaggaJS/stylesheets/prism.css" />
<style>
    input[type=file] {
        display: none;
    }
</style>
<section id="container" class="container">
    <h3>Scan barcode to input-field</h3>
    <p>Click the <strong>button</strong> next to the input-field
        to select a file or snap a picture</p>
    <div>
        <form>
            <div class="input-field">
                <label for="isbn_input">EAN:</label>
                <input id="isbn_input" class="isbn" type="text" />
                <button type="button" class="icon-barcode button scan">&nbsp;</button>
                <input type="file" id="file" capture/>
            </div>
        </form>
    </div>

</section>

<script src="https://serratus.github.io/quaggaJS/v1.0.0-beta.1/examples/js/quagga.js" type="text/javascript"></script>
<script src="./js/quagga.js" type="text/javascript"></script>
<script src="https://serratus.github.io/quaggaJS/v1.0.0-beta.1/examples/js/prism.js"></script>


      </section>
    </div>
  </body>
</html>
