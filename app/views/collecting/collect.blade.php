@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>PENGUMPULAN DATA</b>
@endsection
@section('subjudul')
    
    <a href="collect-form" class="btn btn-sm btn-warning" role="button">Cari Kembali</a>
@endsection
@section('content')
	<h2>Pilihan Data</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>No</th>
                                        <th>Konten Tweet</th>
                                        <th>Username</th>
                                    </tr>
                                </thead>
                                <tbody>
                                {{ Form::open(array('route'=>'save-tweet', 'method' => 'POST','role' => 'form')); }}
                                	<?php $jumlah = count($data_tweet)?>
                                	<?php for ($i=0; $i < $jumlah ; $i++) { ?>
                                		
                                	
                                    <tr>
                                        <td> 
                                    	<label>
                                        <input type="checkbox" name="confirm_save[]" value="{{$i}}">
                                    	</label>
                                
                                		</td>
                                        <td>{{$i+1}}
                                		</td>
                                        <td>{{$data_tweet[$i]['tweet']}}</td>
                                        <input type="hidden" name="tweet[]" value="{{$data_tweet[$i]['tweet']}}">
                                        <td>{{$data_tweet[$i]['username']}}</td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                            <div class="col-lg-2">
                            <div class="form-group">
                                <label>Kelas Tweet</label>
                                <select name="kelas_tweet" class="form-control">
                                    <option value=0>Spam</option>
                                    <option value=1>Valid</option>
                                   
                                </select>
                            </div>
                            {{Form::submit('Simpan Tweet', array('class' => 'btn btn-sm btn-info', ''));}}
                        	</div>
                            
                        </div>	
@endsection