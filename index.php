<?php
  // error outputting for debugging
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $library = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
  require_once $library . 'GibberishDetector.php';
  require_once $library . 'trained_model.php';

  $candidate = false;
  if( !empty( $_GET['text'] ) ) {
    $candidate = $_GET['text'];
    $gibber = new GibberishDetector( $pretrained_gibberish_model );
    $result = $gibber->evaluate( $candidate, true );
  }
?>
  <h1>Gibberish Detector</h1>
  <p>Gibberish Threshold = <?php echo $pretrained_gibberish_model['threshold']; ?></p>
  <form method="GET">
    <input type="text" name="text" value="<?php echo htmlspecialchars( $candidate ); ?>"/>
    <input type="submit"/>
  </form>
  <?php if( $candidate ): ?>
    <strong><?php echo ( $result['is_gibberish'] ) ? 'Text is gibberish' : 'Text is ok'; ?></strong>
    (<?php echo $result['probability']; ?>)
  <?php endif; ?>
