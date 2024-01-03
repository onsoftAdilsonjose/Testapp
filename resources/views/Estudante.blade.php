<html>
<head>
    <title>Html-Qrcode Demo</title>
      <script src="assets/js/jquery-3.6.4.min.js"></script>
             <style>
#qr-reader {
    width: 640px;
}
@media(max-width: 600px) {
  #qr-reader {
    width: 300px;
  }
}
.empty {
    display: block;
    width: 100%;
    height: 20px;
}
</style>
<body>
    <div id="qr-reader" style="width:500px"></div>
    <div id="qr-reader-results"></div>
     <div class="container">
    <div class="main-body">
      <div class="row gutters-sm">
        <div class="col-md-4 mb-3" id="escode1" style="display: none;">
          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">
                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                <div class="mt-3">
                  <h4 id="Fullname"></h4>
                </div>
                <div class="mt-3">
                  <p>Status : </p><h4 id="status"></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-8" id="escode" style="display: none;">
          <div class="card mb-3">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Nome Completo</h6>
                </div>
                <div class="col-sm-9 text-secondary" id="Fullname1">

                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Classe</h6>
                </div>
                <div class="col-sm-9 text-secondary" id="Classe">

                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Anolectivo</h6>
                </div>
                <div class="col-sm-9 text-secondary" id="Anolectivo">

                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Turma</h6>
                </div>
                <div class="col-sm-9 text-secondary" id="Turma">

                </div>
              </div>
              <hr>
              <div class="row">
              <div class="col-sm-3">
              <h6 class="mb-0">Sala</h6>
              </div>
              <div class="col-sm-9 text-secondary" id="Sala">

              </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Periodo</h6>
                </div>
                <div class="col-sm-9 text-secondary" id="Periodo">

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12" id="pagamentos" style="display: none;">
          <div class="row gutters-sm">
            <div class="col-sm-12 mb-3">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Meses</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <span id="MesComDivida"></span>,
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Total de Meses Com Divida</h6>
                  </div>
                  <div class="col-sm-9 text-secondary" id="MesesComMultas">
                  </div>
                </div>
                               <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Total Divida + Multa</h6>
                  </div>
                  <div class="col-sm-9 text-secondary" id="Divida">
                  </div>
                </div>
                <hr>
                                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Multa por Mes</h6>
                  </div>
                  <div class="col-sm-9 text-secondary" id="multapormes">
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Total Multa</h6>
                  </div>
                  <div class="col-sm-9 text-secondary" id="ValorDaMulta">
                  </div>
                </div>
                <hr>
              </div>
            </div>
          </div>
        </div>
 
      </div>
    </div>
  </div>
</body>
    <script src="html5-qrcode.min.js"></script>
<script>
    function docReady(fn) {
        // see if DOM is already available
        if (document.readyState === "complete"
            || document.readyState === "interactive") {
            // call on next available tick
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    docReady(function () {
        var resultContainer = document.getElementById('qr-reader-results');
        var lastResult, countResults = 0;

        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                ++countResults;
                lastResult = decodedText;
                // Handle on success condition with the decoded message.
                console.log(`Scan result ${decodedText}`, decodedResult);

                // Send a request to your API here
                sendRequestToApi(decodedText);
            }
        }

        function sendRequestToApi(data) {
            // Replace 'YOUR_API_ENDPOINT' with the actual endpoint of your API
            var apiUrl = 'http://localhost:8000/api/Scanestudante';
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // Add any additional headers if needed
                },
                body: JSON.stringify({ data: data }),
            })
            .then(response => response.json())
            .then(data => {

      $('#status').html('<strong style="' + (data.status ? 'color: red;': 'color: green;' ) + '">' + (data.status ? 'Devedor' :'Não Devedor' ) + '</strong>');
      $('#MesComDivida').html('<strong style="color: red;">' + data.resultArray.MesesComMultas.map(month => month.mesNome).join(', ') + '</strong>');
      $('#multapormes').html('<strong style="color: red;">' + parseFloat(data.multapormes).toFixed(2) + ' AOA</strong>');
      $('#Divida').html('<strong style="color: red;">' + parseFloat(data.totalcommulta).toFixed(2) + ' AOA</strong>');
      $('#ValorDaMulta').html('<strong style="color: red;">' + parseFloat(data.ValorDaMulta).toFixed(2) + ' AOA</strong>');
      $('#Fullname').html(data.aluno.full_name);
      $('#MesesComMultas').html('<strong>' + data.MesesComMultas + '</strong>');
      $('#Periodo').html('<strong>' + data.aluno.nomePeriodo + '</strong>');
      $('#Classe').html('<strong>' + data.aluno.classe_name + '</strong>');
      $('#Anolectivo').html('<strong>' + data.aluno.ano_lectivo + '</strong>');
      $('#Turma').html('<strong>' + data.aluno.nomeTurma + '</strong>');
      $('#Sala').html('<strong>' + data.aluno.nomeSala + '</strong>');
      $('#Fullname1').html('<strong>' + data.aluno.full_name + '</strong>');
      $('#regnumber').val('').focus();
         // Clear the input field and keep it in focus
      $('#escode').show();
      $('#escode1').show();
      $('#pagamentos').show();

                // Handle the API response if needed
                console.log('API Response:', data);
            })
            .catch(error => {
                console.error('Error sending request to API:', error);
            });
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>

</head>
</html>


