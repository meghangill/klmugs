<?php if($url != ''){ ?>
	<div class="tab-pane active" id="page" ng-controller="AngularBlog">
<?php }else{ ?>
	<div class="tab-pane" id="page">
<?php } ?>

		<div ng-repeat="post in posts">

			<div class="page-header top">
				<h1>{{post.title}} <a href="./" class="btn btn-small pull-right">back</a></h1>
			</div>
			<p class="clearfix">&nbsp;</p>
			<p><div ng-bind-html-unsafe="post.content"></div></p>

		</div>

</div>