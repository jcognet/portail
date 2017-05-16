<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>QuaggaJS, an advanced barcode-reader written in JavaScript</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="copyright" content="This project is maintained by Christoph Oberhofer" />
    <meta name="description" content="QuaggaJS is an advanced barcode-reader written in JavaScript" />
    <meta name="keywords" content="barcode, javascript, canvas, computer vision, image processing, ean, code128" />
    <meta name="robots" content="index,follow" />

    <link rel="canonical" href="https://serratus.github.io/v1.0.0-beta.1/examples/file-input/" />
    <link rel="stylesheet" href="https://serratus.github.io/quaggaJS/stylesheets/styles.css">
    <link rel="stylesheet" href="https://serratus.github.io/quaggaJS/stylesheets/example.css">
    <link rel="stylesheet" href="https://serratus.github.io/quaggaJS/stylesheets/pygment_trac.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="wrapper">
      <header>
        <h1><a href="/quaggaJS/"><img src="/quaggaJS/assets/code128.png" alt="QuaggaJS" /></a></h1>
        <p>An advanced barcode-reader written in JavaScript</p>

        <nav class="side-nav">
            <ul class="pages">
                <li><a href="/quaggaJS/">Project Home</a></li>
                <li><a href="/quaggaJS/examples">Examples</a></li>
                <li><a href="/quaggaJS/v1.0.0-beta.1/examples">Examples (v1.0.0-beta)</a></li>
            </ul>
        </nav>

        <p class="view"><a href="https://github.com/serratus/quaggaJS">View the Project on GitHub <small>serratus/quaggaJS</small></a></p>
        <ul class="github">
          <li><a href="https://github.com/serratus/quaggaJS/zipball/master">Download <strong>ZIP File</strong></a></li>
          <li><a href="https://github.com/serratus/quaggaJS/tarball/master">Download <strong>TAR Ball</strong></a></li>
          <li><a href="https://github.com/serratus/quaggaJS">Fork On <strong>GitHub</strong></a></li>
        </ul>
      </header>
      <section>
        <h1>Examples</h1>

<ul>
    <li><a href="../sandbox/">Mobile Sandbox</a> for playing around with all the various settings</li>
    <li><a href="../scan-to-input/">Video-based Example</a> showcasing an input field for barcodes</li>
    <li><a href="../file-input/">File-based Example</a> showcasing an input field for barcodes</li>
    <li><a href="../multiple/">Video-based Example</a> showcasing the use of multiple readers</li>
    <li><a href="../static_images/">Demo with sample images</a></li>
    <li><a href="../live_w_locator/">Demo with live-stream</a> using <code>getUserMedia</code></li>
    <li><a href="../file_input/">Demo with file-input</a> showcasing a use for mobile</li>
</ul>

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
    <p>This example demonstrates the following features:
        <ul>
            <li>Use static image as source</li>
            <li>Configuring EAN-Reader</li>
            <li>Use custom mount-point (Query-Selector)</li>
        </ul>
    </p>
</section>

<script src="https://serratus.github.io/quaggaJS/v1.0.0-beta.1/examples/js/quagga.js" type="text/javascript"></script>
<script src="https://serratus.github.io/quaggaJS/v1.0.0-beta.1/examples/file-input/index.js" type="text/javascript"></script>
<script src="https://serratus.github.io/quaggaJS/v1.0.0-beta.1/examples/js/prism.js"></script>


      </section>
      <footer>
        <p>This project is maintained by <a href="http://github.com/serratus">Christoph Oberhofer</a></p>
        <p><small>Hosted on GitHub Pages &mdash; Theme by <a href="https://github.com/orderedlist">orderedlist</a></small></p>
      </footer>
    </div>
    <script src="https://serratus.github.io/quaggaJS/javascripts/scale.fix.js"></script>
  </body>
</html>
