# Gibberish Detector
[Demo Page](https://gschoppe.com/projects/gibberish/) | [Training Output](https://gschoppe.com/projects/gibberish/train.php)

I originally built this gibberish detector in 2013, as a challenge. I had toyed
with the idea of blocking all users with gibberish names from accessing a forum
I was building, but it seemed like a clear example of the
["Scunthorpe" problem](https://www.youtube.com/watch?v=CcZdwX4noCE) that
profanity filters face.

However, it seemed like a dumb detector could be used to flag users for review,
rather than to block or filter them. With a tool that could bulk-remove any
content they created, it could be a completely transparent process for the user.

To initialize the gibberish detector, you either need to train it with a large
volume of known text, a selection of gibberish, and a selection of text that
should pass, or you need to provide it with cached trained model, which can be
exported from a trained instance.

In 2019, I received an email requesting that I post the source online. To that
end I did a complete rewrite to better separate concerns and offer greater
flexibility than my original implementation.

The primary methods of the GibberishDetector class are:

* `set_charset( $charset )` - Sets the characters that should be evaluated for
gibberish. For example, numbers should not be assessed for gibberish. Takes a
lowercase string containing each of the characters to evaluate.
* `train( &$dictionary, &$good, &$bad )` - Trains the model to identify
gibberish, based on a known volume of text (I use e-books from project
gutenberg), a set of known good examples, and a set of known bad examples. Each
parameter is an array of lines, such as produced by `file()`.
* `export_model( $serialize )` - Exports a cache of the trained model, for use
in future instances of the class. takes a boolean, to choose whether to export
as a serialized string or an associative array. Either can be input easily, and
a native array will give the best performance, but certain implementations may
find it simpler to work with the more portable serialized string.
* `import_model( $model )` - Imports a cached trained model, to allow an
instance to skip the training stage. takes a pre-trained model either as a
serialized string or an associative array.
* `evaluate( $candidate_text, $verbose )` - Tests a string and evaluates whether
it is gibberish or not. Takes a sample of text to test as the first parameter.
The second optional parameter allows you to return an associative array, rather
than a boolean, containing the actual value returned by the model, as well as
the threshold contained in the model, and the final determination.

## Known Weaknesses

* The string functions used in GibberishDetector are currently not multi-byte
sensitive. to support languages other than English, these could be swapped for
mb_string equivalents, to give proper UTF8 support.
* I decided to make gibberish detection case-insensitive, but this might not be
the correct decision for all use cases. This behavior could be set by a flag in
the training model.
* Currently the Markov chain used is only two characters deep. It could be
refactored to support Markov chains of both 2 and 3 length, for better accuracy.
However, memory use would increase significantly, as the model would be an order
of magnitude larger.
* The core issue that cannot be addressed by this code is that there will always
be a significant chance of false positives and false negatives in any detector
that lacks a human element. For this reason **I STRONGLY ADVISE AGAINST USING
GIBBERISH DETECTOR AS A HARD FILTER TO PREVENT FORM COMPLETION.** At most, this
class should be used to flag entries for manual review.
