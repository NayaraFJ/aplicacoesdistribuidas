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
        id: registro.id,
        nome_completo: registro.nome_completo ?? '',
        cpf: registro.cpf ?? '',
        data_nascimento: registro.data_nascimento,
        data_nascimento_br: registro.data_nascimento_br || formatarDataBR(registro.data_nascimento),
        email: registro.email ?? '',
        telefone: registro.telefone ?? '',
        endereco: registro.endereco ?? '',
        cidade: registro.cidade ?? '',
        estado: registro.estado ?? '',
        status: Number(registro.status),
        status_texto: registro.status_texto || (Number(registro.status) === 1 ? 'Ativo' : 'Inativo'),
        observacoes: registro.observacoes ?? ''
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
            { data: 'id' },
            { data: 'nome_completo' },
            { data: 'cpf' },
            { data: 'data_nascimento_br' },
            { data: 'email' },
            { data: 'telefone' },
            { data: 'endereco' },
            { data: 'cidade' },
            { data: 'estado' },
            { data: 'status_texto' },
            { data: 'observacoes' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}">Editar</button>
                        <button class="btn btn-sm btn-danger ms-1 delete-btn" data-id="${row.id}">Excluir</button>
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

        $('#edid').val(dados.id);
        $('#ednome_completo').val(dados.nome_completo);
        $('#edcpf').val(dados.cpf);
        $('#eddata_nascimento').val(dados.data_nascimento);
        $('#edemail').val(dados.email);
        $('#edtelefone').val(dados.telefone);
        $('#edendereco').val(dados.endereco);
        $('#edcidade').val(dados.cidade);
        $('#edestado').val(dados.estado);
        $('#edstatus').val(dados.status);
        $('#edobservacoes').val(dados.observacoes);
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
                    id: formulario.id,
                    nome_completo: formulario.nome_completo,
                    cpf: formulario.cpf,
                    data_nascimento: formulario.data_nascimento,
                    email: formulario.email,
                    telefone: formulario.telefone,
                    endereco: formulario.endereco,
                    cidade: formulario.cidade,
                    estado: formulario.estado,
                    status: formulario.status,
                    observacoes: formulario.observacoes
                });

                if (linhaSelecionada) {
                    linhaSelecionada.data(registroAtualizado).draw(false);
                } else {
                    tabelaDados.rows().every(function () {
                        if (this.data().id === registroAtualizado.id) {
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

        const payload = new URLSearchParams({ id: dados.id });

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
