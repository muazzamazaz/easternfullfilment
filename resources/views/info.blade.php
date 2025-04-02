
<!DOCTYPE html>
<html lang="en">
<head>
   <style>
    .logo img {
      max-width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .bi-check2 {
      color: #FFA500;
    }
    .bs-body-bg{
        background: #2A3C5D;
    }
    /* Hide the button container initially */
    body, html {
        margin: 0;
        padding: 0;
        background: transparent; /* Set the background to transparent */
        overflow: hidden;
    }
    
    @media only screen and (max-width: 768px) {
    body, html {
        overflow: auto; /* Enable scrolling on smaller screens */
    }

    /* Style for input fields */
    form {
      margin: 100px;
    }

    .input-field {
      position: relative;
      width: 250px;
      height: 44px;
      line-height: 44px;
      margin-bottom: 40px; /* Increased spacing between input fields */
      opacity: 0; /* Initially hide the input fields */
    }

    label {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      color: #FFA500; /* Set label color to white */
      transition: 0.2s all;
      cursor: text;
    }

    input {
      width: 100%;
      border: 0;
      outline: 0;
      padding: 0.5rem 0;
      border-bottom: 2px solid transparent;
      box-shadow: none;
      background-color: transparent; /* Set background color to transparent */
      color: #fff; /* Set text color */
    }

    input:invalid {
      outline: 0;
      color: #FFA500; /* Change color for invalid input */
      border-color: #FFA500;
    }

    input:focus,
    input:valid {
      border-color: #00dd22;
    }

    input:focus~label,
    input:valid~label {
      font-size: 14px;
      top: -24px;
      color: #00dd22;
    }

    /* Apply animation to reveal input fields */
    .reveal {
      opacity: 1;
      animation: fadeIn 0.5s ease forwards;
    }

    
    /* Media query for smaller screens */
    @media only screen and (max-width: 768px) {
      form {
        margin: 0 auto; /* Center the form */
        width: 80%; /* Adjust the width of the form */
        padding-top: 20px;
      }

      .input-field {
        width: 100%; /* Make input fields full width */
      }
      
      #cta-btn {
        display: none; /* Hide the CTA button on smaller screens */
      }
    }

    /* Animation keyframes */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
   </style>
      
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Eastern Fulfillment Co - Index</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <!-- Bootstrap CSS -->
  <!-- Bootstrap Icons CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <link href="{{ asset('new_assets')}}/assets/img/favicon.png" rel="icon">
  <link href="{{ asset('new_assets')}}/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('new_assets')}}/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="{{ asset('new_assets')}}/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('new_assets')}}/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ asset('new_assets')}}/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="{{ asset('new_assets')}}/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="{{ asset('new_assets')}}/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="{{ asset('new_assets')}}/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  
  <!-- Calendly badge widget begin -->
  
  <!-- Template Main CSS File -->
  <link href="{{ asset('new_assets')}}/assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Arsha
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top ">
    <div class="container  align-items-center">
      <center><a href="{{url('/')}}" class="logo me-auto"><img src="{{ asset('new_assets')}}/assets/img/KEYSHOT.png" alt="" class="img-fluid"/></a></center>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo me-auto"><img src="{{ asset('new_assets')}}/assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar">
        <ul>
        </ul>
      </nav>
      <div class="navbar-mobile"></div>
    </div>
  </header><!-- End Header -->
  
  <!-- ======= Hero Section ======= -->
  <!-- ======= Hero Section ======= -->
  <section id="cta" class="cta" >
    <center>
        <div class="container" data-aos="zoom-in">
            <div class="container-fluid px-lg-0 py-3 py-lg-5 bg-white-gradient">
              <div class="container-md">
                <div class="row" style="padding-top: 100px;">
                  <div class="col-12 col-lg-6 order-1"  >
                   
                  </div>
                  
                  <div class="col-12 d-flex flex-column flex-md-row justify-content-between overview mt-3 mt-lg-5 order-2 order-lg-3">
                    <div><h3>Receiving</h3>
                      <div>
                        <h4 style="color: #FFA500;">$25 / hour</h4><br><br>
                        <h3>3PL Storage</h3>
                        <h4 style="color: #FFA500;">$25 PER SKU</h4><br><br>
                    </div>
                     
                    </div>
                    <div class="mx-md-3"><h3>PREP Services</h3>
                      <div>
                        <h4 style="color: #FFA500;">$0.50 per item < 2lbs <br> $1.00 per item > 2lbs 
                        </h4><br><br>
                        
                    </div>
                    <h3>FBA Box Labeling</h3>
                    <h4 style="color: #FFA500;">$3 per Box <br> $5 per Pallet
                    </h4><br><br>
                    </div>
                    <div>
                      <h3>Long Term Storage</h3>
                      <div>
                        <h4 style="color: #FFA500;">$45 per Pallet (Max 180 Days)
                        </h4><br><br>
                        <h3>Short Term Storage</h3>
                        <h4 style="color: #FFA500;">$35 per Pallet (Max 90 Days)</h4><br><br>
                    </div>
                    <h3>Order Fulfillment</h3>
                    <h4 style="color: #FFA500 ; margin-bottom: -10px;">Starts at $6.75
                    </h4>


          </div>
          
          </div>
          <div></div>
          
        </div>
          
      </div>
      <center>
        <div class="col-lg-3 col-md-6 cta-btn-container text-center mt-3 mx-auto" id="cta-btn">
          <a class="purple-link large-link position-relative d-inline-block" style="color: #FFA500; padding-top: 50px;" href="{{route('data3')}}">
              <b>Next</b>
              <svg aria-hidden="true" style="color: white;" focusable="false" data-prefix="fas" data-icon="angle-right" class="svg-inline--fa fa-angle-right fa-w-8 ms-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                  <path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z">
                  </path>
              </svg>
          </a>
      </div>
      
      
      </center>
      </div>
      </div>
      </div>
      
      </center>
  </section><!-- End Hero -->
  

 

  <!-- Vendor JS Files -->
  
  <script src="{{ asset('new_assets')}}/assets/vendor/aos/aos.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('new_assets')}}/assets/js/main.js"></script>

  <!-- Script to reveal input fields one by one -->
  <script>
  
  window.addEventListener('load', function() {
    window.scrollTo(0, 0);
});
  
    document.addEventListener("DOMContentLoaded", function () {
      animateInputs();
    });

    function animateInputs() {
      const inputFields = document.querySelectorAll('.input-field');
      let index = 0;

      function revealInput() {
        if (index < inputFields.length) {
          inputFields[index].classList.add('reveal');
          index++;
        } else {
          // Reveal the button after all inputs are revealed
          document.getElementById('cta-btn').style.display = 'inline-block';
        }
      }

      revealInput();
    }

    function revealNextInput(event, nextIndex) {
      const inputField = event.target.parentElement.nextElementSibling;
      if (event.target.value.trim() !== '') {
        inputField.classList.add('reveal');
        if (nextIndex === 6) {
          // Reveal the button when the last input field is revealed
          document.getElementById('cta-btn').style.display = 'inline-block';
        }
      }
    }
  </script>
</body>
</html>