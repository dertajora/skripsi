@extends('layout.datatable')
@section('head')
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <link href="css/jquery.dataTables.min.css" rel="stylesheet">

    <script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
    } );
    </script>
@endsection

@section('footer')
    
@endsection
@section('judul')
    <b>DATA TRAINING DATATABLE</b>
@endsection

@section('content')

<table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tweet</th>
                <th>Kelas</th>
                
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Tweet</th>
                <th>Kelas</th>
            </tr>
        </tfoot>
        <tbody>
            <?php $i=1;?>
            @foreach ($data_trainings as $row)
            <tr>
                <td>{{$i}}</td>
                <td>{{$row->tweet}}</td>
                <td><?php 
                $kelas = $row->kelas;
                if ($kelas==1) {
                    echo "Valid";
                }else if($kelas==2){
                    echo "Spam";
                }
                $i++;
                ?></td>
                
               
            </tr>
            @endforeach
           
        </tbody>
    </table>
@endsection
