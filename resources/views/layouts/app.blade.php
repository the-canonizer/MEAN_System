<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Canonizer</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <link rel="shortcut icon" href="img/favicon.ico" >
        <!-- Bootstrap core CSS-->
        <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="{{ URL::asset('/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <!-- Custom styles for this template-->
        <link href="{{ URL::asset('/css/canonizer.css') }}" rel="stylesheet">

        <!-- jquery  -->
        <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('/js/jquery-ui/jquery-ui.js') }}"></script>
        <link href="{{ URL::asset('/js/jquery-ui/jquery-ui.css') }}" rel="stylesheet" type="text/css">

        <!--countdown timers -->
        <script src="{{ URL::asset('/js/jquery.countdownTimer.min.js') }}"></script>
        <link href="{{ URL::asset('/css/jquery.countdownTimer.css') }}" rel="stylesheet" type="text/css">



    </head>
    <body>

        @section('sidebar')
        <nav class="navbar navbar-expand-lg" id="mainNav">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ URL::asset('/img/logo.png')}}">
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav search-box">
                    <li class="nav-item col-sm-7">
                       <form method="get" action="https://www.google.com/custom" target="_top">
                            <div class="input-group search-panel">
                               <table>
									<tr>
										<td class="radio radio-primary">
										<input type="radio" name="sitesearch" value="" checked id="ss0"></input>
										<label for="ss0" title="Search the Web"><font size="-1" color="black">Web</font></label></td>
										<td class="radio radio-primary">
										<input type="radio" name="sitesearch" value="canonizer.com" id="ss1" checked></input>
										<label for="ss1" title="Search canonizer.com"><font size="-1" color="black">Canonizer.com</font></label></td>
									</tr>
								</table>
                                <input type="hidden" name="search_param" value="all" id="search_param">
                                <input type="text" class="form-control search" name="q" id="sbi" placeholder="Search for...">

									<input type="submit" name="sa" value="Google Search" id="sbb"></input>
									<input type="hidden" name="client" value="pub-6646446076038181"></input>
									<input type="hidden" name="forid" value="1"></input>
									<input type="hidden" name="ie" value="ISO-8859-1"></input>
									<input type="hidden" name="oe" value="ISO-8859-1"></input>
									<input type="hidden" name="cof" value="GALT:#0066CC;GL:1;DIV:#999999;VLC:336633;AH:center;BGC:FFFFFF;LBGC:FF9900;ALC:0066CC;LC:0066CC;T:000000;GFNT:666666;GIMP:666666;LH:43;LW:220;L:https://canonizer.com/images/CANONIZER.PNG;S:https://;FORID:1"></input>
									<input type="hidden" name="hl" value="en"></input>
                              </div>
                        </form>
                    </li>
                    <li class="nav-item col-sm-5 text-right" style="padding-right:0px;">
                        @if(Auth::check())
						<div class="dropdown">
                            Browsing as: <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-user"></i> <span class="brsr-name">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name}} </span></a>
                            <span class="caret"></span>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('settings')}}">Account Settings</a></li>
                                <li><a href="{{ url('/logout')}}">Logout</a></li>
                            </ul>

                        </div>
                        @else
						<a class="nav-link guestLogin">Browsing as: Guest_31</a>
                        <a class="nav-link" href="{{ url('/login')}}"><i class="fa fa-fw fa-user"></i> Log in</a>
                        <a class="nav-link" href="{{ url('/register')}}"><i class="fa fa-fw fa-user-plus"></i> Register </a>
                        @endif
                    </li>
                </ul>
				<?php $route = Route::getCurrentRoute()->getActionMethod();
                $parameters = Route::current()->parameters();
                $id = (isset($parameters['id']) && $parameters['id']) ? $parameters['id'] : null;
                if($id == null){
                    if(isset($parameters['topicid']) && $parameters['topicid']){
                        if(isset($parameters['topicname'])){
                            $id = $parameters['topicid']."-".$parameters['topicname'];
                        }else{
                            $id = null;
                        }
                    }else{
                        $id = null;
                    }
                }
                 $campNum = (isset($parameters['campnum']) && $parameters['campnum']) ? $parameters['campnum'] : null;
                 $campUrl = "/camp/create";
                if($id!=null && $campNum !=null){
                    $campUrl = "/camp/create/$id/$campNum";
                }

				?>
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                    <ul class="uppermenu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/')}}">
                                <span class="nav-link-text {{ ($route=='index') ? 'menu-active':''}}">Canonizer Main</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/browse')}}">
                                <span class="nav-link-text {{ ($route=='browse') ? 'menu-active':''}}">Browse</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/create/topic')}}">

                                <span class="nav-link-text {{ ($route=='topic' & str_contains(Request::fullUrl(), 'topic') ) ? 'menu-active':''}}">Create New Topic</span>
                            </a>
                        </li>

						<?php if($route=='show' and strpos(Request::fullUrl(), 'forum' ) == 0 ) { ?>
                        
						<li class="nav-item">
                            <a class="nav-link" href='{{ url("$campUrl")}}'>

                                <span class="nav-link-text">Create New Camp</span>
                            </a>
                        </li>
						<?php } ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/upload') }}">
                                <span class="nav-link-text {{ ($route=='getUpload') ? 'menu-active':''}}">Upload File</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/topic/132-Help/1')}}">
                                <span class="nav-link-text {{ (str_contains(Request::fullUrl(), '132-Help')) ? 'menu-active':''}}">Help</span>
                            </a>
                        </li>
						<li class="nav-item">
                            <a class="nav-link" target="_blank" href="{{ url('/files/2012_amplifying_final.pdf')}}">
                                <span class="nav-link-text">White Paper</span>
                            </a>
                        </li>

						<li class="nav-item">
                            <a class="nav-link" href="{{ url('/blog')}}">
                                <span class="nav-link-text">Blog</span>
                            </a>
                        </li>
                    </ul>
					<?php
					$routeArray = app('request')->route()->getAction();
					$controllerAction = class_basename($routeArray['controller']);

                    list($controller, $action) = explode('@', $controllerAction);

					$visibleRoutes = array("index","show");

					if(in_array($route,$visibleRoutes) && $controller != "CThreadsController" && $controller != "SettingsController") { ?>
                    <ul class="lowermneu canoalgo">

					<!-- set algorithm as per request -->
					<?php



					$algorithms = \App\Model\Algorithm::getKeyList();
					if(isset($_REQUEST['canonizer']) && in_array($_REQUEST['canonizer'],$algorithms)) {
					  session(['defaultAlgo'=>$_REQUEST['canonizer']]);
					}
                    if(isset($_REQUEST['asof']) && $_REQUEST['asof'] !='') {
                      session(['asofDefault'=>$_REQUEST['asof']]);
                    }
                    if(isset($_REQUEST['asofdate']) && $_REQUEST['asofdate']) {
                      session(['asofdateDefault'=>$_REQUEST['asofdate']]);
                    }
					?>
                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
                            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#canoalgo">
                                <span class="nav-link-text">Canonizer</span>
                            </a>
                            <ul class="sidenav-second-level collapse show" id="canoalgo">
                                <li>
									<span>Canonizer Algorithm:</span>
                                    <select name="algorithm" onchange="changeAlgorithmChoice(this)">
                                    @foreach(\App\Model\Algorithm::getList() as $key=>$value)
                                        <option value="{{ $key }}" {{ session('defaultAlgo') == $key ? 'selected' : ''}}>{{$value}}</option>
                                    @endforeach
                                    </select>
									<a href="<?php echo url('topic/53-Canonized-Canonizer-Algorithms/2') ?>"><span>Algorithm Information</span></a>
                                </li>

                                <li>

                                    <div class="filter">Filter < <input onkeypress="changeFilterOnEnter(this,event)" onblur="changeFilter(this)" type="number" value="{{ isset($_REQUEST['filter']) && !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : '0.001' }}"/></div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="lowermneu asof">
                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
                            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#asof">
                                <span class="nav-link-text">As Of</span>
                            </a>
                            <ul class="sidenav-second-level collapse show" id="asof">
                                <li>
								 <form name="as_of" id="as_of" method="GET">
                                 <input type="hidden" id="filter" name="filter" value="{{ isset($_REQUEST['filter']) && !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : '0.001' }}"/>
								   <input type="hidden" name="_token" value="{{ csrf_token() }}">
									<div class="radio radio-primary">
										<input type="radio" <?php echo (session('asofDefault')=="review") ? "checked='checked'" : '';?> class="asofdate" name="asof" id="radio1" value="review">
										<label for="radio1">include review</label>
									</div>
									<div class="radio radio-primary">
										<input type="radio" <?php echo (session('asofDefault')!="review") || !(session('asofDefault')) ? "checked='checked'" : '';?> class="asofdate" name="asof" id="radio2" value="default">
										<label for="radio2">default</label>
									</div>
									<div class="radio radio-primary">
										<input type="radio" <?php echo (session('asofDefault')=="bydate") ? "checked='checked'" : '';?> class="asofdate" name="asof"id="radio3" value="bydate">
										<label for="radio3">as of (yy/mm/dd)</label>
									</div>
									<div><input readonly type="text" id="asofdate" name="asofdate" value="<?php echo (session('asofdateDefault')) ? session('asofdateDefault'): '';?>"/></div>
								</form>
                                </li>
                            </ul>
                        </li>
                    </ul>
					<?php } ?>
                </ul>
            </div>
        </nav>
        @show

        <div class="content-wrapper">
            @yield('content')
        <div class="homeADDright">
			@include('partials.advertisement')
		</div>
            <!-- footer -->
            @extends('layouts.footer')

            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" onclick="topFunction()" href="javascript:void(0)">
                <i class="fa fa-angle-up"></i>
            </a>
            <!-- Logout Modal-->
        </div>
    </div>
    <script>
        function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#asofdate").datepicker({
                changeMonth: true,
                changeYear: true,
				dateFormat: 'yy/mm/dd'
            });

			$(".asofdate, #asofdate").change(function(){
				// Do something interesting here
				 var value = $('#asofdate').val();

				 var bydate = $("input[name='asof']:checked"). val();

				 if(value=="" && bydate == 'bydate') {
					 $('#asofdate').focus();
				  return false;
				 }
				 $('#as_of').submit();
			});

        });

        function changeAlgorithmChoice(element){
            $.ajax({
                url:"{{ route('change-algorithm') }}",
                type:"POST",
                data:{algo:$(element).val()},
                success:function(){
                    window.location.reload();
                }
            });
        }

        function changeFilter(element){
            $('#filter').val($(element).val());

            $('#as_of').submit();
        }
		function changeFilterOnEnter(element,e){

		  if(e.keyCode === 13){
            e.preventDefault();
            $('#filter').val($(element).val());

            $('#as_of').submit();
		  }
        }
    </script>

</body>
</html>
