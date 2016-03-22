@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>REMOVE STOPWORD</b>
@endsection

@section('content')
<!-- Term dari Data Training -->

<?php //print_r($token); ?>

Token unik dari Data Training
<pre>
<?php 

$vocab_unique = array_unique($token);
$vocab_reindexed = array_values($vocab_unique);
print_r($vocab_reindexed); ?>
</pre>

Hasil eliminasi username, hashtag dan URL
<pre>
<?php 


print_r($token_baru); ?>
</pre>
@endsection