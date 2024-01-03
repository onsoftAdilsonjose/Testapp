<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="Esta Pagina e Para Verificacão de Estudante Com Divida">
    <meta name="keywords" content="HTML, CSS,JavaScript, framework Laravel , bootstrap 4">
    <meta name="author" content="Onsoft">
    <title>Scan Estudante</title>
    <link rel="OnSchool 0.9.0 Beta V" href="onloading.png">
    <!-- build:css -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

 
<style type="text/css">
  .panel {
    font-family: 'Raleway', sans-serif;
    padding: 0;
    border: none;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
}

.panel .panel-heading {
    background: #535353;
    padding: 15px;
    border-radius: 0;
}

.panel .panel-heading .btn {
    color: #fff;
    background-color: #000;
    font-size: 14px;
    font-weight: 600;
    padding: 7px 15px;
    border: none;
    border-radius: 0;
    transition: all 0.3s ease 0s;
}

.panel .panel-heading .btn:hover {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.panel .panel-heading .form-horizontal .form-group {
    margin: 0;
}

.panel .panel-heading .form-horizontal label {
    color: #fff;
    margin-right: 10px;
}

.panel .panel-heading .form-horizontal .form-control {
    display: inline-block;
    width: 80px;
    border: none;
    border-radius: 0;
}

.panel .panel-heading .form-horizontal .form-control:focus {
    box-shadow: none;
    border: none;
}

.panel .panel-body {
    padding: 0;
    border-radius: 0;
}

.panel .panel-body .table {
    width: 100%;
    border-collapse: collapse;
}

.panel .panel-body .table thead tr th {
    color: #fff;
    background: #8D8D8D;
    font-size: 14px;
    font-weight: 700;
    padding: 14px;
    border-bottom: none;
}

.panel .panel-body .table tbody tr td {
    color: #555;
    background: #fff;
    font-size: 15px;
    font-weight: 500;
    padding: 13px;
    vertical-align: middle;
    border-color: #e7e7e7;
}

.panel .panel-body .table tbody tr:nth-child(odd) td {
    background: #f5f5f5;
}

.panel .panel-body .table tbody .action-list {
    padding: 0;
    margin: 0;
    list-style: none;
}

.panel .panel-body .table tbody .action-list li {
    display: inline-block;
}

.panel .panel-body .table tbody .action-list li a {
    color: #fff;
    font-size: 13px;
    line-height: 28px;
    height: 28px;
    width: 33px;
    padding: 0;
    border-radius: 0;
    transition: all 0.3s ease 0s;
}

.panel .panel-body .table tbody .action-list li a:hover {
    box-shadow: 0 0 5px #ddd;
}

.panel .panel-footer {
    color: #fff;
    background: #535353;
    font-size: 16px;
    line-height: 33px;
    padding: 25px 15px;
    border-radius: 0;
}

.pagination {
    margin: 0;
}

.pagination li a {
    color: #fff;
    background-color: rgba(0, 0, 0, 0.3);
    font-size: 15px;
    font-weight: 700;
    margin: 0 2px;
    border: none;
    border-radius: 0;
    transition: all 0.3s ease 0s;
}

.pagination li a:hover,
.pagination li a:focus,
.pagination li.active a {
    color: #fff;
    background-color: #000;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.4);
}

</style>
  </head>

  <body>
    <br><br>
 <div class="container">
    <div class="row">

        <div class="col-md-offset-1 col-md-12">
            <div class="panel">
 
                <div class="panel-body table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                 
                                <th>Nome</th>
                                <th>Nascimento</th>
                                <th>Classe</th>
                                <th>Anolectivo</th>
                                <th>Periodo</th>
                               <!--  <th >Curso</th> -->
                                <th>Barcode</th>
                            </tr>
                        </thead>

                        <tbody>
                           @foreach($estudantes as $estudante)
                            <tr >
                                 
                                <td>{{$estudante->full_name}}</td>

                                <td>{{$estudante->dataofbirth}}</td>
                                 <td>{{$estudante->classe_name}}</td>
                                <td>{{$estudante->ano_lectivo}}</td>
                                <td>{{$estudante->nomePeriodo}}</td>
                              <!--   <td>{{$estudante->nomeCurso}}</td> -->
                                <td>
                                
                                    {!! DNS2D::getBarcodeHTML("$estudante->reg_Numero", 'QRCODE') !!}

                                </td>
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>
        
            </div>
        </div>
    </div>
</div>
     

    <!-- build:js -->
    <script src="assets/js/main.js"></script>
     <!-- endbuild -->
  </body>

</html>
