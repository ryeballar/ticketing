<!DOCTYPE html>
<html ng-app="app">
<head>
	<base href="/ticketing/">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>Online Ticketing System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">

	<link rel="stylesheet" href="assets/css/font-awesome.min.css" type="text/css" />

	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="assets/css/bootstrap-theme.min.css" type="text/css">	

	<link rel="stylesheet" href="assets/css/animate.css"  type="text/css"/>
	<link rel="stylesheet" href="assets/css/jquery.easy-pie-chart.css"  type="text/css"/>

	<link href="assets/css/style.css" rel="stylesheet" media="screen" type="text/css" />
</head>
<body>
	<header class="navbar navbar-inverse" ng-controller="PageHeaderController" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
		      <button type="button" class="navbar-toggle" ng-click="toggle()">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand"><img src="assets/img/logo.png"></a>
		    </div>

		    <div class="collapse navbar-collapse" ng-class="{in: isCollapse}">
				<ul class="nav navbar-nav navbar-right">
					<li bindonce ng-repeat="item in nav" nav-item 
						ng-click="collapse()"
						ng-class="{active: item.label == RouteData.get('activePage')}"
						bo-class="{dropdown: item.dropdown}"></li>
				</ul>
			</div>
		</div>
	</header>

	<section ng-view></section>

    <footer class="footer">
		<div class="container-fluid text-center">
			<span><a href="http://twitter.com"> <i class=" icon-twitter icon-4x"></i></a> </span>
			<span> <a href="http://plus.google.com"><i class=" icon-google-plus icon-4x"></i></a> </span>
			<span><a href="http://facebook.com"> <i class=" icon-facebook-sign icon-4x"></i></a> </span>
			<span> <a href="https://ph.linkedin.com"><i class=" icon-linkedin icon-4x"></i></a> </span>
			<br><br>
			<p>&copy; 2013 <a href="#">Terms &amp; Conditions.</a> All rights reserved @fritzsamsonbootsstrapguru</p>
		</div>
	</footer>

	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/jquery.easy-pie-chart.js"></script>
	<script src="assets/js/landing-animate.js"></script>

    <script src="assets/js/angular.min.js"></script>
    <script src="assets/js/angular-route.min.js"></script>
    <script src="assets/js/angular-resource.min.js"></script>
    <script src="assets/js/angular-cookies.min.js"></script>

    <script src="assets/js/ui-bootstrap-tpls-0.10.0.min.js"></script>

    <script src="assets/js/bindonce.min.js"></script>
    <script src="assets/js/route-data.js"></script>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/app-config.js"></script>
    <script src="assets/js/app-route-config.js"></script>
    <script src="assets/js/app-directives.js"></script>
    <script src="assets/js/app-controllers.js"></script>
    <script src="assets/js/app-services.js"></script>

</body>
</html>