<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sistem Klasifikasi Tweet Berbahasa Indonesia - Derta Isyajora</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('head')

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home">Sistem Klasifikasi Tweet Berbahasa Indonesia</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Derta Isyajora Menuju S.Kom. <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="aktif">
                        <a href="home"><i class="fa fa-fw fa-home"></i> Home</a>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#data"><i class="fa fa-fw fa-table"></i> Data <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="data" class="collapse">
                            <li>
                                <a href="data-training">Data Training</a>
                            </li>
                            <li>
                                <a href="data-uji">Data Uji</a>
                            </li>
                        
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#pre-processing"><i class="fa fa-fw fa-wrench"></i> Praproses<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="pre-processing" class="collapse">
                            <li>
                                <a href="strtolower">Mengubah ke Huruf Kecil</a>
                            </li>
                            <li>
                                <a href="tokenization">Tokenization</a>
                            </li>
                            <li>
                                <a href="removeusername">Menghapus Username dan Hashtag</a>
                            </li>
                            <li>
                                <a href="removeurl">Menghapus URL</a>
                            </li>
                            <li>
                                <a href="removesymbol">Menghapus Simbol dan Angka</a>
                            </li>
                            
                            <li>
                                <a href="stopword">Menghapus Stopword</a>
                            </li>
                            <li>
                                <a href="stemmer">Stemming</a>
                            </li>
                           
                           <!--  <li>
                                <a href="maps-marker">Stopword Removal</a>
                            </li> -->
                            <li>
                                <a href="hasil-praproses">Hasil Praproses</a>
                            </li>
                        </ul>
                    </li>
                    <li> 
                        <a href="javascript:;" data-toggle="collapse" data-target="#seleksi"><i class="fa fa-fw fa-dashboard"></i></i> Seleksi Fitur <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="seleksi" class="collapse">
                            <li>
                                <a href="chi">Chi Square</a>
                            </li>
                            <li>
                                <a href="show-seleksi">Hasil Seleksi</a>
                            </li>
                            
                            
                        
                        </ul>
                    </li>
                
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#training"><i class="fa fa-fw fa-table"></i> Training <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="training" class="collapse">
                            <li>
                                <a href="multinomial">Multinomial</a>
                            </li>
                           <!--  <li>
                                <a href="hasil_multinomial">Hasil Training Multinomial</a>
                            </li> -->
                            <li>
                                <a href="bernoulli">Bernoulli</a>
                            </li>
                            <!-- <li>
                                <a href="hasil_bernoulli">Hasil Training Bernoulli</a>
                            </li> -->

                            <li>
                                <a href="hasiltraining">Hasil Training</a>
                            </li>
                            
                        
                        </ul>
                    </li>
                
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#klasifikasi"><i class="fa fa-fw fa-desktop"></i> Klasifikasi<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="klasifikasi" class="collapse">
                            
                            <li>
                                <a href="fromapi">Data dari Twitter API</a>
                            </li>
                            <li>
                                <a href="tesfromuser">Data dari User</a>
                            </li>
                            <!-- <li>
                                <a href="ujimultinomial">Klasifikasi Data Uji - Multinomial</a>
                            </li>
                            <li>
                                <a href="ujibernoulli">Klasifikasi Data Uji - Bernoulli</a>
                            </li> -->
                        </ul>
                    </li>
                    <li>
                         <a href="javascript:;" data-toggle="collapse" data-target="#evaluasi"><i class="fa fa-fw fa-bar-chart-o"></i> Evaluasi<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="evaluasi" class="collapse">
                            
                            <li>
                                <a href="pengaruhclassifier">Uji Classifier</a>
                            </li>
                            <li>
                                <a href="pengaruhseleksi">Uji Seleksi Fitur</a>
                            </li>
                            <li>
                                <a href="pengaruhdatatraining">Uji Data Training</a>
                            </li>
                            
                        </ul>
                    </li>
                      
                      
    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            @yield('judul') <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            @yield('subjudul')
                            <!-- <li class="active">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </li> -->
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

             @yield('content')
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    @yield('footer')

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>

</body>

</html>
