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
    <script>
        var host = "serratus.github.io";
        if ((host == window.location.host) && (window.location.protocol != "https:")) {
            window.location.protocol = "https";
        }
    </script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-56318310-1', 'auto');
      ga('send', 'pageview');

    </script>
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
    <div class="source-code">
        <h4>Source</h4>
        <div class="collapsable-source">
            <pre>
                <code class="language-javascript">
var Quagga = window.Quagga;
var App = {
    _scanner: null,
    init: function() {
        this.attachListeners();
    },
    decode: function(src) {
        Quagga
            .decoder({readers: ['ean_reader']})
            .locator({patchSize: 'medium'})
            .fromImage(src, {size: 800})
            .toPromise()
            .then(function(result) {
                document.querySelector('input.isbn').value = result.codeResult.code;
            })
            .catch(function() {
                document.querySelector('input.isbn').value = "Not Found";
            })
            .then(function() {
                this.attachListeners();
            }.bind(this));
    },
    attachListeners: function() {
        var self = this,
            button = document.querySelector('.input-field input + .button.scan'),
            fileInput = document.querySelector('.input-field input[type=file]');

        button.addEventListener("click", function onClick(e) {
            e.preventDefault();
            button.removeEventListener("click", onClick);
            document.querySelector('.input-field input[type=file]').click();
        });

        fileInput.addEventListener("change", function onChange(e) {
            e.preventDefault();
            fileInput.removeEventListener("change", onChange);
            if (e.target.files && e.target.files.length) {
                self.decode(URL.createObjectURL(e.target.files[0]));
            }
        });
    }
};
App.init();
                </code>
            </pre>
        </div>
        <div class="collapsable-source">
            <pre>
                <code class="language-html">
&lt;form&gt;
    &lt;div class=&quot;input-field&quot;&gt;
        &lt;label for=&quot;isbn_input&quot;&gt;EAN:&lt;/label&gt;
        &lt;input id=&quot;isbn_input&quot; class=&quot;isbn&quot; type=&quot;text&quot; /&gt;
        &lt;button type=&quot;button&quot; class=&quot;icon-barcode button scan&quot;&gt;&amp;nbsp;&lt;/button&gt;
        &lt;input type=&quot;file&quot; id=&quot;file&quot; capture/&gt;
    &lt;/div&gt;
&lt;/form&gt;
                </code>
            </pre>
        </div>
    </div>
</section>

<script src="../js/quagga.js" type="text/javascript"></script>
<script src="index.js" type="text/javascript"></script>
<script src="../js/prism.js"></script>


      </section>
      <footer>
        <p>This project is maintained by <a href="http://github.com/serratus">Christoph Oberhofer</a></p>
        <p><small>Hosted on GitHub Pages &mdash; Theme by <a href="https://github.com/orderedlist">orderedlist</a></small></p>
      </footer>
    </div>
    <script src="https://serratus.github.io/quaggaJS/javascripts/scale.fix.js"></script>
  </body>
</html>
