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


    /* Style for input fields */
   /* Custom styling for checkbox */
   .custom-checkbox .custom-control-label::before {
      border-radius: 5px;
      border: 1px solid #adb5bd;
    }
    .custom-checkbox .custom-control-label::after {
      content: "";
      border-radius: 3px;
    }
    .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
      background-color: #007bff;
      border-color: #007bff;
    }
    .custom-checkbox .custom-control-input:focus ~ .custom-control-label::before {
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    /* Custom styling for modal */
    .modal-content,
.modal-content h1,
.modal-content p,
.modal-content ul,
.modal-content li {
  color: white !important;
}

.modal-body {
  background-color: #37517E !important;
}
    .modal-header {
      border-bottom: none;
    }
    .modal-footer {
      border-top: none;
    }
    .form-check-label {
      color: white;
    }
    /* Custom styling for scrollbar */
    ::-webkit-scrollbar {
      width: 10px;
    }
    ::-webkit-scrollbar-track {
      background: #37517E;
    }
    ::-webkit-scrollbar-thumb {
      background: #fff;
      border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #0056b3;
    }
    /* Custom styling for close button */
    .btn-close {
      color: white !important;
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
      <center><a href="{{url('/')}}" class="logo me-auto"><img src="{{ asset('new_assets')}}/assets/img/KEYSHOT.1 (1).png" alt="" class="img-fluid"/></a></center>
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
  <section id="cta" class="cta">
    <center>
        <div class="container" data-aos="zoom-in">
            <div class="container-fluid px-lg-0 py-3 py-lg-5 bg-white-gradient">
              <div class="container-md">
                <div class="row">
                  <div class="col-12 col-lg-6 order-1" style="padding-top: 100px;">
                    <h2 class="mb-3"style="color: white;" ><b> Included in our<br> Fulfillment Partnership.</b>
                    </h2>
                   
                  </div>
                  <div class="col-12 col-lg-6 text-lg-end order-3 order-lg-2 py-4 py-lg-0">
                    <a class="purple-link large-link position-relative d-inline-block mt-3" data-bs-toggle="modal" data-bs-target="#termsModal" style="color: #FFA500;" href="">GET STARTED<svg aria-hidden="true" style="color: white;" focusable="false" data-prefix="fas" data-icon="angle-right" class="svg-inline--fa fa-angle-right fa-w-8 ms-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg></a>
                  </div>
                 
          
          <div class="col-12 d-flex flex-column flex-md-row justify-content-between overview mt-3 mt-lg-5 order-2 order-lg-3" style=" color: #fff;">
            
            <div>
          
              <i class="bi bi-check2"></i>
                            <h4>All Orders Dispatched Same Day If Received Before 1:30 Pm.

                            </h4>
                            <i class="bi bi-check2"></i>
                            <h4>Shipping Times Exclude Weekends.</h4>
                            <i class="bi bi-check2"></i>
                            <h4>These Prices Are Estimates Based On The Weight Of Product Described. </h4>
                            
            </div>
            <div class="mx-md-3">
              <i class="bi bi-check2"></i>
                            <h4>Dynamic Routing To All Outbound Packages Reducing Transit Times To 1 Day.


                            </h4>
                            <i class="bi bi-check2"></i>
                            <h4>These Prices Are Subject To Change By Carrier With 14 Days Prior Notice.

                            </h4>
                            <i class="bi bi-check2"></i>
                            <h4>Unlimited free Picks on orders

                            </h4></div>
            <div>
              <i class="bi bi-check2"></i>
                            <h4>2-4-hour Receiving Times

                            </h4>
                            <i class="bi bi-check2"></i>
                            <h4>10% Surcharge Will Be Added By Carrier In Peak Season (Oct - Jan).

                            </h4>
                            <i class="bi bi-check2"></i>
                            <h4>Alaska, Hawaii Orders May Take Longer UP TO 5 - 7 Business Days To Deliver.

                            </h4>
          </div>
          
          </div>
          
          </div>
          <center>
            <div class="col-lg-3 cta-btn-container text-center" id="cta-btn"  style=" padding-left: 60px; ">
              <a class="cta-btn align-middle" data-bs-toggle="modal" data-bs-target="#termsModal" href="" id="cta-btn" >Get Started</a>
            </div></center>
          </div>
          </div>
          </div>
          
          </center>
    <!-- Button container with initially hidden style -->
   
  </section><!-- End Hero -->
  


  <!-- Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">Terms of Service of Eastern Fulfillment Co</h5>
        <button type="button" style="background-color: #fff;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Insert all the provided terms and conditions text here -->
        <!-- Repeat the below format for all sections of the terms and conditions -->

        <h1>1. TERM</h1>
        <p><strong>Term.</strong> The term of this Agreement commences on the Effective Date and continues for the initial period of one (1) year, unless and until earlier terminated as provided under this Agreement or applicable law (“Term”). On expiration of the Term, this Agreement automatically renews for additional successive one (1) year terms unless and until either Party provides written Notice of termination at least thirty (30) days before the end of the then-current term, or unless and until earlier terminated as provided under this Agreement.</p>
        
        <h1>2. SERVICES</h1>
        <p><strong>Services.</strong> EASTERN FULFILLMENT CO will provide the following services to you;</p>
        <ul>
        <li>Receive shipments from Manufacture / Supplier of the Product</li>
        <li>Provide storage for the received inventory at the warehouse at monthly cost.</li>
        <li>We will perform pick, pack and shipping of the Products from the available Inventory and ship such products directly to the customer.</li>
        <li>Will provide and use standard shipping materials (boxes / Poly Mailers.) at our discretion.</li>
        <li>Will maintain summaries of all orders shipped and received for 120 days.</li>
        </ul>
        
        <h1>3. PAYMENT TERMS AND CONDITIONS</h1>
        <p><strong>Payments &amp; Invoicing.</strong> EASTERN FULFILLMENT CO utilizes auto pay model for the payment of services. A valid Credit / debit card is required to be kept on file and will be charged at the end of each business day. (Unless made arrangements for weekly invoicing.)</p>
        <p><strong>Options to Pay.</strong> EASTERN FULFILLMENT CO offers convenience to pay for invoices online through the following payment methods: Credit Card (4.5% processing fee), or a wire transfer (Free within the U.S.).</p>
        <p><strong>Privacy.</strong> EASTERN FULFILLMENT CO use a 3rd party payment processing company Stripe. EASTERN FULFILLMENT CO does not store any payment information on its own servers; all of the Company's payment information is stored with Stripe. If Company’s invoice remains unpaid for more than 2 days from the issue date, Company agrees that EASTERN FULFILLMENT CO shall have the right to auto-charge any payment method that has been used in the past.</p>
        
        <!-- Rest of the content omitted for brevity -->
        
        <h1>4. FORCE MAJEURE</h1>
        <p>EASTERN FULFILLMENT CO shall not be liable for any failure or delay in performance hereunder which may be due, in whole or in part, to fire, explosion, earthquake, storm, flood, drought or other adverse weather condition, accident, casualty, breakdown of machinery or facilities, strike, lockout, combination of workmen or other labor difficulties (from whatever cause arising, and whether or not the demands of the employees are reasonable or within  EASTERN FULFILLMENT CO’s power to grant), war, civil disturbance, acts of terrorism, insurrection, riot, act of God or the public enemy, law, act, order, proclamation, decree, regulation, ordinance, instruction or request of Government or other public authorities, judgment or decree of a court of competent jurisdiction, delay or failure of carriers, shippers or contractors, labor shortage or inability to obtain transportation, equipment, operating materials, plant equipment or materials required for our performance, curtailment or suspension of operations to remedy or avoid an actual or alleged violation or violations of Federal, State or local law, as may be in effect from time to time during the Agreement period, or any contingency or delay or failure or cause of any nature beyond the reasonable control of  EASTERN FULFILLMENT CO, whether or not of the kind hereinabove specified and whether or not any such contingency is presently occurring or occurs in the future.  EASTERN FULFILLMENT CO shall give notice of any force majeure event as soon as reasonably practicable by giving notice to your administrative email account.</p>
        
        <p>EASTERN FULFILLMENT CO may change or revise this Agreement at our discretion. If any change or revision to this Agreement is not acceptable to you, your only remedy is to stop using our Services and send a cancellation email to support@Easternfulfillment.com Otherwise, you will be bound by the changed or revised terms. EASTERN FULFILLMENT CO may change or revise this Agreement from time to time by providing ten (10) days prior notice either by emailing the email address associated with your account or by posting a notice on the Eastern Fulfillment coGo Dashboard. You can review the most current version of this Agreement at any time here or by logging into your account. Your use of the Services ten (10) days after this Notice shall constitute full acceptance of the revised or changed terms.</p>
        

        <!-- Repeat the above format for all sections of the terms and conditions -->

      </div>
      <div class="modal-footer">
        <div class="form-check custom-checkbox mb-3">
          <input type="checkbox" class="form-check-input custom-control-input" id="agreeCheckbox">
          <label class="form-check-label custom-control-label" for="agreeCheckbox">I agree to the terms and conditions</label>
        </div>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
     <!--   <button type="button" class="btn btn-primary" style="background-color: #2A3C5D;" id="agreeButton" disabled>Agree and Continue</button>-->
        <a href="{{ route('payment') }}" class="btn btn-primary" style="background-color: #2A3C5D;" id="agreeLink">Agree and Continue</a>
      </div>
    </div>
  </div>
</div>
 

  <!-- Vendor JS Files -->

  <script>

window.addEventListener('load', function() {
    window.scrollTo(0, 0);
});

  </script>
  
  
  <script src="{{ asset('new_assets')}}/assets/vendor/aos/aos.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="{{ asset('new_assets')}}/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('new_assets')}}/assets/js/main.js"></script><script src="assets/js/main.js"></script>

  <!-- Script to reveal input fields one by one -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Enable the button when the checkbox is checked
    document.getElementById('agreeCheckbox').addEventListener('change', function() {
      document.getElementById('agreeButton').disabled = !this.checked;
    });
  </script>
</body>
</html>