<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
  <title>StellarShift - <?php echo $title ?></title>
  <!-- Default Meta Tags -->
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="title" content="Transforming the way you work and officiate: Save, spend and exchange across digital wallets with StellarShift.">
  <meta name="description" content="StellarShift is a cutting-edge web app that empowers new startups and existing companies to regulate and manage their staff, build extensive work reports, and monitor work activities. With automated features and seamless functionality, StellarShift simplifies day-to-day operations, enabling businesses to optimize productivity and streamline their workflow. Experience the transformative power of StellarShift and elevate your company's success.">
  <meta name="author" content="StellarShift">
  <meta name="keywords" content="StellarShift, web app, startup, existing companies, staff management, work reports, work activities, automation, day-to-day operations">
  <!-- Google / Search Engine Tags -->
  <meta itemprop="title" content="Create and Fund Dollar Debit Cards with NGN and USDT: Save, spend and exchange across digital wallets with StellarShift.">
  <meta itemprop="description" content="StellarShift is a cutting-edge web app that empowers new startups and existing companies to regulate and manage their staff, build extensive work reports, and monitor work activities. With automated features and seamless functionality, StellarShift simplifies day-to-day operations, enabling businesses to optimize productivity and streamline their workflow. Experience the transformative power of StellarShift and elevate your company's success.">
  <meta itemprop="author" content="StellarShift">

  <!-- Facebook Meta Tags -->
  <meta property="og:author" content="StellarShift">
  <meta property="og:title" content="Create and Fund Dollar Debit Cards with NGN and USDT: Save, spend and exchange across digital wallets.">
  <meta property="og:description" content="Home - Create and Fund Dollar Debit Cards with NGN and USDT: Save, spend and exchange across digital wallets with StellarShift">
  <meta property="og:url" content="https://app.StellarShift.co/" />
  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="StellarShift" />
  <meta property="og:image" content="https://app.StellarShift.co/assets/images/abc.svg" />
  <meta property="og:image:alt" content="StellarShift Logo">

  <!-- Twitter Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="@StellarShiftafrica">
  <meta name="twitter:creator" content="@StellarShiftafrica">
  <meta name="twitter:title" content="Home - Create and Fund Dollar Debit Cards with NGN and USDT: Save, spend and exchange across digital wallets with StellarShift.">
  <meta name="twitter:description" content="Get a virtual dollar card in Nigeria with NGN and USDT on StellarShift">
  <link rel="shortcut icon" href="./assets/media/logos/favicon.png" />
  <!--begin::Fonts(mandatory for all pages)-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
  <!--end::Fonts-->
  <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
  <link href="./assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
  <link href="./assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
  <link href="./assets/css/default.css" rel="stylesheet" type="text/css" />
  <link href="./assets/css/override_general.css" rel="stylesheet" type="text/css" />
  <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Theme mode setup on page load-->
<script>
  var defaultThemeMode = "light";
  var themeMode;
  if (document.documentElement) {
    if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
      themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
    } else {
      if (localStorage.getItem("data-bs-theme") !== null) {
        themeMode = localStorage.getItem("data-bs-theme");
      } else {
        themeMode = defaultThemeMode;
      }
    }
    if (themeMode === "system") {
      themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }
    document.documentElement.setAttribute("data-bs-theme", themeMode);
  }
</script>
<!--begin::Body-->

<body id="kt_body" class="app-blank">