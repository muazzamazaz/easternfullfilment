<!DOCTYPE html>
<html lang="en">
<head>
   <style>
    .logo img {
      max-width: 100%;
      height: 100%;
      object-fit: contain;
      overflow: hidden;
    }

    .bi-check2 {
      color: #FFA500;
    }

    /* Hide the button container initially */
    
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
    <div class="container  align-items-center" style="padding-bottom: 200px;">
      <center></center>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar">
        <ul>
        </ul>
      </nav>
      <div class="navbar-mobile"></div>
    </div>
  </header><!-- End Header -->
  
  <!-- ======= Hero Section ======= -->
  <section id="hero" class="align-items-center" style="padding-top: 100px;">
    <center>
      <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
        <img src="{{asset('assets/images/KEYSHOT.1 (1).png')}}" class="img-fluid animated" alt="">
      </div>
      <div class="col-lg-3  cta-btn-container text-center input-field " style="margin-left: -20px;" id="cta-btn"  >
        <a class="purple-link large-link position-relative d-inline-block mt-3" style="color: #FFA500;" href="{{route('register_eastern')}}"><b>Next</b><svg aria-hidden="true" style="color: white;" focusable="false" data-prefix="fas" data-icon="angle-right" class="svg-inline--fa fa-angle-right fa-w-8 ms-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg></a>
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

  <!-- Script to reveal button after text animation -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      animateText();
    });

    function animateText() {
      const text = "Welcome to Eastern Fulfillment Suite.";
      const titleElement = document.getElementById('hero-title');
      titleElement.textContent = ''; // Clear existing text
      let index = 0;

      function type() {
        if (index < text.length) {
          titleElement.textContent += text.charAt(index);
          index++;
          setTimeout(type, 50);
        } else {
          // Reveal the button after text animation completes
          document.getElementById('cta-btn').style.display = 'inline-block';
        }
      }

      type();
    }
  </script>
</body>
</html>
