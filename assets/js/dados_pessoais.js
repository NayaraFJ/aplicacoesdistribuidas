let tabelaDados = null;
let linhaSelecionada = null;

function formatarDataBR(dataISO) {
    if (!dataISO) {
        return '';
    }
    const partes = dataISO.split('-');
    if (partes.length !== 3) {
        return dataISO;
    }
    return `${partes[2]}/${partes[1]}/${partes[0]}`;
}

function montarRegistroTabela(registro) {
    return {
        matricula: registro.matricula,
        nome: registro.nome ?? '',
        email: registro.email ?? '',
        celular: registro.celular ?? '',
        data_nascimento: registro.data_nascimento,
        data_nascimento_br: registro.data_nascimento_br || formatarDataBR(registro.data_nascimento)
    };
}

function limparFormulario(criar = true) {
    const formulario = criar ? document.getElementById('form-create') : document.getElementById('form-edit');
    if (formulario) {
        formulario.reset();
    }
}

$(document).ready(function () {
    tabelaDados = new DataTable('#dados-pessoais', {
        ajax: 'get_dados_pessoais.php',
        columns: [
            { data: 'matricula' },
            { data: 'nome' },
            { data: 'email' },
            { data: 'celular' },
            { data: 'data_nascimento_br' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-primary edit-btn" data-matricula="${row.matricula}">Editar</button>
                        <button class="btn btn-sm btn-danger ms-1 delete-btn" data-matricula="${row.matricula}">Excluir</button>
                    `;
                }
            }
        ],
        language: {
            url: 'assets/js/pt_br.json'
        }
    });

    $('#btSalvarDados').on('click', function () {
        const dados = $('#form-create').serialize();

        fetch('create_dados_pessoais.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: dados
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Não foi possível salvar o registro.');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                const registro = montarRegistroTabela(data);
                tabelaDados.row.add(registro).draw(false);
                limparFormulario(true);
                alert('Registro cadastrado com sucesso!');
            })
            .catch(error => {
                alert(error.message);
                console.error(error);
            });
    });

    $('#dados-pessoais tbody').on('click', '.edit-btn', function () {
        const row = tabelaDados.row($(this).closest('tr'));
        const dados = row.data();
        linhaSelecionada = row;

        $('#edmatricula').val(dados.matricula);
        $('#ednome').val(dados.nome);
        $('#eddata_nascimento').val(dados.data_nascimento);
        $('#edemail').val(dados.email);
        $('#edcelular').val(dados.celular);
    });

    $('#btAlterarDados').on('click', function () {
        const dados = $('#form-edit').serialize();

        fetch('update_dados_pessoais.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: dados
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Não foi possível atualizar o registro.');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                const formulario = Object.fromEntries(new URLSearchParams(dados));
                const registroAtualizado = montarRegistroTabela({
                    matricula: formulario.matricula,
                    nome: formulario.nome,
                    email: formulario.email,
                    celular: formulario.celular,
                    data_nascimento: formulario.data_nascimento
                });

                if (linhaSelecionada) {
                    linhaSelecionada.data(registroAtualizado).draw(false);
                } else {
                    tabelaDados.rows().every(function () {
                        if (this.data().matricula === registroAtualizado.matricula) {
                            this.data(registroAtualizado).draw(false);
                        }
                    });
                }

                alert('Registro atualizado com sucesso!');
            })
            .catch(error => {
                alert(error.message);
                console.error(error);
            });
    });

    $('#dados-pessoais tbody').on('click', '.delete-btn', function () {
        const row = tabelaDados.row($(this).closest('tr'));
        const dados = row.data();

        if (!confirm('Deseja realmente excluir este registro?')) {
            return;
        }

        const payload = new URLSearchParams({ matricula: dados.matricula });

        fetch('delete_dados_pessoais.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: payload
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Não foi possível excluir o registro.');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                row.remove().draw(false);
                alert('Registro excluído com sucesso!');
            })
            .catch(error => {
                alert(error.message);
                console.error(error);
            });
    });
});
