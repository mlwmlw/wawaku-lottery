<!DOCTYPE html>
<html ng-app="app">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title><?=isset($title) ? $title . ' - ': ''?>臉書留言抽獎</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/public/css/style.css">
		<meta property="fb:app_id" content="183271221771652">
		<!--meta property="og:url" content="http://lottery.mlwmlw.org"-->
		<meta property="og:type" content="website">
		<meta property="og:title" content="<?=isset($title) ? $title . ' - ': ''?>臉書留言抽獎">
		<meta property="og:image" content="http://lottery.mlwmlw.org/public/logo.png">
		<meta property="og:description" content="<?=isset($desc) ? $desc: "舉辦公平公正公開的的臉書留言抽獎活動"?>">
		<meta property="og:site_name" content="臉書留言抽獎">
		<meta property="og:locale" content="zh_TW">
  </head>
  <body ng-controller="ctrl">
		
		<section class="hero is-success">
			<div class="hero-head">
				<header class="nav">
					<div class="container">
						<div class="nav-left">
							<a class="nav-item">
								<i class="fa fa-smile-o" aria-hidden="true"></i>
								　臉書留言抽獎
							</a>
						</div>
						<div class="nav-right nav-menu">
							<a class="nav-item is-active">
								Home
							</a>
							<a target="_blank" href="https://github.com/mlwmlw/Wawaku-Lottery/wiki" class="nav-item">
								Documentation
							</a>
							<span class="nav-item">
								<a target="_blank" href="https://github.com/mlwmlw/Wawaku-Lottery/issues" class="button is-success is-inverted">
									<span class="icon">
										<i class="fa fa-github"></i>
									</span>
									<span>Issue</span>
								</a>
							</span>
						</div>
					</div>
				</header>
			</div>	
		</section>
		<? if(isset($winner)):?>
		<div class="content columns is-mobile">
			<div class="column is-half-desktop is-offset-one-quarter-desktop">
				<div class="fb-comment-embed" data-href="<?=$url?>?comment_id=<?=$winner?>" data-width="560" data-include-parent="false"></div>	
			</div>
		</div>
		<div class="content columns is-mobile">
			<div class="column is-half-desktop is-offset-one-quarter-desktop">
				<div class="fb-share-button is-pulled-right" data-href="<?=$uri?>" data-layout="button_count" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode($uri)?>;src=sdkpreparse">分享</a></div>
			</div>
		</div>
		<? endif;?>
		<div class="content columns is-mobile">
			<div class="column is-mobile is-half-desktop is-offset-one-quarter-desktop">
				<form ng-submit="query()">
				<p class="control has-icon has-addons">
					<input ng-model="url" class="input is-expanded" type="url" placeholder="FB 文章網址"> <i class="fa fa-facebook"></i> <a class="button is-info" ng-click="query()" ng-class="{'is-loading': loading}">
						載入抽獎名單
					</a>
				</p>
					</form>
			</div>
		</div>
		<div class="columns is-mobile">
			<div class="column has-text-centered" ng-if="!comments">
				<iframe src="//giphy.com/embed/3o6gDTswSOojDjw080" width="480" height="406" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="http://giphy.com/gifs/3o6gDTswSOojDjw080">via GIPHY</a></p>
			</div>
			<div class="column is-3 is-offset-9" ng-if="comments">
				<a ng-click="random()" class="button is-primary is-large">打亂</a>
				<a class="button is-warning is-large" ng-click="lottery()">抽獎</a>
			</div>
		</div>
		<div class="columns is-multiline content">
			<div class="column is-mobile is-one-third-tablet is-one-quarter-desktop"  ng-repeat="comment in comments">
				<div class="card is-fullwidth notification" ng-class="{'is-success': $index == anwser}">
					<header class="card-header">
						<p id="card{{$index}}"  class="card-header-title">
							<a target="_blank" href="//fb.com/{{comment.id}}">{{comment.name}}</a>
						</p>
					</header>
					<div class="card-content">
						<div class="content">
							{{comment.msg}}
							<br />
							<small class="is-pulled-right">{{comment.created_time}}</small>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="modal winner" ng-class="{'is-active': winner}">
			<div class="modal-background"></div>
			<div class="modal-card">
				<header class="modal-card-head">
					<p class="modal-card-title">
					<a target="_blank" href="//fb.com/{{winner.comment.id}}">{{winner.comment.name}}</a>
					</p>
					<button class="delete" ng-click="winner = null"></button>
				</header>
				<section class="modal-card-body">
				<article class="media">
					<figure class="media-left">
						<p class="image is-100x100">
							<img ng-src="{{winner.user.url}}">
						</p>
					</figure>
					<div class="media-content">
						{{winner.comment.msg}}
						<br />
						<small class="is-pulled-right">{{comment.created_time}}</small>

					</div>
				</article>
				</section>
				<footer class="modal-card-foot">
					<a class="button is-success" target="_blank" href="/winner?url={{url}}&winner={{winner.comment.post}}">得獎連結</a>
					<a class="button is-primary" target="_blank" href="{{url + '?comment_id=' + winner.comment.post}}">查看留言</a>
					<a class="button is-warning" ng-click="winner = null; lottery()">抽下一個</a>
					<a class="button" ng-click="winner = null">結束</a>
				</footer>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
		<script>
			var app = angular.module('app', []);
			app.controller('ctrl', function($scope, $http, $timeout) {
				var comments = [];
				$scope.random = function() {
					for(var i = 0; i < (comments.length < 1000 ? 4: 1); i++)
						$timeout(function() {
							for(var i in comments) 
									comments[i].rank = 0.5 - Math.random();
							comments.sort(function(a, b) {
								return a.rank - b.rank;
							});
						}, i * 500);
				};
				$scope.url = "https://www.facebook.com/DulcieKu/posts/1608893232772506";
				$scope.url = "https://www.facebook.com/DulcieKu/posts/1608319182829911:0";
				$scope.url = "https://www.facebook.com/buy123TW/posts/546072625580681";
				$scope.lottery = function() {
					$scope.anwser = Math.round(comments.length * Math.random());
					location.hash = "card" + $scope.anwser;
					var y = window.scrollY;
					scrollTo(0, y - window.screen.height/3);
					$http.get('/user?id=' + comments[$scope.anwser].id).success(function(user) {
						$timeout(function() {
							$scope.winner = {user: user, comment: comments[$scope.anwser]};
						}, 400);
					});
				}
				$scope.query = function() {
					$scope.loading = true;
					$http.get('/comments?url=' + $scope.url).success(function(res) {
						comments = res;
						$scope.comments = comments;
					}).error(function() {
						alert('文章網址錯誤。');
					}).finally(function() {
						$scope.loading = false;
					});
				};
			});	
		</script>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.6&appId=183271221771652";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>	
  </body>
</html>
