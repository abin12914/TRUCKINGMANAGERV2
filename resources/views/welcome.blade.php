<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Welcome | {{ env('APP_NAME', 'TM2') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/f4a826a612.js" integrity="sha384-tCIa9x35rFwAnPYwDCoqImEiB9Ai31jfzeslK4Q53KZAXSFi24Px912eIpefo9Bj" crossorigin="anonymous"></script>
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.0/css/ionicons.min.css" integrity="sha256-3iu9jgsy9TpTwXKb7bNQzqWekRX7pPK+2OLj3R922fo=" crossorigin="anonymous" />
    <!-- Theme style -->
    <link rel="stylesheet" href="/css/AdminLTE.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
   <b>{{ env('APP_NAME', 'TM2') }}</b>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
      <h2 class="text-center">Welcome to Trucking Manager</h2>
    <p class="login-box-msg"></p>
    <div class="social-auth-links text-center">
      <a href="{{ route('dashboard') }}">
          <i class="fa fa-dashboard"></i> Dashboard
      </a>
    </div>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!-- Sweet Alert 8.19.0 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.19.0/dist/sweetalert2.all.min.js" integrity="sha256-H7W99VLbKVqW6ktckGeRWdtiQX+2n+C1d5Llfa95z9k=" crossorigin="anonymous"></script>
<!-- AdminLTE App -->
<script src="/js/adminlte.min.js"></script>
{{-- Custom JS --}}
<script src="/js/main.min.js"></script>
{{-- message type and message for sweet alert --}}
<script type="text/javascript">
    alertType    = "{{ Session::get('alert-class') }}";
    alertMessage = "{{ Session::get('message') }}";
</script>
</body>
</html>
