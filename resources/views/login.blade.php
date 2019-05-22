@extends('masterlogin')
@section('content')
@section('title','Login')

<body>
    <!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<div class="error-pagewrap">
		<div class="error-page-int">
			<div class="text-center m-b-md custom-login">
				<h3>ERP SYSTEM</h3>
				<p>Please login to ERP</p>
			</div>
			<div class="content-error">
				<div class="hpanel">
                    <div class="panel-body">
                        <form action="login" method="post" id="loginForm">
                        {{csrf_field()}}
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label class="control-label" for="username">Username</label>
                                <input type="text" placeholder="example@gmail.com"  value="" name="username" id="username" class="form-control">
                                <span class="help-block small">Your unique username to erp</span>
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label class="control-label" for="password">Password</label>
                                <input type="password" placeholder="******"  value="" name="password" id="password" class="form-control">
                                <span class="help-block small">Your strong password</span>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="checkbox login-checkbox">
                                <label>
										<input type="checkbox" class="i-checks" {{ old('remember') ? 'checked' : '' }}> Remember me </label>
                                <p class="help-block small">(if this is a private computer)</p>
                            </div>

                            <button class="btn btn-success btn-block loginbtn" type="submit">Login</button>
                             <label class="control-label">Don't remember your password ?  </label><a style="background: none; font-weight: bold; font-size: 18px; color: blue;" href="{{ route('password.request') }}">Forgot Password</a>
                            
                        </form>
                    </div>
                </div>
			</div>
			<div class="text-center login-footer">
				<p>Copyright © 2019. All rights reserved.</p>
			</div>
		</div>   
    </div>
    @endsection