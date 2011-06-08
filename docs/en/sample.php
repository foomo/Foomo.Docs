++ Helpers by example

* link to a file download [<?= $model->getWikiFileUrl('birdie.png', $offerAsDownload = true) ?> get the bird]

* using a file as an image
[[image <?= $model->getWikiFileUrl('birdie.png') ?> width="20%" height="20%" alt="a super sample birdie" title="birdie himself"]]

// show this file
<geshi type="php" file="<?= $model->getRelativeFile('sample.php') ?>">
</geshi>