<?php
  // error outputting for debugging
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $directory = dirname(__FILE__) . DIRECTORY_SEPARATOR;
  $library   = $directory . 'lib' . DIRECTORY_SEPARATOR;
  $data      = $directory . 'trainingdata' .  DIRECTORY_SEPARATOR;
  require $library . 'GibberishDetector.php';

  $dictionary = file( $data . 'dictionary.txt' );
  $good       = file( $data . 'good.txt'       );
  $bad        = file( $data . 'bad.txt'        );

  $gibber = new GibberishDetector();
  $gibber->train( $dictionary, $good, $bad );

  $serialized_cache = $gibber->export_model();
  $raw_cache = $gibber->export_model( false );
?>
<h1>Training Results</h1>
<h3>Serialized Model</h3>
<pre><?php echo $serialized_cache; ?></pre>
<h3>PHP Model</h3>
<pre><?php echo var_export( $raw_cache ); ?></pre>
