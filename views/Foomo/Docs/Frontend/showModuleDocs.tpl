<? /*@var $view Foomo\MVC\View */ ?>
<?= $view->partial('header') ?>
<h2>Module <?= $model->docsModule ?></h2>
<?
/* @var $model Foomo\Docs\Frontend\Model */
foreach($model->getAvailableLanguages() as $lang): ?>

<? if( count($model->getAvailableLanguages()) > 1): ?>
	<h3><?php echo $lang ?></h3>
<? endif; ?>

<div id="docs">
	
	<div class="toggleBox">
		<div class="toogleButton">
			<div class="toggleOpenIcon">+</div>
			<div class="toggleOpenContent">Table of contents</div>
		</div>
		<div class="toggleContent">
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
