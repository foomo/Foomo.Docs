<? /* @var $model Foomo\Docs\Frontend\Model */ ?>
<? foreach($model->getAvailableLanguages() as $lang): ?>

<? if( count($model->getAvailableLanguages()) > 1): ?>
	<h3><?php echo $lang ?></h3>
<? endif; ?>
<div id="docs">

	<div class="toggleBox">
		<div class="toogleButton">
			<div class="toggleOpenIcon">+</div>
			<div class="toggleOpenContent">
				Table of contents
			</div>
		</div>
		<div class="toggleContent">
			<?= $model->getToc($lang); ?>
		</div>
	</div>

	<div id="docContents">
		<div class="innerDocs">
		<?= $model->getContents($lang); ?>
		</div>
	</div>
	
	<a 
		class="linkButtonSmallGrey" 
		title="if you are writing this documentation, then this link is for you" 
		href="<?= $view->url('showModuleDocs', array($model->docsModule, 'update')); ?>"
	>update docs</a>	
	
</div>
<? endforeach; ?>	