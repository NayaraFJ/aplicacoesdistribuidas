 var table = "";

        $(document).ready(function () {
            table = new DataTable('#people', {
                ajax: 'get_people.php',
                "columns": [
                    {"data": "idpessoas"}, // Corresponds to column name in FETCH_ASSOC array
                    {"data": "nome"},
                    {"data": "status"},
                    {
                        "data": null,
                        "render": function (data, type, row) {
                            return  '<button class="edit-btn" data-idpessoas="'+row.idpessoas+'" data-nome="'+row.nome+'" data-status="'+row.status+'">Editar</button>' +
                                    '&nbsp;&nbsp;<button class="delete-btn" data-idpessoas="' + row.idpessoas + '">Excluir</button>';
                        }
                    }
                ],
                language: {
                     url:"assets/js/pt_br.json"
                }
            });

            $("#btSalvar").click(function () {
                const url = 'add_people.php'; // A URL da API
                var nome = $("#nome").val();
                var status = $("#status").val();

                const postData = {
                    nome: nome,
                    status: status
                };

                const urlEncodedData = new URLSearchParams(postData).toString();

                fetch('add_people.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: urlEncodedData
                })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json(); // Or response.text() if the server returns plain text  
                        })
                        .then(data => {
                            //adiciona uma nova linha se deu certo
                            var newRowData = {
                                idpessoas: data.idpessoas,
                                nome: data.nome,
                                status: data.status
                            };
                            table.row.add(newRowData).draw();
                            console.log('Success:', data);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });

            });
            
            //alterar
            $('#people tbody').on('click', '.edit-btn', function () {
                var id = $(this).data('idpessoas');
                var nome = $(this).data('nome');
                var status = $(this).data('status');
                
                $("#edidpessoas").val(id);
                $("#ednome").val(nome);
                $("#edstatus").val(status);
                
            });
            
            
            $("#btAlterar").click(function () {
                const url = 'add_people.php'; // A URL da API
                var idpessoas = $("#edidpessoas").val();
                var nome = $("#ednome").val();
                var status = $("#edstatus").val();

                const postData = {
                    idpessoas: idpessoas,
                    nome: nome,
                    status: status
                };

                const urlEncodedData = new URLSearchParams(postData).toString();

                fetch('update_people.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: urlEncodedData
                })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json(); // Or response.text() if the server returns plain text  
                        })
                        .then(data => {
                            if(data.success==true){
                            //atualiza uma nova linha se deu certo
                            var newRowData = {
                                idpessoas: idpessoas,
                                nome: nome,
                                status: status
                            };
                            table.rows().every(function() {
                                if (this.data().idpessoas === idpessoas) {
                                    this.data(newRowData).draw(); // Update data and redraw the table
                                }
                            });
                                alert("Registro atualizado com sucesso.");
                            }else{
                                alert("Falha ao atualizar o registro.");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
            });
            
            //excluir
            $('#people tbody').on('click', '.delete-btn', function () {
                //pega o id da linha que foi clicada
                var idpessoas = $(this).data('idpessoas');
                
                const postData = {
                    idpessoas: idpessoas
                };

                const urlEncodedData = new URLSearchParams(postData).toString();

                fetch('delete_people.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: urlEncodedData
                })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json(); // Or response.text() if the server returns plain text  
                        })
                        .then(data => {
                            if(data.success==true){
                                //identifica a linha clicada
                                var row = $(this).parents('tr');
                                // Remove a linha e redesenha a DataTable
                                table.row(row).remove().draw();
                                alert("Registro excluido com sucesso!");
                            }else{
                                alert("Falha ao excluir o registro!");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });

            });
                
                
            });
       