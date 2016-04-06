@extends('layout.datatable')
@section('head')
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <link href="css/jquery.dataTables.min.css" rel="stylesheet">

    <script type="text/javascript">
    $(document).ready(function() {
    $('#example1').DataTable();
    $('#example').DataTable();
    $('#example2').DataTable();
    } );
    
    </script>
@endsection



@section('content')
DATA ASLI
<table id="example"  class="display" cellspacing="0" width="100%">
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
            <?php $i=1;
            $jumlah = count($data_ready);

            for ($i=0; $i < $jumlah ; $i++) { 
            ?>
            
            
            <tr>
                <td>{{$i+1}}</td>
                <td>{{$data_ready[$i]['tweet']}}</td>
                <td><?php 
                $kelas = $data_ready[$i]['kelas'];
                if ($kelas==1) {
                    echo "Valid";
                }else if($kelas==2){
                    echo "Spam";
                }
                
                ?></td>
                
               
            </tr>
            <?php 	
            }
            ?>
           
           
        </tbody>
    </table>

DATA TRAINING 1
<table id="example1" class="display" cellspacing="0" width="100%">
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
            <?php $i=1;
            $jumlah = count($data_training0);

            for ($i=0; $i < $jumlah ; $i++) { 
            ?>
            
            
            <tr>
                <td>{{$i+1}}</td>
                <td>{{$data_training0[$i]['tweet']}}</td>
                <td><?php 
                $kelas = $data_training0[$i]['kelas'];
                if ($kelas==1) {
                    echo "Valid";
                }else if($kelas==2){
                    echo "Spam";
                }
                
                ?></td>
                
               
            </tr>
            <?php 	
            }
            ?>
           
           
        </tbody>
    </table>

DATA TES 1
<table id="example2" class="display" cellspacing="0" width="100%">
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
            <?php $i=1;
            $jumlah = count($data_test0);

            for ($i=0; $i < $jumlah ; $i++) { 
            ?>
            
            
            <tr>
                <td>{{$i+1}}</td>
                <td>{{$data_test0[$i]['tweet']}}</td>
                <td><?php 
                $kelas = $data_test0[$i]['kelas'];
                if ($kelas==1) {
                    echo "Valid";
                }else if($kelas==2){
                    echo "Spam";
                }
                
                ?></td>
                
               
            </tr>
            <?php 	
            }
            ?>
           
           
        </tbody>
    </table>



@endsection
