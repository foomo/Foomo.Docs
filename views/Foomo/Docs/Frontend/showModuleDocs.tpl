<? /*@var $view Foomo\MVC\View */ ?>
<?= $view->partial('header') ?>
<h2>Module <?= $model->docsModule ?></h2>
<?
/* @var $model Foomo\Docs\Frontend\Model */
foreach($model->getAvailableLanguages() as $lang): ?>
<div id="docs">
	<div id="docDirectory">
		<div class="innerDocs">
		<!--  
		<h2>Table of contents - <?php echo $lang ?></h2>
		-->
		<?= $model->getToc($lang); ?>
		</div>
	</div>
	<div id="docContents">
		<div class="innerDocs">
		<!-- 
		<h2>Contents</h2>
		 -->
		<?= $model->getContents($lang); ?>
		</div>
	</div>
</div>
<? endforeach; ?>
