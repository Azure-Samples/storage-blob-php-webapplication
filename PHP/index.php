<!DOCTYPE html>
<html lang="en" ng-app="app">
	<head>
		<meta charset="UTF-8">
		<base href="/" target="_blank, _self, _parent, _top">
		<title>Azure Storage Blob Models Sample in PHP</title>
		<link rel="stylesheet" type="text/css" href="public/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="public/css/ball-beat.min.css">
		<script src="public/js/main.js"></script>
	</head>
	<body>
		<div class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<a href="/" class="navbar-brand">Azure Storage Blob Models Sample in PHP</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li ng-if="isInit" ><a href="/trial" >Trial</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container body-content">
			<ng-view></ng-view>
		</div>
	</body>
</html>