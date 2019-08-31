<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>{{ env('APP_NAME', 'TM2') }} | @yield('title')</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/f4a826a612.js" integrity="sha384-tCIa9x35rFwAnPYwDCoqImEiB9Ai31jfzeslK4Q53KZAXSFi24Px912eIpefo9Bj" crossorigin="anonymous"></script>
<!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.0/css/ionicons.min.css" integrity="sha256-3iu9jgsy9TpTwXKb7bNQzqWekRX7pPK+2OLj3R922fo=" crossorigin="anonymous" />

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- select2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" integrity="sha256-PIRVsaP4JdV/TIf1FR8UHy4TFh+LiRqeclYXvCPBeiw=" crossorigin="anonymous" />
<!-- datepicker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<!-- Theme style -->
<link rel="stylesheet" href="/css/AdminLTE.min.css">
<!-- AdminLTE Skins. -->
<link rel="stylesheet" href="/css/skins/skin-green.min.css">
{{-- custom css --}}
<link rel="stylesheet" href="/css/main.css?rndstr={{ rand(1000,9999) }}">