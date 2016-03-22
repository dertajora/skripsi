<?php

class ExcelController extends BaseController {

    public function getExportDataTraining(){
         
     

    $derta = Excel::create('Datatraining', function ($excel) {

    $excel->sheet('Sheetname', function ($sheet) {

       
        $sheet->row(1, array('Data Training'));

        // second row styling and writing content
        $sheet->row(2, function ($row) {

            // call cell manipulation methods
            $row->setFontFamily('Times New Roman');
            $row->setFontSize(12);
            $row->setFontWeight('bold');

        });

        $sheet->row(2, array('Something else here'));
         // getting data to display - in my case only one record
        $datas = Datatraining::get()->toArray();

        // setting column names for data - you can of course set it manually
        $sheet->appendRow(array_keys($datas[0])); // column names

        // getting last row number (the one we already filled and setting it to bold
        $sheet->row($sheet->getHighestRow(), function ($row) {
            $row->setFontWeight('bold');
        });

        // putting users data as next rows
        foreach ($datas as $data) {
            $sheet->appendRow($data);
        }
       
    });

    })->export('xls');
    
    return "sukses";
    }

    public function getExportDataUji(){
         
     

    $derta = Excel::create('Datauji', function ($excel) {

    $excel->sheet('Sheetname', function ($sheet) {

       
        $sheet->row(1, array('Data Uji'));

        // second row styling and writing content
        $sheet->row(2, function ($row) {

            // call cell manipulation methods
            $row->setFontFamily('Times New Roman');
            $row->setFontSize(12);
            $row->setFontWeight('bold');

        });

        $sheet->row(2, array('Something else here'));
         // getting data to display - in my case only one record
        $datas = Datauji::get()->toArray();

        // setting column names for data - you can of course set it manually
        $sheet->appendRow(array_keys($datas[0])); // column names

        // getting last row number (the one we already filled and setting it to bold
        $sheet->row($sheet->getHighestRow(), function ($row) {
            $row->setFontWeight('bold');
        });

        // putting users data as next rows
        foreach ($datas as $data) {
            $sheet->appendRow($data);
        }
       
    });

    })->export('xls');
    
    return "sukses";
    }


  
       
   

      

}


    
