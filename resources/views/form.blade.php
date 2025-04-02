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
        overflow: hidden; /* Default to hidden */
    }

    /* Style for input fields */
    form {
      max-width: 800px; /* Limit the form width */
      margin: 50px auto; /* Center the form */
      padding-top: 20px; /* Add padding on top */
    }

    /* Set transparent background for input field */
    .input-field input {
      background-color: transparent; /* Set background color to transparent */
      width: 100%;
      border: none;
      border-bottom: 2px solid #fff;
      color: #fff; /* Set text color */
      padding: 5px 0;
      margin-bottom: 20px; /* Add space between fields */
    }

    /* Set label style */
    label {
      color: #FFA500; /* Set label color to orange */
      display: block;
      margin-bottom: 5px;
    }

    /* Apply animation to reveal input fields */
    .reveal {
      opacity: 1;
      animation: fadeIn 0.5s ease forwards;
    }

    /* Style for the button */
    .cta-btn {
      display: block;
      width: 200px;
      margin: 20px auto;
      padding: 10px;
      background-color: #FFA500;
      color: #fff;
      text-align: center;
      text-decoration: none;
      border-radius: 5px;
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

    /* Media query for smaller screens */
    @media only screen and (max-width: 768px) {
      body, html {
        overflow: auto; /* Enable scrolling on mobile devices */
      }
      form {
        padding-top: 0;
        
        margin-top: -20px; /* Remove padding on top for mobile */
      }
      .input-field input {
        padding: 5px;
        font-size: 14px;
      }
      #cta-btn{
        padding-right: 70px;
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
  <header id="header" class="fixed-top " style="padding-top: 50px;">
    <div class="container  align-items-center">
      <center><a href="index.html" class="logo me-auto"><img src="KEYSHOT.1 (1).png" alt="" class="img-fluid"/></a></center>
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
  <section id="cta" class="cta" style="padding-top: 100px;">
    <center>
      <div class="container" >
        <div class="col-lg-12 justify-content-center pt-lg-0 order-1 order-lg-1" data-aos="fade-up" data-aos-delay="200">
          
            <!-- Input fields container -->
            <form action="" >
              <div class="row">
                <div class="col-md-6">
                  <div class="input-field reveal">
                    <label for="input1"><b>Email</b></label>
                    <input type="text" id="input1" required />
                  </div>
                  <div class="input-field reveal">
                    <label for="input2"><b>Name</b></label>
                    <input type="text" id="input2" required />
                  </div>
                  <div class="input-field reveal">
                    <label for="input3"><b>Company Name</b></label>
                    <input type="text" id="input3" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-field reveal">
                    <label for="input4"><b>Position In Company</b></label>
                    <input type="text" id="input4" required />
                  </div>
                  <div class="input-field reveal">
                    <label for="input5" style="font-size: small;"><b>Order Volume / Month</b></label>
                    <input type="text" id="input5" required />
                  </div>
                  <div class="input-field reveal">
                    <label for="input6"><b>Website</b></label>
                    <input type="text" id="input6" required />
                  </div>
                </div>
              </div>
              <div class="col-lg-3  cta-btn-container text-center input-field " style="padding-left: 70px;" id="cta-btn"  >
                <a class="purple-link large-link position-relative d-inline-block mt-3" style="color: #FFA500;" href="info.html"><b>Next</b><svg aria-hidden="true" style="color: white;" focusable="false" data-prefix="fas" data-icon="angle-right" class="svg-inline--fa fa-angle-right fa-w-8 ms-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg></a>
              </div>
            </center>

            </form>
            
          
        </div>
      </div>
    </center>
    
    <!-- Button container with initially hidden style -->
   
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
</body>
</html>