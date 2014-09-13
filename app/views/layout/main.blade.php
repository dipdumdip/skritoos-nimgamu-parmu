<!DOCTYPE html> 
<html>
	<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=IE8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> {{( !empty($meta_data['title']) && isset($meta_data['title']) )? $meta_data['title'] : 'Home Page '}} </title>
      <meta property="og:image" content="{{(!empty($meta_data['fb_image']) && isset($meta_data['fb_image'])) ? $meta_data['fb_image'] : 'default.jpg'}}" />
      <meta property="og:type" content="news" />
      <meta property="og:title" content="{{(!empty($meta_data['fb_title']) && isset($meta_data['fb_title']) ) ? $meta_data['fb_title'] : ''}}" />
      <meta property="og:description" content="{{(!empty($meta_data['metaDescription']) && isset($meta_data['metaDescription'])) ? $meta_data['metaDescription'] : 'Free online .'}}" />

    <link rel='shortcut icon' type='image/x-icon' href="{{ URL::asset('img/favicon.ico')}}"/>
    <meta name="keywords" content="{{(!empty($meta_data['metaKeywords']) && isset($meta_data['metaKeywords']) ) ? $meta_data['metaKeywords'] : 'Free,'}}" />
    <meta name="description" content="{{(!empty($meta_data['metaDescription']) && isset($meta_data['metaDescription'])) ? $meta_data['metaDescription'] : 'Free online .'}}"/>
   <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ URL::asset('css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/body.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="{{ URL::asset('js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jquery.form.js.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jquery.validate.js') }}"></script>
<script>
      var APP_URL= '{{ URL::to('/') }}/';
</script>
<script type="text/javascript" src="{{ URL::asset('js/body_sript.js') }}"></script>

</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

  <div id="global_Container_wrap">
    <div id="MainWrapper">
      <div id="header_wrapper" class="header_height"> 
        <div id="header">
          <a href="{{ URL::route('home') }}">Stock Future</a>
          <div style="float:left">
         @if($page_checker)
            <div id="work_field"> 
              <each>Company Code:</each>
              <each><input id="company_symbol" style="text-transform:uppercase;" name="company_symbol" type="text" value="{{ $company_symbol }}" maxlength="7" autocomplete="off"/></each>
              <each>Accuracy:</each>
              <each> <select id="accuracy_change"  name="accuracy_change">
                    @for($x=1; $x<=9; $x++)
                      <option value="{{ $x }}" {? if($precision==$x){ echo 'selected="true"'; } ?} >
                       {{ $x }}0%</option>
                    @endfor
                  </select>
              </each>
              <each><div class="create_mid_buttton" id="check_result">Check</div></each>
              <div class="work_field_msg">Please enter a valid Company Symbol eg: FB, GOOG, AAPL..etc</div>
            </div>
         @endif
          </div>
        </div>
      
      </div>
    <div id="stockTicker_wrap" onmouseover="pxptick=0" onmouseout="pxptick=scrollspeed">
      <div id="stockTicker_cont">
        @if($page_checker)
           @include('layout.liveticker')
        @endif
      </div>
      <div id="ajax_image" style="margin:4px 35% 0; display:none;"><img  src="{{ URL::asset('img/icons/ajaxloader_hr.gif') }}"  /></div>
    </div>      
      <div id="contentWrapper">

    <!-- /main -->
          @yield('content')
      </div> <!--- contentWrapper _ends  -->
      
      <div id="footer_wrap" class="footer_height">
        <div id="footer">
 <p>&copy; 2005 - {{ date('Y').' StockLength.com  All rights reserved.' }}</p>        </div>
      </div>
    </div> <!--- main wrapper_ends  -->
   </div> <!--- global wrapper_ends  -->
  <!-- script references -->
<script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
</body>
</html>